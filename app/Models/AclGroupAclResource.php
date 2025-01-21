<?php

namespace Modules\Acl\app\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperAclGroupAclResource
 */
class AclGroupAclResource extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var string
     */
    protected $table = 'acl_group_acl_resource';

    //    /**
//     * You can use this instead of newFactory()
//     * @var string
//     */
//    public static string $factory = AclGroupUserFactory::class;

}
