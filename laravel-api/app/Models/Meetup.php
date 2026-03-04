<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meetup extends Model
{
    use HasFactory;

    protected $appends = [
        'local_id',
    ];

    protected $fillable = [
        'name',
        'description',
        'meetup_date',
        'city',
        'location',
        'max_capacity',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'meetup_date' => 'date', // Ajustado a date
            'max_capacity' => 'integer',
        ];
    }

    public function getLocalIdAttribute(): int
    {
        return $this->id;
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(MeetupAttendance::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'meetup_user')
            ->withTimestamps();
    }
}