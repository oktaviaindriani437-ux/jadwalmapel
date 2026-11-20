<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\ClassModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClassModel>
 */
class ClassModelFactory extends Factory
{
    protected $model = ClassModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['10', '11', '12']).'-'.fake()->randomElement(['IPA', 'IPS', 'RPL', 'TKJ']).'-'.fake()->randomDigitNotNull(),
            'major' => fake()->randomElement(['IPA', 'IPS', 'RPL', 'TKJ']),
            'academic_year_id' => AcademicYear::factory(),
        ];
    }
}
