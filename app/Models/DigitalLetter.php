<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DigitalLetter extends Model
{
    /** @use HasFactory<\Database\Factories\DigitalLetterFactory> */
    use HasFactory;

    protected $fillable = [
        'letter_type',
        'letter_number',
        'resident_id',
        'letter_content',
        'signature_path',
        'issued_date',
        'valid_until',
        'pdf_path',
        'issued_by',
    ];

    protected function casts(): array
    {
        return [
            'issued_date' => 'date',
            'valid_until' => 'date',
        ];
    }

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}
