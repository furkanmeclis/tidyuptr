<?php

namespace Database\Factories;

use App\Models\OrganizationTeacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationTeacherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrganizationTeacher::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'organization_id' => $this->faker->numberBetween(1, 99),
            'teacher_id' => $this->faker->numberBetween(1, 99),
        ];
    }
}
