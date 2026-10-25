<?php

namespace Modules\Acl\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Acl\app\Models\AclGroup;

/**
 * @extends Factory<AclGroup>
 */
class AclGroupFactory extends Factory
{
    protected $model = AclGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'        => 'ACL Group '.fake()
                    ->unique()
                    ->words(3, true),
            'description' => implode(' ', fake()->words(20)),
        ];
    }
}
