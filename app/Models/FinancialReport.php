<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialReport extends Model
{
    /** @use HasFactory<\Database\Factories\FinancialReportFactory> */
    use HasFactory;

    protected $fillable = [
        'month',
        'year',
        'opening_balance',
        'total_income',
        'total_expense',
        'closing_balance',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'opening_balance' => 'decimal:2',
            'total_income' => 'decimal:2',
            'total_expense' => 'decimal:2',
            'closing_balance' => 'decimal:2',
        ];
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
