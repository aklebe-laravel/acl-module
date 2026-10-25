<?php

namespace Modules\Acl\app\Ldap;

use LdapRecord\Models\Model;

class LdapCustomGroup extends Model
{
    /**
     * Override dynamic statics in boot
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        static::$objectClasses = explode(',', config('ldap.attributes.object_class_groups', 'posixGroup'));
    }
}