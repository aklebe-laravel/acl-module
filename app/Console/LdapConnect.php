<?php

namespace Modules\Acl\app\Console;

use Illuminate\Support\Facades\Auth;
use Modules\Acl\app\Ldap\LdapCustomUser;
use Modules\DeployEnv\app\Console\Base\DeployEnvBase;
use Symfony\Component\Console\Command\Command as CommandResult;

class LdapConnect extends DeployEnvBase
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ldap:connect
                        {cmd : "login" > auth login, "get" > show user attributes from server}
                        {user : username or id}
                        {password? : user password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '...';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $cmd = $this->argument('cmd');
        $username = $this->argument('user');
        $password = $this->argument('password');

        switch ($cmd) {
            case 'login':
                $credentials = [config('ldap.attributes.guid_auth_name') => $username, 'password' => $password];
                $attempt = Auth::attempt($credentials);
                dump($attempt);
                break;

            case 'get':
                $ldapUser = LdapCustomUser::where('cn', '=', $username)
                    ->first();
                dump($ldapUser->getAttributes());
                break;

            case 'groups':
                $userService = app('acl_user_service');

                /** @var LdapCustomUser $ldapUser */
                $ldapUser = LdapCustomUser::where('cn', '=', $username)
                    ->first();

                $userService->saveLdapGroups($ldapUser->groups()->get());
                break;

            default:
                break;
        }

        return CommandResult::SUCCESS;
    }

}
