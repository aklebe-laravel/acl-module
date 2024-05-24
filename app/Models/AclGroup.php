<?php

namespace Modules\Acl\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Acl\Models\IdeHelperAclGroup;
use Modules\SystemBase\app\Models\Base\TraitBaseModel;


/**
 * @mixin IdeHelperAclGroup
 */
class AclGroup extends Model
{
    use HasFactory;
    use TraitBaseModel;

    const GROUP_STAFF = 'Staff';
    const GROUP_TRADERS = 'Traders';
    const GROUP_PUPPETS = 'Puppets';
    const GROUP_NON_HUMANS = self::GROUP_PUPPETS;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * Multiple bootable model traits is not working
     * https://github.com/laravel/framework/issues/40645
     *
     * parent::construct() will not (or too early) be called without this construct()
     * so all trait boots also were not called.
     *
     * Important for \Modules\Acl\Models\Base\TraitBaseModel::bootTraitBaseModel
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return BelongsToMany
     */
    public function aclResources()
    {
        return $this->belongsToMany(AclResource::class)->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(static::$userClassName)->withTimestamps();
    }


}
