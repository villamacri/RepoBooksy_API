<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'author',
        'publisher',
        'publication_year',
        'description',
        'physical_condition',
        'operation_type',
        'price',
        'publication_date',
        'is_available',
        'category_id',
        'user_id',
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
            'price' => 'decimal:2',
            'publication_date' => 'date',
            'is_available' => 'boolean', // Es buena práctica castear los booleanos
            'category_id' => 'integer',
            'user_id' => 'integer',
        ];
    }

    // RELACIONES

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); // El propietario del libro
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

}