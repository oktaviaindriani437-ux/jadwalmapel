<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'schedules' => Schedule::count(),
            'teachers' => Teacher::count(),
            'classes' => ClassModel::count(),
            'subjects' => Subject::count(),
            'students' => Student::count(),
        ];

        $today = strtolower(now()->englishDayOfWeek);
        $todayDayKey = $today;

        $classes = ClassModel::orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $rooms = \App\Models\Room::orderBy('name')->get();
        $majors = ClassModel::MAJORS;

        $majorsList = [
            'PPLG' => [
                'number' => 1,
                'name' => 'Pengembangan Perangkat Lunak dan Gim',
                'short' => 'PPLG',
                'badge' => 'major-pplg',
            ],
            'BCF' => [
                'number' => 2,
                'name' => 'Broadcasting dan Perfilman',
                'short' => 'BCF',
                'badge' => 'major-bcf',
            ],
            'TPFL' => [
                'number' => 3,
                'name' => 'Teknik Pengelasan dan Fabrikasi Logam',
                'short' => 'TPFL',
                'badge' => 'major-tpfl',
            ],
            'TO' => [
                'number' => 4,
                'name' => 'Teknik Otomotif',
                'short' => 'Teknik Otomotif',
                'badge' => 'major-to',
            ],
            'Animasi' => [
                'number' => 5,
                'name' => 'Animasi',
                'short' => 'Animasi',
                'badge' => 'major-animasi',
            ],
        ];

        // Attach major_key to each class and sort strictly by the 5 majors: 1. PPLG, 2. BCF, 3. TPFL, 4. TO, 5. Animasi
        $majorOrder = ['PPLG' => 1, 'BCF' => 2, 'TPFL' => 3, 'TO' => 4, 'Animasi' => 5];

        $classesWithMajor = $classes->map(function ($cls) {
            $cName = strtoupper($cls->name ?? '');
            $cMajor = strtoupper($cls->major ?? '');
            if (str_contains($cName, 'PPLG') || str_contains($cMajor, 'PPLG')) {
                $majorKey = 'PPLG';
            } elseif (str_contains($cName, 'BCF') || str_contains($cMajor, 'BCF')) {
                $majorKey = 'BCF';
            } elseif (str_contains($cName, 'TPFL') || str_contains($cMajor, 'TPFL')) {
                $majorKey = 'TPFL';
            } elseif (str_contains($cName, 'TO') || str_contains($cMajor, 'OTOMOTIF') || str_contains($cMajor, 'TO')) {
                $majorKey = 'TO';
            } elseif (str_contains($cName, 'ANIMASI') || str_contains($cMajor, 'ANIMASI')) {
                $majorKey = 'Animasi';
            } else {
                $majorKey = 'Umum';
            }
            $cls->major_key = $majorKey;

            return $cls;
        })->sortBy(function ($cls) use ($majorOrder) {
            $majorRank = $majorOrder[$cls->major_key] ?? 99;
            $gradeRank = str_starts_with(strtoupper(trim($cls->name)), 'X ') ? 1 : (str_starts_with(strtoupper(trim($cls->name)), 'XI ') ? 2 : 3);

            return sprintf('%02d_%d_%s', $majorRank, $gradeRank, $cls->name);
        })->values();

        $majorCounters = [];
        $classesWithMajor = $classesWithMajor->map(function ($cls) use (&$majorCounters) {
            $mKey = $cls->major_key;
            if (! isset($majorCounters[$mKey])) {
                $majorCounters[$mKey] = 0;
            }
            $majorCounters[$mKey]++;
            $cls->major_class_num = $majorCounters[$mKey];

            return $cls;
        });

        $dayOrder = ['monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5];
        $allSchedules = Schedule::with(['subject', 'teacher', 'class', 'roomModel'])
            ->get()
            ->sortBy(function ($s) use ($dayOrder) {
                $day = strtolower($s->day_of_week ?? $s->day ?? '');

                return sprintf('%02d_%s', $dayOrder[$day] ?? 99, $s->start_time);
            })
            ->values();

        $todaySchedules = $allSchedules->filter(function ($s) use ($today) {
            return strtolower($s->day_of_week ?? $s->day) === $today;
        });

        $schedulesByClass = $allSchedules->groupBy('class_id');

        return view('admin.dashboard', compact(
            'stats',
            'classes',
            'classesWithMajor',
            'teachers',
            'subjects',
            'rooms',
            'majors',
            'majorsList',
            'allSchedules',
            'todaySchedules',
            'schedulesByClass',
            'todayDayKey'
        ));
    }

    public function profile()
    {
        $user = Auth::user();

        return view('admin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $validated['name'];
        if (isset($validated['email'])) {
            $user->email = $validated['email'];
        }
        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        return back()->with('success', 'Profil akun administrator dan pengaturan keamanan berhasil diperbarui.');
    }
}
