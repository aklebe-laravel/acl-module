<?php

namespace Modules\Acl\database\seeders;

use Modules\Acl\app\Models\AclGroup;
use Modules\SystemBase\database\seeders\BaseModelSeeder;

class AclGroupSeeder extends BaseModelSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        parent::run();

        $this->TryCreateFactories(AclGroup::class, config('seeders.acl_groups.count', 10));
    }
}
