<?php

namespace Modules\Acl\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    const string RES_ADMIN = 'admin';
    const string RES_DEVELOPER = 'developer';
    const string RES_STAFF = 'staff';
    const string RES_SUPPORT = 'support';
    const string RES_TRADER = 'trader';
    const string RES_PUPPET = 'puppet';
    const string RES_NON_HUMAN = self::RES_PUPPET;
    const string RES_MANAGE_CONTENT = 'manage_content';
    const string RES_MANAGE_DESIGN = 'manage_design';
    const string RES_MANAGE_PRODUCTS = 'manage_products';
    const string RES_MANAGE_USERS = 'manage_users';
    const string RES_TESTER = 'tester';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * You can use this instead of newFactory()
     *
     * @var string
     */
    public static string $factory = AclResourceFactory::class;

    /**
     * @return BelongsToMany
     */
    public function aclGroups(): BelongsToMany
    {
        return $this->belongsToMany(AclGroup::class)->withTimestamps();
    }

    /**
     * Attribute
     *
     * @return Attribute
     */
    protected function users(): Attribute
    {
        return Attribute::make(get: function () {
            return app(User::class)->withAclResources([$this->code]);
        });
    }

}

