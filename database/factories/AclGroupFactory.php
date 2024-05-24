<?php

namespace Modules\Acl\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Acl\app\Models\AclGroup>
 */
class AclGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //            'code'             => 'acl_group_' . fake()->word(),
            'name'        => 'ACL Group '.fake()->unique()->word(),
            'description' => implode(' ', fake()->words(20)),
        ];
    }
}
