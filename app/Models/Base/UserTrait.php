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
        $resources = $this->aclResources; // from attribute
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
        return static::whereHas('aclGroups.aclResources', function (Builder $q1) use ($aclResources) {
            $q1->whereIn('code', $aclResources);
        });
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
        return static::whereDoesntHave('aclGroups.aclResources', function (Builder $q1) use ($aclResources) {
            $q1->whereIn('code', $aclResources);
        });
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
