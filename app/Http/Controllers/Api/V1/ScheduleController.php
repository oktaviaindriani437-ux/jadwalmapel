<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CloneTermRequest;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Http\Resources\ClassResource;
use App\Http\Resources\ScheduleResource;
use App\Http\Resources\TeacherResource;
use App\Models\AcademicYear;
use App\Models\ClassModel;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Services\ScheduleConflictService;
use App\Services\ScheduleExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ScheduleController extends Controller
{
    public function __construct(
        protected ScheduleConflictService $conflictService,
        protected ScheduleExportService $exportService
    ) {}

    /**
     * List all schedules with query filters.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Schedule::query()
            ->with(['class', 'subject', 'teacher', 'roomModel', 'academicYear'])
            ->when($request->filled('class_id'), fn ($q) => $q->where('class_id', $request->query('class_id')))
            ->when($request->filled('teacher_id'), fn ($q) => $q->where('teacher_id', $request->query('teacher_id')))
            ->when($request->filled('academic_year_id'), fn ($q) => $q->where('academic_year_id', $request->query('academic_year_id')))
            ->when($request->filled('day'), function ($q) use ($request) {
                $day = strtolower((string) $request->query('day'));
                $q->where(fn ($sub) => $sub->where('day_of_week', $day)->orWhere('day', $day));
            })
            ->when($request->filled('day_of_week'), function ($q) use ($request) {
                $day = strtolower((string) $request->query('day_of_week'));
                $q->where(fn ($sub) => $sub->where('day_of_week', $day)->orWhere('day', $day));
            })
            ->orderBy('day_of_week')
            ->orderBy('start_time');

        return ScheduleResource::collection($query->get());
    }

    /**
     * Create a new schedule entry after conflict validation.
     */
    public function store(StoreScheduleRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $this->conflictService->validate($validated);

        $schedule = Schedule::create($validated);
        $schedule->load(['class', 'subject', 'teacher', 'roomModel', 'academicYear']);

        return (new ScheduleResource($schedule))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display a specific schedule entry.
     */
    public function show(Schedule $schedule): ScheduleResource
    {
        $schedule->load(['class', 'subject', 'teacher', 'roomModel', 'academicYear']);

        return new ScheduleResource($schedule);
    }

    /**
     * Update an existing schedule entry with conflict exclusion.
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule): ScheduleResource
    {
        $validated = $request->validated();
        $mergedData = array_merge($schedule->toArray(), $validated);

        $this->conflictService->validate($mergedData, $schedule->id);

        $schedule->update($validated);
        $schedule->load(['class', 'subject', 'teacher', 'roomModel', 'academicYear']);

        return new ScheduleResource($schedule);
    }

    /**
     * Delete a schedule entry.
     */
    public function destroy(Schedule $schedule): JsonResponse
    {
        $schedule->delete();

        return response()->json([
            'message' => 'Jadwal berhasil dihapus.',
        ]);
    }

    /**
     * Weekly timetable grid response for a specific class.
     */
    public function timetableByClass(Request $request, int $classId): JsonResponse
    {
        $class = ClassModel::with('academicYear')->findOrFail($classId);

        $academicYearId = $request->query('academic_year_id')
            ?? $class->academic_year_id
            ?? AcademicYear::where('is_active', true)->value('id');

        $schedules = Schedule::query()
            ->where('class_id', $classId)
            ->when($academicYearId, fn ($q) => $q->where('academic_year_id', $academicYearId))
            ->with(['subject', 'teacher', 'roomModel', 'academicYear'])
            ->orderBy('start_time')
            ->get();

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $timetable = [];

        foreach ($days as $day) {
            $daySchedules = $schedules->filter(function ($s) use ($day) {
                return strtolower($s->day_of_week ?? $s->day) === $day;
            })->values();

            $timetable[$day] = ScheduleResource::collection($daySchedules);
        }

        return response()->json([
            'class' => new ClassResource($class),
            'academic_year_id' => $academicYearId ? (int) $academicYearId : null,
            'date' => $request->query('date'),
            'timetable' => $timetable,
        ]);
    }

    /**
     * Weekly timetable grid response for a specific teacher.
     */
    public function timetableByTeacher(Request $request, int $teacherId): JsonResponse
    {
        $teacher = Teacher::findOrFail($teacherId);

        $academicYearId = $request->query('academic_year_id')
            ?? AcademicYear::where('is_active', true)->value('id');

        $schedules = Schedule::query()
            ->where('teacher_id', $teacherId)
            ->when($academicYearId, fn ($q) => $q->where('academic_year_id', $academicYearId))
            ->with(['class', 'subject', 'roomModel', 'academicYear'])
            ->orderBy('start_time')
            ->get();

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $timetable = [];

        foreach ($days as $day) {
            $daySchedules = $schedules->filter(function ($s) use ($day) {
                return strtolower($s->day_of_week ?? $s->day) === $day;
            })->values();

            $timetable[$day] = ScheduleResource::collection($daySchedules);
        }

        return response()->json([
            'teacher' => new TeacherResource($teacher),
            'academic_year_id' => $academicYearId ? (int) $academicYearId : null,
            'timetable' => $timetable,
        ]);
    }

    /**
     * Clone entire schedule layout from one academic term to another.
     */
    public function cloneTerm(CloneTermRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $fromId = (int) $validated['from_academic_year_id'];
        $toId = (int) $validated['to_academic_year_id'];
        $overwrite = ! empty($validated['overwrite']);

        $sourceSchedules = Schedule::where('academic_year_id', $fromId)->get();

        if ($sourceSchedules->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada jadwal yang ditemukan pada tahun akademik sumber.',
                'cloned_count' => 0,
            ], 404);
        }

        $clonedCount = DB::transaction(function () use ($sourceSchedules, $toId, $overwrite): int {
            if ($overwrite) {
                Schedule::where('academic_year_id', $toId)->delete();
            }

            $count = 0;
            foreach ($sourceSchedules as $source) {
                Schedule::create([
                    'academic_year_id' => $toId,
                    'class_id' => $source->class_id,
                    'subject_id' => $source->subject_id,
                    'teacher_id' => $source->teacher_id,
                    'room_id' => $source->room_id,
                    'day_of_week' => $source->day_of_week ?? $source->day,
                    'day' => $source->day ?? $source->day_of_week,
                    'start_time' => $source->start_time,
                    'end_time' => $source->end_time,
                    'room' => $source->room,
                ]);
                $count++;
            }

            return $count;
        });

        return response()->json([
            'message' => "Berhasil menyalin {$clonedCount} jadwal ke semester/tahun ajaran baru.",
            'from_academic_year_id' => $fromId,
            'to_academic_year_id' => $toId,
            'cloned_count' => $clonedCount,
        ]);
    }

    /**
     * Export schedule view to CSV/Excel.
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        return $this->exportService->exportCsv($request->all());
    }

    /**
     * Export schedule view to printable PDF.
     */
    public function exportPdf(Request $request): Response
    {
        return $this->exportService->exportPdf($request->all());
    }
}
