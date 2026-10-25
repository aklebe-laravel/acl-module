<?php

namespace Modules\Acl\app\Ldap\Rules;

use Illuminate\Database\Eloquent\Model as Eloquent;
use LdapRecord\Laravel\Auth\Rule;
use LdapRecord\Models\Model as LdapRecord;
use Modules\Acl\app\Models\User;

class OnlyAppUsersRule implements Rule
{
    /**
     * @param  LdapRecord     $user
     * @param  Eloquent|null  $model
     *
     * @return bool
     */
    public function passes(LdapRecord $user, ?Eloquent $model = null): bool
    {
        $allowedGroups = app('acl_user_service')->getLdapAllowedGroups();
        /** @var User $model */
        $memberOf = $user->getAttribute('memberof') ?? [];
        $groupFound = false;
        foreach ($memberOf as $groupDn) {
            // check the allowed groups
            if (preg_match('/(?:^|,)(?i:cn)=(.*?)(?:,|$)/', $groupDn, $matches)) {
                if (in_array($matches[1], $allowedGroups)) {
                    $groupFound = true;
                    break;
                }
            }
        }

        if (!$groupFound) {
            return false; // deny - no allowed group found
        }

        // check user is active ('ak-active')
        $isActive = $user->getFirstAttribute('ak-active');
        $model->is_enabled = ($isActive === 'TRUE');
        if (!$model->is_enabled) {

            return false; // deny
        }

        return true;
    }
}
