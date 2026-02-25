<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalSetting extends Model
{
    protected $fillable = [
        'transaction_type',
        'threshold_amount',
        'approver_role_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'threshold_amount' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function approverRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'approver_role_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function getThreshold(string $transactionType): ?float
    {
        $setting = static::active()
            ->where('transaction_type', $transactionType)
            ->first();

        return $setting?->threshold_amount;
    }
}
