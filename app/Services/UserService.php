<?php

namespace Modules\Acl\app\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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
     * @param  string  $aclResourceId
     * @param  bool  $validUsersOnly
     * @return Builder
     */
    public static function getUserBuilderByAclResourceId(string $aclResourceId, bool $validUsersOnly = false): Builder
    {
        $builder = $validUsersOnly ? User::getBuilderFrontendItems() : User::query();
        return $builder->with(['aclGroups.aclResources'])
            ->whereHas('aclGroups.aclResources', function ($query) use ($aclResourceId) {
                return $query->where('id', '=', $aclResourceId);
            });
    }

    /**
     * @param  string  $aclGroupId
     * @param  bool  $validUsersOnly
     * @return Builder
     */
    public static function getUserBuilderByAclGroupId(string $aclGroupId, bool $validUsersOnly = false): Builder
    {
        $builder = $validUsersOnly ? app(User::class)->getBuilderFrontendItems() : app(User::class)->query();
        return $builder->with(['aclGroups'])->whereHas('aclGroups', function ($query) use ($aclGroupId) {
            return $query->where('id', '=', $aclGroupId);
        });
    }

    /**
     * Users by acl resources OR user id.
     * Every user is returned once!
     *
     * @param  array  $aclResourcesIdList
     * @param  array  $userIdList
     * @param  bool  $validUsersOnly
     * @return Builder
     */
    public static function getUserBuilderByAclResourcesOrUserIds(array $aclResourcesIdList, array $userIdList,
        bool $validUsersOnly = false): Builder
    {
        $builder = $validUsersOnly ? app(User::class)->getBuilderFrontendItems() : app(User::class)->query();
        return $builder->with(['aclGroups.aclResources'])->where(function (Builder $b1) use (
            $aclResourcesIdList, $userIdList
        ) {
            $b1->whereHas('aclGroups.aclResources', function ($query) use ($aclResourcesIdList, $userIdList) {
                return $query->whereIn('id', $aclResourcesIdList);
            })->orWhereIn('id', $userIdList);
        });
    }

    public function getUserBuilderByAclResource(string $aclResourcePath, bool $validUsersOnly = false): Builder
    {
        $builder = $validUsersOnly ? app(User::class)->getBuilderFrontendItems() : app(User::class)->query();
        return $builder->with(['aclGroups.aclResources'])
            ->whereHas('aclGroups.aclResources', function ($query) use ($aclResourcePath) {
                return $query->where('code', '=', $aclResourcePath);
            });
    }

    public function getUserBuilderByAclResources(array $aclResourcesIdList, bool $validUsersOnly = false): Builder
    {
        $builder = $validUsersOnly ? app(User::class)->getBuilderFrontendItems() : app(User::class)->query();
        return $builder->with(['aclGroups.aclResources'])
            ->whereHas('aclGroups.aclResources', function ($query) use ($aclResourcesIdList) {
                return $query->whereIn('id', $aclResourcesIdList);
            });
    }

    /**
     * @param  User|null  $user
     * @param  mixed  $resource
     *
     * @return bool
     */
    public function hasUserResource(?User $user, mixed $resource): bool
    {
        return ($user && $user->hasAclResource($resource));
    }

    /**
     * @return void
     */
    public function destroyUserSession(): void
    {
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
    }

    /**
     * If trying to assign extern username, but it's already exist.
     *
     * @param  string  $startName
     * @return string
     */
    public function getNextAvailableUserName(string $startName): string
    {
        if (!($userFound = User::whereName($startName)->first())) {
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
                if ($userFound = User::whereName($name)->first()) {
                    continue;
                }

                return $name;
            }
        }

        // Not possible, too many users. generate random unique name.
        return $startName.'-'.Str::orderedUuid();
    }
}