<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();
        \App\Models\Organization::factory(100)->create();
        \App\Models\Teacher::factory(100)->create();
        \App\Models\OrganizationTeacher::factory(100)->create();
        \App\Models\Lesson::factory(10)->create();
        \App\Models\Topic::factory(300)->create();
        \App\Models\Student::factory(100)->create();
    }
}
