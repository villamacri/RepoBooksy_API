<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'password',
        'telefono',
        'role',
        'reputacion',
        'preferencias_categoria',
        'nivel_acceso',
        'area_responsabilidad',
        'fecha_registro',
        'estado',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'fecha_registro' => 'date',
            'password' => 'hashed',
        ];
    }

    public function libros(): HasMany
    {
        return $this->hasMany(Libro::class);
    }

    public function transaccions(): HasMany
    {
        return $this->hasMany(Transaccion::class);
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    public function reportes(): HasMany
    {
        return $this->hasMany(Reporte::class);
    }

    public function participacionEventos(): HasMany
    {
        return $this->hasMany(ParticipacionEvento::class);
    }
}