<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetupAttendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enrollment_date',
        'status',
        'user_id',
        'meetup_id', // Antes evento_id
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'enrollment_date' => 'date', // fecha_inscripcion
            'user_id' => 'integer',
            'meetup_id' => 'integer',
        ];
    }

    // RELACIONES

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function meetup(): BelongsTo
    {
        return $this->belongsTo(Meetup::class); // Antes Evento::class
    }
}