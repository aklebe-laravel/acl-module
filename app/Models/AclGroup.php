<?php

namespace Modules\Acl\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Acl\database\factories\AclGroupFactory;
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
     * You can use this instead of newFactory()
     * @var string
     */
    public static string $factory = AclGroupFactory::class;

    /**
     * Multiple bootable model traits is not working
     * https://github.com/laravel/framework/issues/40645
     *
     * parent::construct() will not (or too early) be called without this construct()
     * so all trait boots also were not called.
     *
     * Important for \Modules\Acl\Models\Base\TraitBaseModel::bootTraitBaseModel
     */
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
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
