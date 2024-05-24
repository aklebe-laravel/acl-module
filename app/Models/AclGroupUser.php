<?php

namespace Modules\Acl\app\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Acl\Models\IdeHelperAclGroupUser;


/**
 * @mixin IdeHelperAclGroupUser
 */
class AclGroupUser extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

}
