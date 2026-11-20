<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $date = $request->query('date');
        $effectiveTeacher = $this->effectiveTeacherForDate($date);

        return [
            'id' => $this->id,
            'academic_year_id' => $this->academic_year_id,
            'class_id' => $this->class_id,
            'subject_id' => $this->subject_id,
            'teacher_id' => $this->teacher_id,
            'room_id' => $this->room_id,
            'day_of_week' => $this->day_of_week ?? $this->day,
            'day' => $this->day_of_week ?? $this->day,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'room' => $this->roomModel?->name ?? $this->room,
            'class' => new ClassResource($this->whenLoaded('class')),
            'subject' => new SubjectResource($this->whenLoaded('subject')),
            'teacher' => new TeacherResource($this->whenLoaded('teacher')),
            'effective_teacher' => new TeacherResource($effectiveTeacher),
            'room_details' => new RoomResource($this->whenLoaded('roomModel')),
            'academic_year' => new AcademicYearResource($this->whenLoaded('academicYear')),
            'is_substituted' => $effectiveTeacher->id !== $this->teacher_id,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
