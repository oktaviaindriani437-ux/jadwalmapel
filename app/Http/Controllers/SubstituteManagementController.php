<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\SubstituteLog;
use App\Models\Teacher;
use App\Services\SubstitutionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SubstituteManagementController extends Controller
{
    public function __construct(
        protected SubstitutionService $substitutionService
    ) {}

    public function index()
    {
        $logs = SubstituteLog::with(['schedule.subject', 'schedule.class', 'originalTeacher', 'substituteTeacher'])
            ->latest('date')
            ->get();

        $schedules = Schedule::with(['subject', 'class', 'teacher'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        $teachers = Teacher::orderBy('name')->get();

        return view('admin.substitutes', compact('logs', 'schedules', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'date' => 'required|date',
            'substitute_teacher_id' => 'required|exists:teachers,id',
            'notes' => 'nullable|string|max:255',
            'status' => 'required|in:approved,pending',
        ]);

        try {
            $this->substitutionService->createSubstitution($validated);

            return back()->with('success', 'Penugasan guru pengganti (inval) berhasil disimpan.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function update(Request $request, SubstituteLog $substitute)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'date' => 'required|date',
            'substitute_teacher_id' => 'required|exists:teachers,id',
            'notes' => 'nullable|string|max:255',
            'status' => 'required|in:approved,pending,rejected',
        ]);

        $schedule = Schedule::findOrFail($validated['schedule_id']);
        $formattedDate = Carbon::parse($validated['date'])->format('Y-m-d');

        $substitute->update([
            'schedule_id' => $validated['schedule_id'],
            'original_teacher_id' => $schedule->teacher_id,
            'substitute_teacher_id' => $validated['substitute_teacher_id'],
            'date' => $formattedDate,
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Data penugasan guru inval berhasil diperbarui.');
    }

    public function updateStatus(Request $request, SubstituteLog $substitute)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,pending',
            'notes' => 'nullable|string|max:255',
        ]);

        try {
            $this->substitutionService->updateStatus($substitute, $validated['status'], $validated['notes'] ?? null);

            return back()->with('success', 'Status guru inval berhasil diperbarui menjadi '.ucfirst($validated['status']).'.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    public function destroy(SubstituteLog $substitute)
    {
        $substitute->delete();

        return back()->with('success', 'Data guru inval berhasil dihapus.');
    }
}
