<?php

namespace Modules\Acl\app\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\Acl\app\Ldap\LdapCustomGroup;
use Modules\Acl\app\Ldap\LdapCustomUser;

class SyncLdapCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'ldap:sync-all';

    /**
     * @var string
     */
    protected $description = 'Sync this order: 1) groups, 2) filtered users, 3) link users to their groups';

    /**
     * @return int
     */
    public function handle(): int
    {
        if (!config('ldap.enabled', false)) {
            $this->info('LDAP disabled.');

            return self::SUCCESS;
        }

        $userService = app('acl_user_service');
        $allowedGroups = app('acl_user_service')->getLdapAllowedGroups();

        // -------------------------------------------------------------
        // Import groups
        // -------------------------------------------------------------
        $this->info('Import LDAP-Groups ...');

        LdapCustomGroup::get()
            ->each(function (LdapCustomGroup $ldapGroup) use ($userService) {
                $cn = $ldapGroup->getFirstAttribute('cn');
                if ($cn) {
                    $userService->saveLdapGroup($cn);
                }
            });

        // -------------------------------------------------------------
        // Import filtered users
        // -------------------------------------------------------------
        $this->info("Import users from group: [".implode(', ', $allowedGroups)."] ...");

        $usersImported = 0;
        $usersFailed = 0;
        $usersFound = [];
        foreach (
            LdapCustomGroup::whereIn('cn', $allowedGroups)
                ->get() as $targetGroup
        ) {
            if (!$targetGroup) {
                $groupName = $targetGroup->getFirstAttribute('cn');
                $this->error("LDAP-Group '{$groupName}' not found!");

                continue;
            }

            $memberDns = $targetGroup->getAttribute('member') ?? [];
            foreach ($memberDns as $dn) {
                try {

                    /** @var LdapCustomUser $ldapUser */
                    $ldapUser = LdapCustomUser::find($dn);

                    if (!$ldapUser) {
                        // cascade group or deleted user
                        continue;
                    }

                    $userId = $ldapUser->getFirstAttribute('uid');
                    $userName = $ldapUser->getFirstAttribute('cn');
                    $email = $ldapUser->getFirstAttribute('mail');

                    // preventing import same user again
                    //user could already just be imported by a previous group
                    if ($usersFound[$userId.$userName.$email] ?? null) {
                        continue;
                    }
                    $usersFound[$userId.$userName.$email] = true;

                    // Email fallback
                    if (blank($email)) {
                        $userEmailIdent = $userId ?? $userName ?? Str::random(8);
                        $email = Str::lower($userEmailIdent).'@fake.local';
                    }

                    // The email is unique: if another user (local or another ldap guid) already owns it,
                    // updateOrCreate() would fail with a duplicate key. Report it clearly instead.
                    $guid = $ldapUser->getConvertedGuid();
                    $owner = app('user')::where('email', $email)
                        ->first();
                    if ($owner && !($owner->origin === 'ldap' && $owner->guid === $guid)) {
                        $usersFailed++;
                        $this->reportError(sprintf("LDAP user '%s' (guid %s) skipped: email '%s' is already used by user #%s '%s' (origin: %s).",
                            $userName ?? $userId,
                            $guid,
                            $email,
                            $owner->getKey(),
                            $owner->name,
                            $owner->origin ?? '-',));

                        continue;
                    }

                    // create/update the user in the database
                    $user = app('user')::updateOrCreate([
                        'guid'   => $guid,
                        'origin' => 'ldap',
                    ], [
                        'name'      => $userName ?? $userId,
                        'email'     => $email,
                        'shared_id' => uniqid('ldap_'),
                        'domain'    => config('ldap.default', 'default'),
                    ]);

                    // -------------------------------------------------------------
                    // Assign users to all their groups
                    // -------------------------------------------------------------
                    $userLdapGroupNames = $ldapUser->groups()
                        ->get()
                        ->map(function ($g) {
                            return $g->getFirstAttribute('cn');
                        })
                        ->filter()
                        ->toArray();

                    $userService->syncLdapGroups($user, $userLdapGroupNames);

                    $usersImported++;
                } catch (Exception $e) {
                    $usersFailed++;
                    $this->reportError(sprintf("LDAP user '%s' failed: %s", $dn, $e->getMessage()), $e);
                }
            }


        };

        $message = sprintf("LDAP-Sync finished. Users imported: %s/%s, failed: %s.", $usersImported, count($usersFound), $usersFailed);
        if ($usersFailed) {
            $this->error($message);
            Log::error($message);

            return self::FAILURE;
        }
        $this->info($message);
        Log::info($message);

        return self::SUCCESS;
    }

    /**
     * Shows the error in the console and writes it to the log as error (not debug).
     *
     * @param  string          $message
     * @param  Exception|null  $exception
     *
     * @return void
     */
    protected function reportError(string $message, ?Exception $exception = null): void
    {
        $this->error($message);
        Log::error($message, $exception ? ['exception' => $exception] : []);
    }
}