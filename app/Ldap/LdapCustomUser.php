<?php

namespace Modules\Acl\app\Ldap;

use LdapRecord\Models\Model;
use LdapRecord\Models\Relations\HasMany;

class LdapCustomUser extends Model
{
    /**
     * Override dynamic properties
     *
     * @param  array  $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->guidKey = config('ldap.attributes.guid_auth_name', 'entryuuid');

        parent::__construct($attributes);
    }

    /**
     * Override dynamic statics in boot
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        static::$objectClasses = explode(',', config('ldap.attributes.object_class_users', 'posixAccount'));
    }

    /**
     * Group-User-Relation
     */
    public function groups(): HasMany
    {
        return $this->hasMany(LdapCustomGroup::class, 'member');
    }
}