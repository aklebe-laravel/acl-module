<?php

namespace Modules\Acl\app\Listeners;

use LdapRecord\Laravel\Events\Import\Saved;
use Modules\Acl\app\Ldap\LdapCustomUser;
use Modules\Acl\app\Models\User;

class SyncLdapGroups
{
    public function handle(Saved $event): void
    {
        /** @var User $laravelUser */
        $laravelUser = $event->eloquent;
        /** @var LdapCustomUser $ldapUser */
        $ldapUser = $event->object;

        $userService = app('acl_user_service');

        // Save groups and link them to user
        $userService->saveLdapGroups($ldapUser->groups()->get(), $laravelUser);
    }
}