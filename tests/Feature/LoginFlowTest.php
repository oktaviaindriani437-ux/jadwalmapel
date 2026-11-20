<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_login_as_admin_with_admin123(): void
    {
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'admin123',
        ]);
        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
        $this->assertEquals('admin', auth()->user()->role);
    }

    public function test_login_as_admin_with_admin_password(): void
    {
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'admin',
        ]);
        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_login_as_admin_with_whitespace_and_uppercase(): void
    {
        $response = $this->post('/login', [
            'username' => '  Admin  ',
            'password' => 'admin123',
        ]);
        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_login_as_siswa(): void
    {
        $response = $this->post('/login', [
            'username' => 'siswa',
            'password' => 'siswa123',
        ]);
        $response->assertRedirect(route('student.dashboard'));
        $this->assertAuthenticated();
        $this->assertEquals('siswa', auth()->user()->role);
    }

    public function test_login_as_siswa_with_siswa_password(): void
    {
        $response = $this->post('/login', [
            'username' => 'siswa',
            'password' => 'siswa',
        ]);
        $response->assertRedirect(route('student.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_login_as_student01(): void
    {
        $response = $this->post('/login', [
            'username' => 'student01',
            'password' => 'student123',
        ]);
        $response->assertRedirect(route('student.dashboard'));
        $this->assertAuthenticated();
        $this->assertEquals('siswa', auth()->user()->role);
    }

    public function test_quick_login_as_admin(): void
    {
        $response = $this->get('/login/quick/admin');
        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
        $this->assertEquals('admin', auth()->user()->role);
    }

    public function test_quick_login_as_siswa(): void
    {
        $response = $this->get('/login/quick/siswa');
        $response->assertRedirect(route('student.dashboard'));
        $this->assertAuthenticated();
        $this->assertEquals('siswa', auth()->user()->role);
    }

    public function test_login_using_student_nis(): void
    {
        $response = $this->post('/login', [
            'username' => '2024001',
            'password' => 'student123',
        ]);
        $response->assertRedirect(route('student.dashboard'));
        $this->assertAuthenticated();
    }
}
