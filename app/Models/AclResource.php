<?php

namespace Modules\Acl\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Acl\database\factories\AclResourceFactory;
use Modules\SystemBase\app\Models\Base\TraitBaseModel;

/**
 * @mixin IdeHelperAclResource
 */
class AclResource extends Model
{
    use HasFactory;
    use TraitBaseModel;

    const RES_ADMIN = 'admin';
    const RES_DEVELOPER = 'developer';
    const RES_STAFF = 'staff';
    const RES_SUPPORT = 'support';
    const RES_TRADER = 'trader';
    const RES_MANAGE_CONTENT = 'manage_content';
    const RES_MANAGE_DESIGN = 'manage_design';
    const RES_MANAGE_PRODUCTS = 'manage_products';
    const RES_MANAGE_USERS = 'manage_users';
    const RES_TESTER = 'tester';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * You can use this instead of newFactory()
     * @var string
     */
    public static string $factory = AclResourceFactory::class;

    /**
     * @return BelongsToMany
     */
    public function aclGroups()
    {
        return $this->belongsToMany(AclGroup::class)->withTimestamps();
    }

}
