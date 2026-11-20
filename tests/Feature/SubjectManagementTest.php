<?php

namespace Tests\Feature;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('username', 'admin')->first();
    }

    public function test_can_create_subject_with_typed_teacher_name(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/mata-pelajaran', [
            'name' => 'Bahasa Jepang',
            'teacher_name' => 'Sensei Kenji',
            'description' => 'Mata pelajaran bahasa asing',
            'code' => 'JPN-10',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('subjects', [
            'name' => 'Bahasa Jepang',
            'code' => 'JPN-10',
        ]);
        $this->assertDatabaseHas('teachers', [
            'name' => 'Sensei Kenji',
        ]);
    }

    public function test_can_update_existing_subject_and_change_teacher(): void
    {
        $subject = Subject::first();
        $this->assertNotNull($subject);

        $response = $this->actingAs($this->admin)->put("/admin/mata-pelajaran/{$subject->id}", [
            'name' => $subject->name.' Terapan',
            'teacher_name' => 'Ibu Rahmawati',
            'description' => 'Silabus revisi',
            'code' => 'REV-01',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('subjects', [
            'id' => $subject->id,
            'name' => $subject->name.' Terapan',
            'code' => 'REV-01',
        ]);
        $this->assertDatabaseHas('teachers', [
            'name' => 'Ibu Rahmawati',
        ]);
    }

    public function test_can_delete_subject(): void
    {
        $subject = Subject::factory()->create(['name' => 'Pelajaran Untuk Dihapus']);

        $response = $this->actingAs($this->admin)->delete("/admin/mata-pelajaran/{$subject->id}");

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('subjects', [
            'id' => $subject->id,
        ]);
    }

    public function test_can_create_subject_with_dash_or_empty_code_without_error(): void
    {
        $response1 = $this->actingAs($this->admin)->post('/admin/mata-pelajaran', [
            'name' => 'Kewirausahaan A',
            'code' => '-',
            'description' => 'Materi KIK',
        ]);
        $response1->assertSessionHas('success');

        $response2 = $this->actingAs($this->admin)->post('/admin/mata-pelajaran', [
            'name' => 'Kewirausahaan B',
            'code' => '-',
            'description' => 'Materi KIK Lanjutan',
        ]);
        $response2->assertSessionHas('success');

        $s1 = Subject::where('name', 'Kewirausahaan A')->first();
        $s2 = Subject::where('name', 'Kewirausahaan B')->first();

        $this->assertNotNull($s1);
        $this->assertNotNull($s2);
        $this->assertNotEquals('-', $s1->code);
        $this->assertNotEquals('-', $s2->code);
        $this->assertNotEquals($s1->code, $s2->code);
    }

    public function test_fails_validation_when_duplicate_custom_code_provided(): void
    {
        Subject::factory()->create([
            'name' => 'Mata Pelajaran Lama',
            'code' => 'KIK-99',
        ]);

        $response = $this->actingAs($this->admin)->post('/admin/mata-pelajaran', [
            'name' => 'Mata Pelajaran Baru',
            'code' => 'KIK-99',
        ]);

        $response->assertSessionHasErrors(['code']);
    }
}
