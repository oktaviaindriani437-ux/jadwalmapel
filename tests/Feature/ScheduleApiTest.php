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

class ScheduleApiTest extends TestCase
{
    use RefreshDatabase;

    protected AcademicYear $academicYear;

    protected ClassModel $class;

    protected Teacher $teacher;

    protected Room $room;

    protected Subject $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->academicYear = AcademicYear::factory()->create([
            'name' => '2026/2027',
            'semester' => 'odd',
            'is_active' => true,
        ]);

        $this->class = ClassModel::factory()->create([
            'name' => '10-RPL-1',
            'academic_year_id' => $this->academicYear->id,
        ]);

        $this->teacher = Teacher::factory()->create(['name' => 'Bapak Hartono']);
        $this->room = Room::factory()->create(['name' => 'Lab Komputer 1']);
        $this->subject = Subject::factory()->create(['name' => 'Pemrograman Web', 'code' => 'RPL-01', 'teacher_id' => $this->teacher->id]);
    }

    public function test_can_list_schedules_with_filters(): void
    {
        Schedule::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'class_id' => $this->class->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'room_id' => $this->room->id,
            'day_of_week' => 'monday',
            'start_time' => '07:30:00',
            'end_time' => '09:00:00',
        ]);

        // Filter by class_id
        $response = $this->getJson("/api/v1/schedules?class_id={$this->class->id}");
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.class_id', $this->class->id);

        // Filter by teacher_id
        $teacherResponse = $this->getJson("/api/v1/schedules?teacher_id={$this->teacher->id}");
        $teacherResponse->assertStatus(200)
            ->assertJsonCount(1, 'data');

        // Filter by day
        $dayResponse = $this->getJson('/api/v1/schedules?day=monday');
        $dayResponse->assertStatus(200)
            ->assertJsonCount(1, 'data');

        // Filter by different day should be empty
        $tuesdayResponse = $this->getJson('/api/v1/schedules?day=tuesday');
        $tuesdayResponse->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    public function test_can_delete_schedule(): void
    {
        $schedule = Schedule::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'class_id' => $this->class->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
        ]);

        $response = $this->deleteJson("/api/v1/schedules/{$schedule->id}");
        $response->assertStatus(200)
            ->assertJson(['message' => 'Jadwal berhasil dihapus.']);

        $this->assertDatabaseMissing('schedules', ['id' => $schedule->id]);
    }

    public function test_class_weekly_timetable_grid(): void
    {
        Schedule::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'class_id' => $this->class->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'room_id' => $this->room->id,
            'day_of_week' => 'monday',
            'start_time' => '07:00:00',
            'end_time' => '08:30:00',
        ]);

        Schedule::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'class_id' => $this->class->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'room_id' => $this->room->id,
            'day_of_week' => 'wednesday',
            'start_time' => '09:00:00',
            'end_time' => '10:30:00',
        ]);

        $response = $this->getJson("/api/v1/schedules/timetable/class/{$this->class->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'class',
                'academic_year_id',
                'timetable' => [
                    'monday',
                    'tuesday',
                    'wednesday',
                    'thursday',
                    'friday',
                    'saturday',
                ],
            ])
            ->assertJsonCount(1, 'timetable.monday')
            ->assertJsonCount(0, 'timetable.tuesday')
            ->assertJsonCount(1, 'timetable.wednesday');
    }

    public function test_teacher_weekly_timetable(): void
    {
        Schedule::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'class_id' => $this->class->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'room_id' => $this->room->id,
            'day_of_week' => 'thursday',
            'start_time' => '10:00:00',
            'end_time' => '11:30:00',
        ]);

        $response = $this->getJson("/api/v1/schedules/timetable/teacher/{$this->teacher->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'teacher',
                'timetable' => [
                    'monday',
                    'tuesday',
                    'wednesday',
                    'thursday',
                    'friday',
                    'saturday',
                ],
            ])
            ->assertJsonCount(1, 'timetable.thursday');
    }

    public function test_clone_term_copies_schedule_layout(): void
    {
        $newTerm = AcademicYear::factory()->create([
            'name' => '2026/2027',
            'semester' => 'even',
            'is_active' => false,
        ]);

        Schedule::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'class_id' => $this->class->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'room_id' => $this->room->id,
            'day_of_week' => 'monday',
            'start_time' => '07:30:00',
            'end_time' => '09:00:00',
        ]);

        $payload = [
            'from_academic_year_id' => $this->academicYear->id,
            'to_academic_year_id' => $newTerm->id,
        ];

        $response = $this->postJson('/api/v1/schedules/clone-term', $payload);

        $response->assertStatus(200)
            ->assertJsonPath('cloned_count', 1);

        $this->assertDatabaseHas('schedules', [
            'academic_year_id' => $newTerm->id,
            'class_id' => $this->class->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'day_of_week' => 'monday',
        ]);
    }

    public function test_export_excel_csv(): void
    {
        Schedule::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'class_id' => $this->class->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'room_id' => $this->room->id,
        ]);

        $response = $this->get('/api/v1/schedules/export/excel');

        $response->assertStatus(200)
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_export_pdf_view(): void
    {
        Schedule::factory()->create([
            'academic_year_id' => $this->academicYear->id,
            'class_id' => $this->class->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'room_id' => $this->room->id,
        ]);

        $response = $this->get('/api/v1/schedules/export/pdf');

        $response->assertStatus(200)
            ->assertSee('Jadwal Pelajaran Sekolah');

        $adminResponse = $this->get('/api/v1/schedules/export/pdf?mode=admin');
        $adminResponse->assertStatus(200)
            ->assertSee('PEMERINTAH DAERAH PROVINSI')
            ->assertSee('Administrator Kurikulum');
    }
}
