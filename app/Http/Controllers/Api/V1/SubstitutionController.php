<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubstitutionRequest;
use App\Http\Resources\SubstituteLogResource;
use App\Http\Resources\TeacherResource;
use App\Models\Schedule;
use App\Models\SubstituteLog;
use App\Services\SubstitutionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SubstitutionController extends Controller
{
    public function __construct(
        protected SubstitutionService $substitutionService
    ) {}

    /**
     * List all substitute logs with optional filters.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = SubstituteLog::query()
            ->with(['schedule.subject', 'schedule.class', 'originalTeacher', 'substituteTeacher'])
            ->when($request->filled('date'), fn ($q) => $q->whereDate('date', $request->query('date')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->query('status')))
            ->when($request->filled('schedule_id'), fn ($q) => $q->where('schedule_id', $request->query('schedule_id')))
            ->when($request->filled('teacher_id'), function ($q) use ($request) {
                $teacherId = $request->query('teacher_id');
                $q->where(fn ($sub) => $sub->where('original_teacher_id', $teacherId)->orWhere('substitute_teacher_id', $teacherId));
            })
            ->orderByDesc('date')
            ->orderByDesc('id');

        return SubstituteLogResource::collection($query->get());
    }

    /**
     * Create and assign a substitute teacher for a given date.
     */
    public function store(StoreSubstitutionRequest $request): JsonResponse
    {
        $log = $this->substitutionService->createSubstitution($request->validated());
        $log->load(['schedule.subject', 'schedule.class', 'originalTeacher', 'substituteTeacher']);

        return (new SubstituteLogResource($log))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display a single substitution log.
     */
    public function show(SubstituteLog $substitution): SubstituteLogResource
    {
        $substitution->load(['schedule.subject', 'schedule.class', 'originalTeacher', 'substituteTeacher']);

        return new SubstituteLogResource($substitution);
    }

    /**
     * Suggest available replacement teachers who do not have an active teaching schedule on that day and time.
     */
    public function availableTeachers(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'date' => 'required|date',
        ]);

        $schedule = Schedule::findOrFail($validated['schedule_id']);
        $availableTeachers = $this->substitutionService->getAvailableTeachers($schedule, $validated['date']);

        return response()->json([
            'schedule_id' => $schedule->id,
            'date' => $validated['date'],
            'original_teacher_id' => $schedule->teacher_id,
            'available_teachers' => TeacherResource::collection($availableTeachers),
        ]);
    }

    /**
     * Approve or reject a substitute request.
     */
    public function updateStatus(Request $request, SubstituteLog $substitution): SubstituteLogResource
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,pending',
            'notes' => 'nullable|string|max:500',
        ]);

        $log = $this->substitutionService->updateStatus(
            $substitution,
            $validated['status'],
            $validated['notes'] ?? null
        );

        $log->load(['schedule.subject', 'schedule.class', 'originalTeacher', 'substituteTeacher']);

        return new SubstituteLogResource($log);
    }
}
