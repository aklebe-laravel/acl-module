<?php

namespace Modules\Acl\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Acl\app\Models\AclGroup;

class AclGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AclGroup::factory()->count(50)->create();
    }
}
