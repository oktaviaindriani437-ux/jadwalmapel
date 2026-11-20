<?php

namespace Database\Factories;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Teacher>
 */
class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->name(),
            'nip' => 'NIP'.fake()->unique()->numerify('##########'),
            'phone' => fake()->phoneNumber(),
            'max_hours_per_week' => fake()->numberBetween(24, 40),
        ];
    }
}
