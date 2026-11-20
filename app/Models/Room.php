<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stringable;

class Room extends Model implements Stringable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'is_available',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'is_available' => 'boolean',
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'room_id');
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }
}
