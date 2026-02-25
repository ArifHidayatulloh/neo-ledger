<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budget extends Model
{
    protected $fillable = [
        'category_id',
        'limit_amount',
        'spent_amount',
        'period',
        'alert_sent_80',
        'alert_sent_100',
    ];

    protected function casts(): array
    {
        return [
            'limit_amount' => 'decimal:2',
            'spent_amount' => 'decimal:2',
            'alert_sent_80' => 'boolean',
            'alert_sent_100' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // ── Accessors ──

    public function getUsagePercentageAttribute(): float
    {
        if ($this->limit_amount <= 0) return 0;
        return round(($this->spent_amount / $this->limit_amount) * 100, 1);
    }

    public function getRemainingAttribute(): float
    {
        return $this->limit_amount - $this->spent_amount;
    }

    // ── Helpers ──

    public function isOverBudget(): bool
    {
        return $this->spent_amount >= $this->limit_amount;
    }

    public function isNearLimit(): bool
    {
        return $this->usage_percentage >= 80 && !$this->isOverBudget();
    }
}
