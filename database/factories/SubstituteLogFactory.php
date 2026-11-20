<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\SubstituteLog;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SubstituteLog>
 */
class SubstituteLogFactory extends Factory
{
    protected $model = SubstituteLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'schedule_id' => Schedule::factory(),
            'original_teacher_id' => Teacher::factory(),
            'substitute_teacher_id' => Teacher::factory(),
            'date' => fake()->date(),
            'status' => 'pending',
            'notes' => fake()->sentence(),
        ];
    }
}
