<?php

namespace Modules\Acl\database\seeders;

use Modules\Acl\app\Models\AclResource;
use Modules\SystemBase\database\seeders\BaseModelSeeder;

class AclResourceSeeder extends BaseModelSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        parent::run();

        $this->TryCreateFactories(AclResource::class, config('seeders.acl_resources.count', 10));
    }
}
