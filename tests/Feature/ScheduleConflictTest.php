<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\ClassModel;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduleConflictTest extends TestCase
{
    use RefreshDatabase;

    protected AcademicYear $academicYear;

    protected ClassModel $classA;

    protected ClassModel $classB;

    protected Teacher $teacherA;

    protected Teacher $teacherB;

    protected Room $roomA;

    protected Room $roomB;

    protected Subject $subjectA;

    protected Subject $subjectB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->academicYear = AcademicYear::factory()->create([
            'name' => '2026/2027',
            'semester' => 'odd',
            'is_active' => true,
        ]);

        $this->classA = ClassModel::factory()->create(['name' => '10-IPA-1', 'academic_year_id' => $this->academicYear->id]);
        $this->classB = ClassModel::factory()->create(['name' => '10-IPA-2', 'academic_year_id' => $this->academicYear->id]);

        $this->teacherA = Teacher::factory()->create(['name' => 'Pak Budi']);
        $this->teacherB = Teacher::factory()->create(['name' => 'Bu Siti']);

        $this->roomA = Room::factory()->create(['name' => 'Lab Fisika']);
        $this->roomB = Room::factory()->create(['name' => 'Ruang 101']);

        $this->subjectA = Subject::factory()->create(['name' => 'Fisika', 'code' => 'FIS-01', 'teacher_id' => $this->teacherA->id]);
        $this->subjectB = Subject::factory()->create(['name' => 'Biologi', 'code' => 'BIO-01', 'teacher_id' => $this->teacherB->id]);
    }

    public function test_successful_schedule_creation(): void
    {
        $payload = [
            'academic_year_id' => $this->academicYear->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'teacher_id' => $this->teacherA->id,
            'room_id' => $this->roomA->id,
            'day_of_week' => 'monday',
            'start_time' => '08:00',
            'end_time' => '09:30',
        ];

        $response = $this->postJson('/api/v1/schedules', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.class_id', $this->classA->id)
            ->assertJsonPath('data.teacher_id', $this->teacherA->id)
            ->assertJsonPath('data.room_id', $this->roomA->id);

        $this->assertDatabaseHas('schedules', [
            'academic_year_id' => $this->academicYear->id,
            'class_id' => $this->classA->id,
            'teacher_id' => $this->teacherA->id,
            'day_of_week' => 'monday',
            'start_time' => '08:00',
            'end_time' => '09:30',
        ]);
    }

    public function test_rejection_due_to_teacher_conflict(): void
    {
        // Existing schedule for Teacher A on Monday 08:00 - 09:30 in Class A
        Schedule::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'teacher_id' => $this->teacherA->id,
            'room_id' => $this->roomA->id,
            'day_of_week' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '09:30:00',
        ]);

        // Attempt to assign Teacher A to Class B during overlapping hours (09:00 - 10:00)
        $payload = [
            'academic_year_id' => $this->academicYear->id,
            'class_id' => $this->classB->id,
            'subject_id' => $this->subjectB->id,
            'teacher_id' => $this->teacherA->id,
            'room_id' => $this->roomB->id,
            'day_of_week' => 'monday',
            'start_time' => '09:00',
            'end_time' => '10:00',
        ];

        $response = $this->postJson('/api/v1/schedules', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['teacher_id']);

        $errorMsg = $response->json('errors.teacher_id.0');
        $this->assertStringContainsString('Konflik Guru', $errorMsg);
        $this->assertStringContainsString('Pak Budi', $errorMsg);
    }

    public function test_rejection_due_to_class_conflict(): void
    {
        // Class A already has Physics on Monday 08:00 - 09:30
        Schedule::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'teacher_id' => $this->teacherA->id,
            'room_id' => $this->roomA->id,
            'day_of_week' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '09:30:00',
        ]);

        // Attempt to schedule Biology for Class A on Monday 08:30 - 09:45
        $payload = [
            'academic_year_id' => $this->academicYear->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectB->id,
            'teacher_id' => $this->teacherB->id,
            'room_id' => $this->roomB->id,
            'day_of_week' => 'monday',
            'start_time' => '08:30',
            'end_time' => '09:45',
        ];

        $response = $this->postJson('/api/v1/schedules', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['class_id']);

        $errorMsg = $response->json('errors.class_id.0');
        $this->assertStringContainsString('Konflik Kelas', $errorMsg);
        $this->assertStringContainsString('10-IPA-1', $errorMsg);
    }

    public function test_rejection_due_to_room_conflict(): void
    {
        // Room A is booked by Class A on Monday 08:00 - 09:30
        Schedule::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'teacher_id' => $this->teacherA->id,
            'room_id' => $this->roomA->id,
            'day_of_week' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '09:30:00',
        ]);

        // Attempt to book Room A for Class B with Teacher B on Monday 08:00 - 09:30
        $payload = [
            'academic_year_id' => $this->academicYear->id,
            'class_id' => $this->classB->id,
            'subject_id' => $this->subjectB->id,
            'teacher_id' => $this->teacherB->id,
            'room_id' => $this->roomA->id,
            'day_of_week' => 'monday',
            'start_time' => '08:00',
            'end_time' => '09:30',
        ];

        $response = $this->postJson('/api/v1/schedules', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['room_id']);

        $errorMsg = $response->json('errors.room_id.0');
        $this->assertStringContainsString('Konflik Ruangan', $errorMsg);
        $this->assertStringContainsString('Lab Fisika', $errorMsg);
    }

    public function test_back_to_back_classes_do_not_conflict(): void
    {
        // First class ends exactly at 09:30
        Schedule::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'teacher_id' => $this->teacherA->id,
            'room_id' => $this->roomA->id,
            'day_of_week' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '09:30:00',
        ]);

        // Second class starts exactly at 09:30 for same teacher and same room
        $payload = [
            'academic_year_id' => $this->academicYear->id,
            'class_id' => $this->classB->id,
            'subject_id' => $this->subjectA->id,
            'teacher_id' => $this->teacherA->id,
            'room_id' => $this->roomA->id,
            'day_of_week' => 'monday',
            'start_time' => '09:30',
            'end_time' => '11:00',
        ];

        $response = $this->postJson('/api/v1/schedules', $payload);

        $response->assertStatus(201);
    }

    public function test_updating_schedule_does_not_conflict_with_itself(): void
    {
        $schedule = Schedule::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'class_id' => $this->classA->id,
            'subject_id' => $this->subjectA->id,
            'teacher_id' => $this->teacherA->id,
            'room_id' => $this->roomA->id,
            'day_of_week' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '09:30:00',
        ]);

        // Update room to Room B while keeping same time and teacher
        $updatePayload = [
            'room_id' => $this->roomB->id,
            'start_time' => '08:00',
            'end_time' => '09:30',
        ];

        $response = $this->putJson("/api/v1/schedules/{$schedule->id}", $updatePayload);

        $response->assertStatus(200)
            ->assertJsonPath('data.room_id', $this->roomB->id);
    }
}
