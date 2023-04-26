<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrganizationFactory extends Factory
{
    protected $model = Organization::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'organization_id' => Str::random(4),
            'active' => true,
            'remember_token' => Str::random(10),
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address
        ];
    }
}
