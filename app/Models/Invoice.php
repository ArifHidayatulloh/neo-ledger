<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\InvoiceService;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number', 'spk_id', 'client_id', 'total_contract', 'dp_amount', 'final_bill', 'status', 'sequence', 'year', 'month', 'bank_account', 'bank_name', 'bank_number', 'due_date', 'pdf_path', 'sent_at', 'paid_at'
    ];

    protected $casts = [
        'total_contract' => 'decimal:2',
        'dp_amount' => 'decimal:2',
        'final_bill' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function spk(): BelongsTo
    {
        return $this->belongsTo(Spk::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            $now = now();
            $year = (int)$now->format('Y');
            $month = (int)$now->format('m');
            $generated = InvoiceService::generateInvoiceNumber($year, $month);
            $invoice->invoice_number = $generated['invoice_number'];
            $invoice->sequence = $generated['sequence'];
            $invoice->year = $year;
            $invoice->month = $month;
        });
    }
}
