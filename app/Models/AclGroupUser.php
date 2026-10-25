<?php

namespace Modules\Acl\app\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperAclGroupUser
 */
class AclGroupUser extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var string
     */
    protected $table = 'acl_group_user';

//    /**
//     * You can use this instead of newFactory()
//     * @var string
//     */
//    public static string $factory = AclGroupUserFactory::class;

}
