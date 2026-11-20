<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ClassModel;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use App\Services\ScheduleConflictService;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function __construct(
        protected ScheduleConflictService $conflictService
    ) {}

    public function index()
    {
        $schedules = Schedule::with(['subject', 'teacher', 'class', 'roomModel', 'academicYear'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        $teachers = Teacher::all();
        $subjects = Subject::all();
        $classes = ClassModel::all();
        $rooms = Room::where('is_available', true)->get();
        $academicYears = AcademicYear::all();
        $activeYear = AcademicYear::where('is_active', true)->first();
        $majors = ClassModel::MAJORS;
        $todayDayKey = strtolower(now()->englishDayOfWeek);

        return view('admin.schedules', compact('schedules', 'teachers', 'subjects', 'classes', 'rooms', 'academicYears', 'activeYear', 'majors', 'todayDayKey'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'day' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'class_id' => 'required|exists:classes,id',
            'room' => 'nullable|string|max:50',
            'room_id' => 'nullable|exists:rooms,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
        ]);

        if (empty($validated['academic_year_id'])) {
            $validated['academic_year_id'] = AcademicYear::where('is_active', true)->value('id') ?? 1;
        }

        $validated['day_of_week'] = $validated['day'];

        if (! empty($validated['room'])) {
            $roomName = trim($validated['room']);
            $matchedRoom = Room::firstOrCreate(
                ['name' => $roomName],
                ['capacity' => 36, 'is_available' => true]
            );
            $validated['room_id'] = $matchedRoom->id;
            $validated['room'] = $matchedRoom->name;
        } elseif (! empty($validated['room_id'])) {
            $validated['room'] = Room::find($validated['room_id'])?->name ?? 'Ruangan';
        } else {
            $validated['room'] = 'Ruangan Kelas';
        }

        $this->conflictService->validate($validated);

        Schedule::create($validated);

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'day' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'class_id' => 'required|exists:classes,id',
            'room' => 'nullable|string|max:50',
            'room_id' => 'nullable|exists:rooms,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
        ]);

        if (empty($validated['academic_year_id'])) {
            $validated['academic_year_id'] = $schedule->academic_year_id ?? AcademicYear::where('is_active', true)->value('id') ?? 1;
        }

        $validated['day_of_week'] = $validated['day'];

        if (! empty($validated['room'])) {
            $roomName = trim($validated['room']);
            $matchedRoom = Room::firstOrCreate(
                ['name' => $roomName],
                ['capacity' => 36, 'is_available' => true]
            );
            $validated['room_id'] = $matchedRoom->id;
            $validated['room'] = $matchedRoom->name;
        } elseif (! empty($validated['room_id'])) {
            $validated['room'] = Room::find($validated['room_id'])?->name ?? 'Ruangan';
        }

        $merged = array_merge($schedule->toArray(), $validated);
        $this->conflictService->validate($merged, $schedule->id);

        $schedule->update($validated);

        return back()->with('success', 'Jadwal berhasil diubah.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}
