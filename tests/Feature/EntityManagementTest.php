<?php

namespace Tests\Feature;

use App\Models\ClassModel;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubstituteLog;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EntityManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('role', 'admin')->first();
    }

    public function test_can_create_and_update_teacher(): void
    {
        // 1. Create Teacher
        $response = $this->actingAs($this->admin)->post('/admin/guru', [
            'name' => 'Dra. Siti Aminah',
            'nip' => '198501012010012001',
            'phone' => '081234567899',
        ]);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('teachers', [
            'name' => 'Dra. Siti Aminah',
            'nip' => '198501012010012001',
        ]);

        $teacher = Teacher::where('nip', '198501012010012001')->first();

        // 2. Update Teacher
        $responseUpdate = $this->actingAs($this->admin)->put("/admin/guru/{$teacher->id}", [
            'name' => 'Dra. Siti Aminah, M.Pd',
            'nip' => '198501012010012001',
            'phone' => '081299998888',
        ]);
        $responseUpdate->assertSessionHas('success');
        $this->assertDatabaseHas('teachers', [
            'id' => $teacher->id,
            'name' => 'Dra. Siti Aminah, M.Pd',
            'phone' => '081299998888',
        ]);
    }

    public function test_can_create_teacher_with_auto_generated_nip(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/guru', [
            'name' => 'Pak Guru Honorer',
            'nip' => '',
            'phone' => '08991234567',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('teachers', [
            'name' => 'Pak Guru Honorer',
        ]);

        $teacher = Teacher::where('name', 'Pak Guru Honorer')->first();
        $this->assertNotNull($teacher->nip);
        $this->assertStringStartsWith('G-', $teacher->nip);
    }

    public function test_cannot_create_teacher_with_duplicate_nip(): void
    {
        Teacher::factory()->create([
            'nip' => 'T-999',
            'name' => 'Guru Pertama',
        ]);

        $response = $this->actingAs($this->admin)->post('/admin/guru', [
            'name' => 'Guru Kedua',
            'nip' => 'T-999',
        ]);

        $response->assertSessionHasErrors(['nip']);
    }

    public function test_can_create_and_update_class(): void
    {
        // 1. Create Class
        $response = $this->actingAs($this->admin)->post('/admin/kelas', [
            'name' => 'X RPL 3',
            'major' => 'Rekayasa Perangkat Lunak',
        ]);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('classes', [
            'name' => 'X RPL 3',
            'major' => 'Rekayasa Perangkat Lunak',
        ]);

        $class = ClassModel::where('name', 'X RPL 3')->first();

        // 2. Update Class
        $responseUpdate = $this->actingAs($this->admin)->put("/admin/kelas/{$class->id}", [
            'name' => 'X PPLG 1',
            'major' => 'Pengembangan Perangkat Lunak & Gim',
        ]);
        $responseUpdate->assertSessionHas('success');
        $this->assertDatabaseHas('classes', [
            'id' => $class->id,
            'name' => 'X PPLG 1',
            'major' => 'Pengembangan Perangkat Lunak & Gim',
        ]);
    }

    public function test_can_create_and_update_student(): void
    {
        $class = ClassModel::first();

        // 1. Create Student
        $response = $this->actingAs($this->admin)->post('/admin/siswa', [
            'name' => 'Ahmad Fikri',
            'username' => 'ahmadfikri',
            'password' => 'fikri123',
            'nis' => '998877',
            'class_id' => $class->id,
            'jurusan' => 'RPL',
        ]);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', ['username' => 'ahmadfikri']);
        $this->assertDatabaseHas('students', ['nis' => '998877']);

        $student = Student::where('nis', '998877')->first();

        // 2. Update Student without changing password
        $responseUpdate = $this->actingAs($this->admin)->put("/admin/siswa/{$student->id}", [
            'name' => 'Ahmad Fikri Pratama',
            'username' => 'ahmadfikri',
            'nis' => '998877',
            'class_id' => $class->id,
            'jurusan' => 'RPL Industri',
        ]);
        $responseUpdate->assertSessionHas('success');
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'jurusan' => 'RPL Industri',
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $student->user_id,
            'name' => 'Ahmad Fikri Pratama',
        ]);
    }

    public function test_admin_can_update_profile(): void
    {
        $response = $this->actingAs($this->admin)->put('/admin/profil', [
            'name' => 'Administrator Baru',
            'email' => 'admin.baru@skanic.sch.id',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
            'name' => 'Administrator Baru',
            'email' => 'admin.baru@skanic.sch.id',
        ]);
        $this->assertTrue(Hash::check('newpassword123', $this->admin->fresh()->password));
    }

    public function test_student_can_update_password(): void
    {
        $studentUser = User::where('role', 'siswa')->first();

        $response = $this->actingAs($studentUser)->put('/student/profil', [
            'password' => 'rahasiasiswa123',
            'password_confirmation' => 'rahasiasiswa123',
        ]);

        $response->assertSessionHas('success');
        $this->assertTrue(Hash::check('rahasiasiswa123', $studentUser->fresh()->password));
    }

    public function test_admin_can_update_substitute(): void
    {
        $teachers = Teacher::all();
        $this->assertGreaterThanOrEqual(2, $teachers->count());
        $schedule = Schedule::first();

        $substitute = SubstituteLog::create([
            'schedule_id' => $schedule->id,
            'original_teacher_id' => $schedule->teacher_id,
            'substitute_teacher_id' => $teachers[1]->id,
            'date' => now()->format('Y-m-d'),
            'status' => 'pending',
            'notes' => 'Catatan awal',
        ]);

        $response = $this->actingAs($this->admin)->put("/admin/guru-inval/{$substitute->id}", [
            'schedule_id' => $schedule->id,
            'substitute_teacher_id' => $teachers[1]->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'status' => 'approved',
            'notes' => 'Catatan diperbarui',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('substitute_logs', [
            'id' => $substitute->id,
            'status' => 'approved',
            'notes' => 'Catatan diperbarui',
        ]);
    }

    public function test_admin_can_create_and_update_schedule_with_typed_room(): void
    {
        $subject = Subject::first();
        $teacher = Teacher::first();
        $class = ClassModel::first();

        // 1. Create with custom typed room
        $response = $this->actingAs($this->admin)->post('/admin/jadwal', [
            'day' => 'saturday',
            'start_time' => '13:00',
            'end_time' => '14:30',
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'class_id' => $class->id,
            'room' => 'Lab Robotika Baru',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('rooms', ['name' => 'Lab Robotika Baru']);
        $this->assertDatabaseHas('schedules', [
            'day_of_week' => 'saturday',
            'room' => 'Lab Robotika Baru',
        ]);

        $schedule = Schedule::where('day_of_week', 'saturday')
            ->where('room', 'Lab Robotika Baru')
            ->first();

        $this->assertNotNull($schedule);

        // 2. Update with another typed room
        $responseUpdate = $this->actingAs($this->admin)->put("/admin/jadwal/{$schedule->id}", [
            'day' => 'saturday',
            'start_time' => '13:00',
            'end_time' => '14:30',
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'class_id' => $class->id,
            'room' => 'Bengkel Perakitan Komputer',
        ]);

        $responseUpdate->assertSessionHas('success');
        $this->assertDatabaseHas('rooms', ['name' => 'Bengkel Perakitan Komputer']);
        $this->assertDatabaseHas('schedules', [
            'id' => $schedule->id,
            'room' => 'Bengkel Perakitan Komputer',
        ]);
    }
}
