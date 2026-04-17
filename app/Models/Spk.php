<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Spk extends Model
{
    protected $table = 'spks';

    protected $fillable = [
        'spk_ref', 'client_id', 'total_contract', 'dp_amount', 'final_bill', 'status', 'is_finalized', 'context'
    ];

    protected $casts = [
        'total_contract' => 'decimal:2',
        'dp_amount' => 'decimal:2',
        'final_bill' => 'decimal:2',
        'is_finalized' => 'boolean',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function calculateFinalBill(): float
    {
        return (float) bcmul((string)($this->total_contract - $this->dp_amount), '1', 2);
    }
}
