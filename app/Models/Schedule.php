<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'class_id',
        'subject_id',
        'teacher_id',
        'room_id',
        'day_of_week',
        'day',
        'start_time',
        'end_time',
        'room',
    ];

    protected static function booted(): void
    {
        static::saving(function (Schedule $schedule): void {
            if (! empty($schedule->day_of_week) && empty($schedule->day)) {
                $schedule->day = $schedule->day_of_week;
            } elseif (! empty($schedule->day) && empty($schedule->day_of_week)) {
                $schedule->day_of_week = $schedule->day;
            }

            if (! empty($schedule->room_id) && empty($schedule->room)) {
                $room = Room::find($schedule->room_id);
                if ($room) {
                    $schedule->room = $room->name;
                }
            }
        });
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function roomModel(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function substituteLogs(): HasMany
    {
        return $this->hasMany(SubstituteLog::class);
    }

    public function effectiveTeacherForDate(?string $date): Teacher
    {
        if (! $date) {
            return $this->teacher;
        }

        $formattedDate = Carbon::parse($date)->format('Y-m-d');

        $substitution = $this->substituteLogs()
            ->where('status', 'approved')
            ->whereDate('date', $formattedDate)
            ->with('substituteTeacher')
            ->first();

        return $substitution ? $substitution->substituteTeacher : $this->teacher;
    }
}
