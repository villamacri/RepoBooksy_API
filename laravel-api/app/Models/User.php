<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'phone',
        'role',
        'reputation',
        'category_preferences',
        'access_level',
        'responsibility_area',
        'registration_date',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'registration_date' => 'datetime:d/m/Y H:i', 
            'password' => 'hashed',
            
            'created_at' => 'datetime:d/m/Y H:i',
        ];
    }

    // RELACIONES

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Transaction::class, 'buyer_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'buyer_id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Transaction::class, 'seller_id');
    }

    public function meetupAttendances(): HasMany
    {
        return $this->hasMany(MeetupAttendance::class);
    }

    public function meetups(): BelongsToMany
    {
        return $this->belongsToMany(Meetup::class, 'meetup_user')
            ->withTimestamps();
    }
}