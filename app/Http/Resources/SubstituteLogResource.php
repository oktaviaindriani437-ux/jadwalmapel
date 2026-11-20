<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubstituteLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'schedule_id' => $this->schedule_id,
            'original_teacher_id' => $this->original_teacher_id,
            'substitute_teacher_id' => $this->substitute_teacher_id,
            'date' => $this->date?->format('Y-m-d'),
            'status' => $this->status,
            'notes' => $this->notes,
            'schedule' => new ScheduleResource($this->whenLoaded('schedule')),
            'original_teacher' => new TeacherResource($this->whenLoaded('originalTeacher')),
            'substitute_teacher' => new TeacherResource($this->whenLoaded('substituteTeacher')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
