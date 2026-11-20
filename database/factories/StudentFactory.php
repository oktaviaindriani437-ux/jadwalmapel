<?php

namespace Database\Factories;

use App\Models\ClassModel;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'class_id' => ClassModel::factory(),
            'nis' => fake()->unique()->numerify('#####'),
            'jurusan' => 'MIPA',
            'photo' => null,
        ];
    }
}
