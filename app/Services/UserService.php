<?php

namespace Modules\Acl\app\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use LdapRecord\Models\Collection;
use Modules\Acl\app\Models\AclGroup;
use Modules\Acl\app\Models\User;
use \App\Models\User as AppUser;
use Modules\SystemBase\app\Services\Base\BaseService;

class UserService extends BaseService
{
    /**
     * If trying to assign extern username, but it's already exist.
     * see getNextAvailableUserName()
     *
     * @var array|string[]
     */
    public array $nameSuffixes = [
        'vom Licht',
        'von der Wahrheit',
        'von der Liebe',
        'des TAOs',
        'von Gott gesandt',
        'von der Unendlichkeit',
        'die Weisheit',
        'von der Weisheit',
        'vom Glück',
        'aus dem Traum',
        'des Geschicks',
        'vom Wald',
    ];

    /**
     * @return AppUser|null
     */
    public static function getCurrentUserInstance(): ?AppUser
    {
        return app('user');
    }

    /**
     * @param  string  $aclResourceId
     * @param  bool    $validUsersOnly
     *
     * @return Builder
     */
    public static function getUserBuilderByAclResourceId(string $aclResourceId, bool $validUsersOnly = false): Builder
    {
        $builder = $validUsersOnly ? app('user')::getBuilderFrontendItems() : app('user')::query();

        return $builder->with(['aclGroups.aclResources'])
            ->whereHas('aclGroups.aclResources', function ($query) use ($aclResourceId) {
                return $query->where('id', '=', $aclResourceId);
            });
    }

    /**
     * @param  string  $aclGroupId
     * @param  bool    $validUsersOnly
     *
     * @return Builder
     */
    public static function getUserBuilderByAclGroupId(string $aclGroupId, bool $validUsersOnly = false): Builder
    {
        $builder = $validUsersOnly ? app('user')->getBuilderFrontendItems() : app('user')->query();

        return $builder->with(['aclGroups'])
            ->whereHas('aclGroups', function ($query) use ($aclGroupId) {
                return $query->where('id', '=', $aclGroupId);
            });
    }

    /**
     * Users by acl resources OR user id.
     * Every user is returned once!
     *
     * @param  array  $aclResourcesIdList
     * @param  array  $userIdList
     * @param  bool   $validUsersOnly
     *
     * @return Builder
     */
    public static function getUserBuilderByAclResourcesOrUserIds(
        array $aclResourcesIdList,
        array $userIdList,
        bool $validUsersOnly = false
    ): Builder {
        $builder = $validUsersOnly ? app('user')->getBuilderFrontendItems() : app('user')->query();

        return $builder->with(['aclGroups.aclResources'])
            ->where(function (Builder $b1) use (
                $aclResourcesIdList,
                $userIdList
            ) {
                $b1->whereHas('aclGroups.aclResources', function ($query) use ($aclResourcesIdList, $userIdList) {
                    return $query->whereIn('id', $aclResourcesIdList);
                })
                    ->orWhereIn('id', $userIdList);
            });
    }

    public function getUserBuilderByAclResource(string $aclResourcePath, bool $validUsersOnly = false): Builder
    {
        $builder = $validUsersOnly ? app('user')->getBuilderFrontendItems() : app('user')->query();

        return $builder->with(['aclGroups.aclResources'])
            ->whereHas('aclGroups.aclResources', function ($query) use ($aclResourcePath) {
                return $query->where('code', '=', $aclResourcePath);
            });
    }

    public function getUserBuilderByAclResources(array $aclResourcesIdList, bool $validUsersOnly = false): Builder
    {
        $builder = $validUsersOnly ? app('user')->getBuilderFrontendItems() : app('user')->query();

        return $builder->with(['aclGroups.aclResources'])
            ->whereHas('aclGroups.aclResources', function ($query) use ($aclResourcesIdList) {
                return $query->whereIn('id', $aclResourcesIdList);
            });
    }

    /**
     * @return void
     */
    public function destroyUserSession(): void
    {
        Auth::guard('web')
            ->logout();
        session()->invalidate();
        session()->regenerateToken();
    }

