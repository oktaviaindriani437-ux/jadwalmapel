<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'nip',
        'phone',
        'max_hours_per_week',
    ];

    protected $casts = [
        'max_hours_per_week' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function substitutionsAsOriginal(): HasMany
    {
        return $this->hasMany(SubstituteLog::class, 'original_teacher_id');
    }

    public function substitutionsAsSubstitute(): HasMany
    {
        return $this->hasMany(SubstituteLog::class, 'substitute_teacher_id');
    }
}
