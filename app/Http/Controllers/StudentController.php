<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function dashboard()
    {
        $student = Auth::user()->student()->with('class')->first();

        $today = strtolower(now()->englishDayOfWeek);

        $todaySchedules = Schedule::with(['subject', 'teacher', 'class', 'roomModel'])
            ->where('class_id', $student?->class_id)
            ->where(function ($q) use ($today) {
                $q->where('day', $today)->orWhere('day_of_week', $today);
            })
            ->orderBy('start_time')
            ->get();

        $allSchedules = Schedule::with(['subject', 'teacher', 'class', 'roomModel'])
            ->where('class_id', $student?->class_id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('student.dashboard', compact('student', 'todaySchedules', 'allSchedules', 'today'));
    }

    public function profile()
    {
        $student = Auth::user()->student()->with(['class', 'user'])->first();

        return view('student.profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        return back()->with('success', 'Kata sandi akun siswa berhasil diperbarui.');
    }

    public function schedule()
    {
        $student = Auth::user()->student()->with('class')->first();
        $schedules = Schedule::with(['subject', 'teacher', 'class', 'roomModel'])
            ->where('class_id', $student?->class_id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('student.schedule', compact('schedules', 'student'));
    }

    public function subjects()
    {
        $subjects = Subject::with('teacher')->get();

        return view('student.subjects', compact('subjects'));
    }

    public function teachers()
    {
        $teachers = Teacher::with('subjects')->get();

        return view('student.teachers', compact('teachers'));
    }
}
