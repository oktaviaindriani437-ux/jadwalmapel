<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\ClassModel;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Schedule>
 */
class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startHour = fake()->numberBetween(7, 14);
        $startTime = sprintf('%02d:00:00', $startHour);
        $endTime = sprintf('%02d:45:00', $startHour + 1);

        return [
            'academic_year_id' => AcademicYear::factory(),
            'class_id' => ClassModel::factory(),
            'subject_id' => Subject::factory(),
            'teacher_id' => Teacher::factory(),
            'room_id' => Room::factory(),
            'day_of_week' => fake()->randomElement(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'room' => 'Lab-1',
            'day' => 'monday',
        ];
    }
}
