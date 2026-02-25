<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    public $timestamps = false;

    protected static function booted(): void
    {
        static::creating(function (AuditLog $log) {
            if (is_null($log->getAttribute('ip_address'))) {
                try {
                    $log->ip_address = request()->ip();
                } catch (\Throwable $e) {
                    $log->ip_address = 'unknown';
                }
            }

            if (is_null($log->getAttribute('user_agent'))) {
                try {
                    $log->user_agent = request()->userAgent();
                } catch (\Throwable $e) {
                    $log->user_agent = 'unknown';
                }
            }

            if (is_null($log->getAttribute('created_at'))) {
                $log->created_at = now();
            }
        });
    }

    protected $fillable = [
        'user_id',
        'action',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auditable()
    {
        return $this->morphTo();
    }
}