    /**
     * If trying to assign extern username, but it's already exist.
     *
     * @param  string  $startName
     *
     * @return string
     */
    public function getNextAvailableUserName(string $startName): string
    {
        if (!($userFound = User::whereName($startName)
            ->first())
        ) {
            return $startName;
        }

        // loop 500 * amount of $nameSuffix
        for ($i = 1; $i <= 500; $i++) {
            foreach ($this->nameSuffixes as $nameSuffix) {
                $name = $startName;
                if ($nameSuffix) {
                    $name .= ' '.$nameSuffix;
                    if ($i > 1) {
                        $name .= ' '.$i;
                    }
                }
                // explicit we only need APP USER here, it's only a temp load to check name exists ...
                if ($userFound = User::whereName($name)
                    ->first()
                ) {
                    continue;
                }

                return $name;
            }
        }

        // Not possible, too many users. generate random unique name.
        return $startName.'-'.Str::orderedUuid();
    }

    /**
     * Get the current user as last derived eloquent model instance.
     *
     * @return User|null
     */
    public function currentUser(): ?User
    {
        if (($user = Auth::user()) === null) {
            return null;
        }

        // We want eloquent user only.
        // If other user provider is active, return null.
        if (!$user instanceof AppUser) {
            Log::warning("Current User is not an eloquent user: ".get_class($user));

            return null;
        }

        // We have to reload the user, because Auth::user() could have insufficient
        // attributes like missing "is_enabled"
        return app('user')::with([])
            ->where('id', Auth::id())
            ->first();
    }

    /**
     * @return bool
     */
    public function isLdapEnabled(): bool
    {
        $ldapConnection = config('ldap.default');

        return config('ldap.connections.'.$ldapConnection, false);
    }

    /**
     * @return array
     */
    public function getLdapAllowedGroups(): array
    {
        return array_map('trim', explode(',', config('ldap.users_allowed_from_groups')));
    }

    /**
     * Get the real groups only, not the virtual groups like groups named by usernames.
     *
     * @param  Collection  $ldapGroups
     *
     * @return Collection
     */
    public function toRealLdapGroups(Collection $ldapGroups): Collection
    {
        return $ldapGroups->reject(function ($group) {
            // Holt das Array der ObjectClasses (oder ein leeres Array, falls null)
            $objectClasses = $group->getAttribute('objectclass') ?? [];

            // Prüft, ob IRGENDEINE ObjectClass den Teilstring 'virtual-group' enthält
            return Str::contains(implode(' ', $objectClasses), 'virtual-group');
        });
    }

    /**
     * @param  string  $groupName
     *
     * @return void
     */
    public function saveLdapGroup(string $groupName): void
    {
        AclGroup::firstOrCreate([
            'name'   => $groupName,
            'origin' => 'ldap',
        ], [
            'domain'      => config('ldap.default', 'default'),
            'description' => $groupName." (LDAP)",
        ]);
        logger("Ldap group saved/updated to DB: ".$groupName);
    }

    /**
     * sync ldap groups by
     * 1) adding ldap groups if not already exists
     * 2) keep non ldap (system) groups
     *
     * @param  User   $user
     * @param  array  $userLdapGroupNames
     *
     * @return void
     */
    public function syncLdapGroups(User $user, array $userLdapGroupNames): void
    {
        // keep all NON LDAP groups given by system
        $noLdapGroups = $user->aclGroups()
            ->where('origin', '!=', 'ldap')
            ->pluck('id')
            ->toArray();

        // add all found ldap groups
        $groupIds = AclGroup::whereIn('name', $userLdapGroupNames)
            ->pluck('id')
            ->toArray();

        // merge both to sync them
        $idsToSync = array_unique(array_merge($noLdapGroups, $groupIds));
        $user->aclGroups()
            ->sync($idsToSync);

        logger(sprintf('Sync LDAP groups "%s" to user "%s"', implode(',', $groupIds), $user->name));
    }

    /**
     * @param  Collection  $ldapGroups
     * @param  User|null   $laravelUser  if set, connelt the Groups to this user
     *
     * @return void
     */
    public function saveLdapGroups(Collection $ldapGroups, ?User $laravelUser = null): void
    {
        $ldapGroups = $this->toRealLdapGroups($ldapGroups);
        $ldapGroupNames = $ldapGroups->map(function ($group) {
            return $group->getFirstAttribute('cn');
        })
            ->filter()
            ->toArray();

        // create group if needed
        foreach ($ldapGroupNames as $groupName) {
            $this->saveLdapGroup($groupName);
        }

        if ($laravelUser) {
            $this->syncLdapGroups($laravelUser, $ldapGroupNames);
        }
    }
}