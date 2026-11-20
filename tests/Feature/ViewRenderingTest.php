<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\ClassModel;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewRenderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_and_student_views_render_successfully(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $studentUser = User::factory()->create(['role' => 'siswa']);

        $academicYear = AcademicYear::factory()->create(['is_active' => true]);
        $class = ClassModel::factory()->create(['academic_year_id' => $academicYear->id]);
        $room = Room::factory()->create();
        $teacher = Teacher::factory()->create(['user_id' => $admin->id]);
        $subject = Subject::factory()->create(['teacher_id' => $teacher->id]);

        $student = Student::factory()->create([
            'user_id' => $studentUser->id,
            'class_id' => $class->id,
            'nis' => '12345',
        ]);

        Schedule::factory()->create([
            'academic_year_id' => $academicYear->id,
            'class_id' => $class->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'room_id' => $room->id,
            'day_of_week' => 'monday',
        ]);

        // 1. Landing and Login page (Pengenalan Sistem & No Plaintext Passwords & Notifikasi Beranda)
        $this->get('/')->assertStatus(200)->assertSee('Pengenalan Sistem')->assertSee('Notifikasi Beranda')->assertDontSee('admin123');
        $this->get('/login')->assertStatus(200)->assertSee('Jadwal Skanic')->assertSee('Notifikasi Beranda')->assertDontSee('admin123');

        // 2. Admin pages
        $todayDayKey = strtolower(now()->englishDayOfWeek);
        $this->actingAs($admin)->get('/admin/dashboard')
            ->assertStatus(200)
            ->assertSee('Total Jadwal')
            ->assertSee('Jadwal Keseluruhan Pelajaran')
            ->assertSee('Cetak Jadwal (Admin)')
            ->assertSee('PPLG')
            ->assertSee('BCF')
            ->assertSee('TPFL')
            ->assertSee('Teknik Otomotif')
            ->assertSee('Animasi')
            ->assertSee('Jadwal Pelajaran Per Kelas')
            ->assertDontSee('Revisi Jadwal dari Dewan Guru Telah Diterapkan')
            ->assertDontSee('Total Siswa');
        $this->actingAs($admin)->get('/admin/jadwal')
            ->assertStatus(200)
            ->assertSee('Jadwal Mingguan')
            ->assertSee('value="'.$todayDayKey.'" selected', false);
        $this->actingAs($admin)->get('/admin/mata-pelajaran')->assertStatus(200)->assertSee('Daftar Pelajaran');
        $this->actingAs($admin)->get('/admin/guru')->assertStatus(200)->assertSee('Daftar Guru');
        $this->actingAs($admin)->get('/admin/kelas')->assertStatus(200)->assertSee('Daftar Kelas');
        $this->actingAs($admin)->get('/admin/siswa')->assertStatus(200)->assertSee('Daftar Siswa');
        $this->actingAs($admin)->get('/admin/guru-inval')->assertRedirect(route('admin.dashboard'));
        $this->actingAs($admin)->get('/admin/profil')->assertStatus(200)->assertSee('Profil Akun');

        // 3. Student pages (Student Data removed from Dashboard, Revision Notification Removed, No Admin Print Button)
        $this->actingAs($studentUser)->get('/student/dashboard')
            ->assertStatus(200)
            ->assertSee('Pelajaran Hari Ini')
            ->assertDontSee('Revisi Dewan Guru Aktif')
            ->assertDontSee('Cetak Jadwal (Admin)')
            ->assertDontSee('Nomor Induk (NIS)');
        $this->actingAs($studentUser)->get('/student/jadwal')
            ->assertStatus(200)
            ->assertSee('Jadwal Mingguan')
            ->assertDontSee('Revisi Dewan Guru Aktif')
            ->assertDontSee('Cetak Jadwal (Admin)');
        $this->actingAs($studentUser)->get('/student/mata-pelajaran')->assertStatus(200)->assertSee('Daftar Pelajaran');
        $this->actingAs($studentUser)->get('/student/guru')->assertStatus(200)->assertSee('Dewan Guru');
        $this->actingAs($studentUser)->get('/student/profil')->assertStatus(200)->assertSee('Profil Saya');

        // 4. Public viewer has homepage notification, 5 major tabs, pagination bar, and no admin print button
        auth()->logout();
        $this->get('/login')
            ->assertStatus(200)
            ->assertSee('Notifikasi Beranda')
            ->assertSee('portalMajorTabs')
            ->assertSee('portalPaginationBar')
            ->assertSee('pageSizeSelect')
            ->assertSee('PPLG')
            ->assertSee('BCF')
            ->assertSee('TPFL')
            ->assertSee('Teknik Otomotif')
            ->assertSee('Animasi')
            ->assertDontSee('Revisi Dewan Guru Aktif')
            ->assertDontSee('Cetak Jadwal (Admin)');
    }

    public function test_admin_portal_routes_redirect_properly(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Guest access redirects to login with portal parameter
        $this->get('/admin')->assertRedirect(route('login', ['portal' => 'admin']));
        $this->get('/admin/login')->assertRedirect(route('login', ['portal' => 'admin']));

        // Authenticated admin redirects to admin dashboard
        $this->actingAs($admin)->get('/admin')->assertRedirect(route('admin.dashboard'));
        $this->actingAs($admin)->get('/admin/login')->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_cannot_login_without_password(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test', 'password' => bcrypt('secret123')]);
        $response = $this->post('/login', [
            'username' => 'admin_test',
            'password' => '',
        ]);
        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }
}
