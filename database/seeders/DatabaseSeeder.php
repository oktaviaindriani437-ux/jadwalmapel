<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\ClassModel;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin Skanic',
                'email' => 'admin@skanic.sch.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        $studentUser = User::firstOrCreate(
            ['username' => 'student01'],
            [
                'name' => 'Siswa Skanic',
                'email' => 'student01@skanic.sch.id',
                'password' => Hash::make('student123'),
                'role' => 'siswa',
            ]
        );

        $academicYear = AcademicYear::firstOrCreate(
            ['name' => '2026/2027', 'semester' => 'odd'],
            ['is_active' => true]
        );

        $room1 = Room::firstOrCreate(['name' => 'Ruang 101'], ['capacity' => 36, 'is_available' => true]);
        $room2 = Room::firstOrCreate(['name' => 'Lab Komputer 1'], ['capacity' => 32, 'is_available' => true]);
        $room3 = Room::firstOrCreate(['name' => 'Lab Fisika'], ['capacity' => 30, 'is_available' => true]);

        $class10 = ClassModel::firstOrCreate(
            ['name' => 'X MIPA 1'],
            ['major' => 'MIPA', 'academic_year_id' => $academicYear->id]
        );
        $class10->update(['academic_year_id' => $academicYear->id]);

        $class11 = ClassModel::firstOrCreate(
            ['name' => 'XI IPS 1'],
            ['major' => 'IPS', 'academic_year_id' => $academicYear->id]
        );
        $class11->update(['academic_year_id' => $academicYear->id]);

        $teacher1 = Teacher::firstOrCreate(
            ['nip' => 'T001'],
            ['user_id' => $admin->id, 'name' => 'Ibu Nanda', 'phone' => '081234567890', 'max_hours_per_week' => 30]
        );

        $teacher2 = Teacher::firstOrCreate(
            ['nip' => 'T002'],
            ['user_id' => $admin->id, 'name' => 'Bapak Rizky', 'phone' => '081234567891', 'max_hours_per_week' => 30]
        );

        $teacher3 = Teacher::firstOrCreate(
            ['nip' => 'T003'],
            ['user_id' => $admin->id, 'name' => 'Bapak Hendra', 'phone' => '081234567892', 'max_hours_per_week' => 28]
        );

        $math = Subject::firstOrCreate(
            ['name' => 'Matematika'],
            ['code' => 'MAT-10', 'teacher_id' => $teacher1->id, 'credit_hours' => 3, 'description' => 'Pelajaran hitung']
        );
        if (empty($math->code)) {
            $math->update(['code' => 'MAT-10', 'credit_hours' => 3]);
        }

        $bio = Subject::firstOrCreate(
            ['name' => 'Biologi'],
            ['code' => 'BIO-10', 'teacher_id' => $teacher2->id, 'credit_hours' => 2, 'description' => 'Pelajaran IPA']
        );
        if (empty($bio->code)) {
            $bio->update(['code' => 'BIO-10', 'credit_hours' => 2]);
        }

        $fiz = Subject::firstOrCreate(
            ['name' => 'Fisika'],
            ['code' => 'FIS-10', 'teacher_id' => $teacher3->id, 'credit_hours' => 2, 'description' => 'Pelajaran Fisika']
        );
        if (empty($fiz->code)) {
            $fiz->update(['code' => 'FIS-10', 'credit_hours' => 2]);
        }

        $siswaUser = User::firstOrCreate(
            ['username' => 'siswa'],
            [
                'name' => 'Siswa Skanic',
                'email' => 'siswa@skanic.sch.id',
                'password' => Hash::make('siswa123'),
                'role' => 'siswa',
            ]
        );

        Student::firstOrCreate(
            ['nis' => '2024001'],
            ['user_id' => $studentUser->id, 'class_id' => $class10->id, 'jurusan' => 'MIPA', 'photo' => null]
        );

        Student::firstOrCreate(
            ['nis' => '2024002'],
            ['user_id' => $siswaUser->id, 'class_id' => $class10->id, 'jurusan' => 'MIPA', 'photo' => null]
        );

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        foreach ($days as $index => $day) {
            Schedule::firstOrCreate(
                [
                    'academic_year_id' => $academicYear->id,
                    'day_of_week' => $day,
                    'start_time' => sprintf('%02d:00:00', 7 + $index),
                    'class_id' => $class10->id,
                    'subject_id' => $math->id,
                ],
                [
                    'teacher_id' => $teacher1->id,
                    'end_time' => sprintf('%02d:45:00', 7 + $index),
                    'room_id' => $room1->id,
                    'room' => $room1->name,
                    'day' => $day,
                ]
            );
        }

        Schedule::firstOrCreate(
            [
                'academic_year_id' => $academicYear->id,
                'day_of_week' => 'monday',
                'start_time' => '09:00:00',
                'class_id' => $class10->id,
                'subject_id' => $bio->id,
            ],
            [
                'teacher_id' => $teacher2->id,
                'end_time' => '09:45:00',
                'room_id' => $room3->id,
                'room' => $room3->name,
                'day' => 'monday',
            ]
        );
    }
}
