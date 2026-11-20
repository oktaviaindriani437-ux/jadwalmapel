<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    public const MAJORS = [
        'PPLG' => 'PPLG (Pengembangan Perangkat Lunak dan Gim)',
        'BCF' => 'BCF (Broadcasting dan Perfilman)',
        'TPFL' => 'TPFL (Teknik Pengelasan dan Fabrikasi Logam)',
        'TO' => 'Teknik Otomotif (TO)',
        'Animasi' => 'Animasi',
    ];

    protected $fillable = [
        'name',
        'major',
        'academic_year_id',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'class_id');
    }
}
