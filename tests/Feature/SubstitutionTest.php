<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\ClassModel;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\SubstituteLog;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubstitutionTest extends TestCase
{
    use RefreshDatabase;

    protected AcademicYear $academicYear;

    protected ClassModel $class;

    protected Teacher $teacherA;

    protected Teacher $teacherB;

    protected Teacher $teacherC;

    protected Room $room;

    protected Subject $subject;

    protected Schedule $schedule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->academicYear = AcademicYear::factory()->create([
            'name' => '2026/2027',
            'semester' => 'odd',
            'is_active' => true,
        ]);

        $this->class = ClassModel::factory()->create(['academic_year_id' => $this->academicYear->id]);

        $this->teacherA = Teacher::factory()->create(['name' => 'Guru Utama A']);
        $this->teacherB = Teacher::factory()->create(['name' => 'Guru Pengganti B']);
        $this->teacherC = Teacher::factory()->create(['name' => 'Guru Sibuk C']);

        $this->room = Room::factory()->create();
        $this->subject = Subject::factory()->create(['teacher_id' => $this->teacherA->id]);

        // Schedule on Monday 08:00 - 09:30
        $this->schedule = Schedule::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'class_id' => $this->class->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacherA->id,
            'room_id' => $this->room->id,
            'day_of_week' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '09:30:00',
        ]);
    }

    public function test_suggest_available_replacement_teachers(): void
    {
        // Teacher C has a busy schedule on Monday 08:30 - 10:00
        Schedule::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'teacher_id' => $this->teacherC->id,
            'day_of_week' => 'monday',
            'start_time' => '08:30:00',
            'end_time' => '10:00:00',
        ]);

        // Target date is a Monday (e.g. 2026-10-12 is Monday)
        $date = '2026-10-12';

        $response = $this->getJson("/api/v1/substitutions/available-teachers?schedule_id={$this->schedule->id}&date={$date}");

        $response->assertStatus(200)
            ->assertJsonPath('original_teacher_id', $this->teacherA->id);

        $teacherIds = collect($response->json('available_teachers'))->pluck('id')->all();

        // Teacher B is free and should be suggested
        $this->assertContains($this->teacherB->id, $teacherIds);

        // Teacher A (original teacher) and Teacher C (busy teacher) should NOT be suggested
        $this->assertNotContains($this->teacherA->id, $teacherIds);
        $this->assertNotContains($this->teacherC->id, $teacherIds);
    }

    public function test_create_and_approve_substitute_request(): void
    {
        $payload = [
            'schedule_id' => $this->schedule->id,
            'substitute_teacher_id' => $this->teacherB->id,
            'date' => '2026-10-12',
            'notes' => 'Sakit demam',
        ];

        // 1. Submit substitution request
        $createResponse = $this->postJson('/api/v1/substitutions', $payload);
        $createResponse->assertStatus(201)
            ->assertJsonPath('data.status', 'pending')
            ->assertJsonPath('data.substitute_teacher_id', $this->teacherB->id);

        $logId = $createResponse->json('data.id');

        // 2. Approve substitution request
        $approveResponse = $this->patchJson("/api/v1/substitutions/{$logId}/status", [
            'status' => 'approved',
            'notes' => 'Disetujui oleh Kepala Sekolah',
        ]);

        $approveResponse->assertStatus(200)
            ->assertJsonPath('data.status', 'approved');

        $this->assertDatabaseHas('substitute_logs', [
            'id' => $logId,
            'status' => 'approved',
        ]);
    }

    public function test_approved_substitute_overrides_teacher_assignment_on_target_date(): void
    {
        $targetDate = '2026-10-12';

        SubstituteLog::factory()->create([
            'schedule_id' => $this->schedule->id,
            'original_teacher_id' => $this->teacherA->id,
            'substitute_teacher_id' => $this->teacherB->id,
            'date' => $targetDate,
            'status' => 'approved',
        ]);

        // When viewing class timetable for that date
        $response = $this->getJson("/api/v1/schedules/timetable/class/{$this->class->id}?date={$targetDate}");

        $response->assertStatus(200);

        $mondaySchedules = $response->json('timetable.monday');
        $this->assertNotEmpty($mondaySchedules);

        $firstItem = $mondaySchedules[0];
        $this->assertTrue($firstItem['is_substituted']);
        $this->assertEquals($this->teacherB->id, $firstItem['effective_teacher']['id']);
        $this->assertEquals('Guru Pengganti B', $firstItem['effective_teacher']['name']);
    }

    public function test_cannot_assign_original_teacher_as_substitute(): void
    {
        $payload = [
            'schedule_id' => $this->schedule->id,
            'substitute_teacher_id' => $this->teacherA->id,
            'date' => '2026-10-12',
        ];

        $response = $this->postJson('/api/v1/substitutions', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['substitute_teacher_id']);
    }
}
