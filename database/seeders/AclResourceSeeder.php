<?php

namespace Modules\Acl\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Acl\app\Models\AclResource;

class AclResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AclResource::factory()->count(50)->create();
    }
}
