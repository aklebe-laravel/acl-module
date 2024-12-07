<?php

namespace Modules\Acl\app\Models\Base;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Acl\app\Models\AclGroup;
use Modules\Acl\app\Models\AclResource;

trait UserTrait
{
    /**
     * @return BelongsToMany
     */
    public function aclGroups(): BelongsToMany
    {
        return $this->belongsToMany(AclGroup::class)->withTimestamps()->withPivot(['created_at', 'updated_at']);
    }

    /**
     * Check user has any of the acl resources.
     *
     * @param  mixed  $codes
     * @param  array  $orCodes
     *
     * @return bool
     * @todo: caching
     */
    public function hasAclResource(mixed $codes, array $orCodes = [AclResource::RES_ADMIN]): bool
    {
        if (!is_array($codes)) {
            $codes = [$codes];
        }
        $resources = $this->aclResources;
        if ($orCodes && ($resources->whereIn('code', $orCodes)->count() > 0)) {
            return true;
        }

        return ($resources->whereIn('code', $codes)->count() > 0);
    }

    /**
     * @param  array  $aclResources
     *
     * @return Builder
     * @todo: Try make scope instead of create new instance
     *
     */
    public static function withAclResources(array $aclResources): Builder
    {
        $builder = app(static::class)->query();
        $builder->distinct();
        $builder->select('users.*');
        $builder->join('acl_resources', function ($join) use ($aclResources) {
            $join->whereIn('code', $aclResources);
        });
        $builder->join('acl_group_acl_resource', 'acl_group_acl_resource.acl_resource_id', '=', 'acl_resources.id');
        $builder->join('acl_groups', 'acl_groups.id', '=', 'acl_group_acl_resource.acl_group_id');
        //            $builder->join('acl_group_user', 'acl_group_user.acl_group_id', '=', 'acl_group_acl_resource.acl_group_id');
        $builder->join('acl_group_user', function ($join) {
            $join->on('acl_group_user.acl_group_id', '=', 'acl_group_acl_resource.acl_group_id')
                 ->on("acl_group_user.user_id", "=", "users.id");
        });

        //        $builder->groupBy('users.id');

        return $builder;
    }

    /**
     * @param  array  $aclResources
     *
     * @return Builder
     * @todo: Try make scope instead of create new instance
     *
     */
    public static function withNoAclResources(array $aclResources): Builder
    {
        $result = app(static::class)->withAclResources($aclResources)->pluck('id');
        $builder = app(static::class)->query();
        $builder->distinct();
        $builder->whereNotIn('id', $result);

        return $builder;
    }

    /**
     * Attribute
     *
     * @return Attribute
     */
    protected function aclResources(): Attribute
    {
        return Attribute::make(get: function () {

            return AclResource::with([])->whereHas('aclGroups.users', function ($query) {
                return $query->where('id', '=', $this->getKey());
            })->get();

        });
    }

}
