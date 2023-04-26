<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Student;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'organization_id' => $this->faker->numberBetween(1, 10),
            'address' => $this->faker->address,
            'password' => bcrypt('password'),
            'email' => $this->faker->unique()->safeEmail,
            'remember_token' => \Illuminate\Support\Str::random(10),
        ];
    }
}
