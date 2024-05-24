<?php

namespace Modules\Acl\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Acl\app\Models\AclResource>
 */
class AclResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code'        => 'acl_res_'.fake()->word(),
            'name'        => 'ACL Resource '.fake()->word(),
            'description' => implode(' ', fake()->words(20)),
        ];
    }
}
