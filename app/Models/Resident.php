<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resident extends Model
{
    /** @use HasFactory<\Database\Factories\ResidentFactory> */
    use HasFactory;

    protected $fillable = [
        'nik',
        'name',
        'no_kk',
        'address',
        'phone_number',
        'status',
        'is_head_of_family',
        'photo_path',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'is_head_of_family' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function digitalLetters(): HasMany
    {
        return $this->hasMany(DigitalLetter::class);
    }
}
