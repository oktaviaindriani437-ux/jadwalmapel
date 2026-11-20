<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\ClassModel;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class ScheduleConflictService
{
    /**
     * Validate schedule data for teacher, class, and room conflicts.
     *
     * @param  array<string, mixed>  $data
     *
     * @throws ValidationException
     */
    public function validate(array $data, ?int $ignoreScheduleId = null): void
    {
        $academicYearId = (int) ($data['academic_year_id'] ?? AcademicYear::where('is_active', true)->value('id') ?? 1);
        $dayOfWeek = strtolower((string) ($data['day_of_week'] ?? $data['day'] ?? 'monday'));
        $startTime = Carbon::parse($data['start_time'])->format('H:i:s');
        $endTime = Carbon::parse($data['end_time'])->format('H:i:s');
        $teacherId = isset($data['teacher_id']) ? (int) $data['teacher_id'] : null;
        $classId = isset($data['class_id']) ? (int) $data['class_id'] : null;
        $roomId = ! empty($data['room_id']) ? (int) $data['room_id'] : null;

        $errors = [];

        $dayNamesIndo = [
            'monday' => 'Senin',
            'tuesday' => 'Selasa',
            'wednesday' => 'Rabu',
            'thursday' => 'Kamis',
            'friday' => 'Jumat',
            'saturday' => 'Sabtu',
            'sunday' => 'Minggu',
        ];
        $dayName = $dayNamesIndo[$dayOfWeek] ?? ucfirst($dayOfWeek);

        if ($teacherId) {
            $teacherConflict = $this->checkTeacherConflict($academicYearId, $dayOfWeek, $startTime, $endTime, $teacherId, $ignoreScheduleId);
            if ($teacherConflict) {
                $teacher = Teacher::find($teacherId);
                $teacherName = $teacher?->name ?? 'Guru';
                $className = $teacherConflict->class?->name ?? 'Kelas lain';
                $timeRange = substr($teacherConflict->start_time, 0, 5) . ' - ' . substr($teacherConflict->end_time, 0, 5) . ' WIB';
                $errors['teacher_id'] = [
                    "Konflik Guru: Guru {$teacherName} sudah memiliki jadwal mengajar pada hari {$dayName} pukul {$timeRange} di kelas {$className}.",
                ];
            }
        }

        if ($classId) {
            $classConflict = $this->checkClassConflict($academicYearId, $dayOfWeek, $startTime, $endTime, $classId, $ignoreScheduleId);
            if ($classConflict) {
                $class = ClassModel::find($classId);
                $className = $class?->name ?? 'Kelas';
                $subjectName = $classConflict->subject?->name ?? 'Mata pelajaran lain';
                $timeRange = substr($classConflict->start_time, 0, 5) . ' - ' . substr($classConflict->end_time, 0, 5) . ' WIB';
                $errors['class_id'] = [
                    "Konflik Kelas: Kelas {$className} sudah memiliki jadwal mata pelajaran {$subjectName} pada hari {$dayName} pukul {$timeRange}.",
                ];
            }
        }

        if ($roomId) {
            $roomConflict = $this->checkRoomConflict($academicYearId, $dayOfWeek, $startTime, $endTime, $roomId, $ignoreScheduleId);
            if ($roomConflict) {
                $room = Room::find($roomId);
                $roomName = $room?->name ?? 'Ruangan';
                $className = $roomConflict->class?->name ?? 'Kelas lain';
                $timeRange = substr($roomConflict->start_time, 0, 5) . ' - ' . substr($roomConflict->end_time, 0, 5) . ' WIB';
                $errors['room_id'] = [
                    "Konflik Ruangan: Ruangan {$roomName} sudah digunakan oleh kelas {$className} pada hari {$dayName} pukul {$timeRange}.",
                ];
            }
        }

        if (! empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }

    /**
     * Check if teacher has an overlapping schedule.
     */
    public function checkTeacherConflict(
        int $academicYearId,
        string $dayOfWeek,
        string $startTime,
        string $endTime,
        int $teacherId,
        ?int $ignoreScheduleId = null
    ): ?Schedule {
        return Schedule::query()
            ->where('academic_year_id', $academicYearId)
            ->where(function ($q) use ($dayOfWeek) {
                $q->where('day_of_week', $dayOfWeek)->orWhere('day', $dayOfWeek);
            })
            ->where('teacher_id', $teacherId)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->when($ignoreScheduleId, fn ($q) => $q->where('id', '!=', $ignoreScheduleId))
            ->with(['teacher', 'class', 'subject'])
            ->first();
    }

    /**
     * Check if class has an overlapping schedule.
     */
    public function checkClassConflict(
        int $academicYearId,
        string $dayOfWeek,
        string $startTime,
        string $endTime,
        int $classId,
        ?int $ignoreScheduleId = null
    ): ?Schedule {
        return Schedule::query()
            ->where('academic_year_id', $academicYearId)
            ->where(function ($q) use ($dayOfWeek) {
                $q->where('day_of_week', $dayOfWeek)->orWhere('day', $dayOfWeek);
            })
            ->where('class_id', $classId)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->when($ignoreScheduleId, fn ($q) => $q->where('id', '!=', $ignoreScheduleId))
            ->with(['class', 'subject', 'teacher'])
            ->first();
    }

    /**
     * Check if room has an overlapping schedule.
     */
    public function checkRoomConflict(
        int $academicYearId,
        string $dayOfWeek,
        string $startTime,
        string $endTime,
        int $roomId,
        ?int $ignoreScheduleId = null
    ): ?Schedule {
        return Schedule::query()
            ->where('academic_year_id', $academicYearId)
            ->where(function ($q) use ($dayOfWeek) {
                $q->where('day_of_week', $dayOfWeek)->orWhere('day', $dayOfWeek);
            })
            ->where('room_id', $roomId)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->when($ignoreScheduleId, fn ($q) => $q->where('id', '!=', $ignoreScheduleId))
            ->with(['roomModel', 'class', 'subject'])
            ->first();
    }
}
