<?php

namespace Modules\Acl\app\Listeners;

use Illuminate\Support\Str;
use LdapRecord\Laravel\Events\Import\Synchronized;

class AssignFallbackEmail
{
    public function handle(Synchronized $event): void
    {
        $eloquentUser = $event->eloquent;
        $ldapUser = $event->object;

        // Is email empty?
        if (empty($ldapUser->getFirstAttribute('mail'))) {
            // Calc the identifier
            $username = $ldapUser->getFirstAttribute('uid') ?? $ldapUser->getFirstAttribute('cn') ?? Str::random(8);

            // Generate a unique Fake-E-Mail
            $eloquentUser->email = Str::lower($username).'@fake.local';
        }
    }
}