<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_access_admin_dashboard_and_not_student(): void
    {
        $admin = User::where('username', 'admin')->first();
        $this->assertNotNull($admin);
        $this->assertEquals('admin', $admin->role);

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);

        $responseStudent = $this->actingAs($admin)->get('/student/dashboard');
        $responseStudent->assertStatus(403);
    }

    public function test_siswa_can_access_student_dashboard_and_not_admin(): void
    {
        $siswa = User::where('username', 'student01')->first();
        $this->assertNotNull($siswa);
        $this->assertEquals('siswa', $siswa->role);

        $response = $this->actingAs($siswa)->get('/student/dashboard');
        $response->assertStatus(200);

        $responseAdmin = $this->actingAs($siswa)->get('/admin/dashboard');
        $responseAdmin->assertStatus(403);
    }
}
