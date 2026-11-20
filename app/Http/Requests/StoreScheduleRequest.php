<?php

namespace App\Http\Requests;

use App\Models\AcademicYear;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare inputs for validation.
     */
    protected function prepareForValidation(): void
    {
        if (! $this->has('academic_year_id') && ! $this->filled('academic_year_id')) {
            $activeYearId = AcademicYear::where('is_active', true)->value('id');
            if ($activeYearId) {
                $this->merge(['academic_year_id' => $activeYearId]);
            }
        }

        if ($this->has('day') && ! $this->has('day_of_week')) {
            $this->merge(['day_of_week' => strtolower($this->input('day'))]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'room_id' => 'nullable|exists:rooms,id',
            'day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i,H:i:s',
            'end_time' => 'required|date_format:H:i,H:i:s|after:start_time',
            'room' => 'nullable|string|max:100',
        ];
    }
}
