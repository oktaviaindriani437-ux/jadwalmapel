<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\SubstituteLog;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class SubstitutionService
{
    /**
     * Get available substitute teachers for a schedule on a specific date.
     *
     * @return Collection<int, Teacher>
     */
    public function getAvailableTeachers(Schedule $schedule, string $date): Collection
    {
        $targetDate = Carbon::parse($date);
        $dayOfWeek = strtolower($targetDate->englishDayOfWeek);
        $formattedDate = $targetDate->format('Y-m-d');

        $busyInSchedules = Schedule::query()
            ->where('academic_year_id', $schedule->academic_year_id)
            ->where(function ($q) use ($dayOfWeek) {
                $q->where('day_of_week', $dayOfWeek)->orWhere('day', $dayOfWeek);
            })
            ->where('start_time', '<', $schedule->end_time)
            ->where('end_time', '>', $schedule->start_time)
            ->pluck('teacher_id')
            ->toArray();

        $busyInSubstitutions = SubstituteLog::query()
            ->whereDate('date', $formattedDate)
            ->where('status', 'approved')
            ->whereHas('schedule', function ($q) use ($schedule) {
                $q->where('start_time', '<', $schedule->end_time)
                    ->where('end_time', '>', $schedule->start_time);
            })
            ->pluck('substitute_teacher_id')
            ->toArray();

        $excludedTeacherIds = array_unique(array_merge(
            [$schedule->teacher_id],
            $busyInSchedules,
            $busyInSubstitutions
        ));

        return Teacher::query()
            ->whereNotIn('id', $excludedTeacherIds)
            ->get();
    }

    /**
     * Create a new substitute request or assignment.
     *
     * @param  array<string, mixed>  $data
     *
     * @throws ValidationException
     */
    public function createSubstitution(array $data): SubstituteLog
    {
        $schedule = Schedule::findOrFail($data['schedule_id']);
        $substituteTeacherId = (int) $data['substitute_teacher_id'];
        $formattedDate = Carbon::parse($data['date'])->format('Y-m-d');

        if ($substituteTeacherId === (int) $schedule->teacher_id) {
            throw ValidationException::withMessages([
                'substitute_teacher_id' => ['Guru pengganti tidak boleh sama dengan guru yang berhalangan.'],
            ]);
        }

        $availableTeachers = $this->getAvailableTeachers($schedule, $formattedDate);
        if (! $availableTeachers->contains('id', $substituteTeacherId)) {
            $subTeacher = Teacher::find($substituteTeacherId);
            $subName = $subTeacher?->name ?? 'Guru yang dipilih';
            throw ValidationException::withMessages([
                'substitute_teacher_id' => ["Guru pengganti {$subName} tidak tersedia karena memiliki jadwal mengajar lain pada waktu tersebut."],
            ]);
        }

        return SubstituteLog::create([
            'schedule_id' => $schedule->id,
            'original_teacher_id' => $schedule->teacher_id,
            'substitute_teacher_id' => $substituteTeacherId,
            'date' => $formattedDate,
            'status' => $data['status'] ?? 'pending',
            'notes' => $data['notes'] ?? null,
        ]);
    }

    /**
     * Approve or reject a substitution log.
     *
     * @throws ValidationException
     */
    public function updateStatus(SubstituteLog $log, string $status, ?string $notes = null): SubstituteLog
    {
        if (! in_array($status, ['approved', 'rejected', 'pending'], true)) {
            throw ValidationException::withMessages([
                'status' => ['Status tidak valid.'],
            ]);
        }

        if ($status === 'approved') {
            $availableTeachers = $this->getAvailableTeachers($log->schedule, $log->date->format('Y-m-d'));
            if (! $availableTeachers->contains('id', $log->substitute_teacher_id)) {
                $subTeacher = $log->substituteTeacher;
                $name = $subTeacher?->name ?? 'Guru';
                throw ValidationException::withMessages([
                    'status' => ["Tidak dapat menyetujui: Guru {$name} sudah memiliki jadwal bentrok pada tanggal {$log->date->format('Y-m-d')}."],
                ]);
            }
        }

        $log->update([
            'status' => $status,
            'notes' => $notes ?? $log->notes,
        ]);

        return $log;
    }
}
