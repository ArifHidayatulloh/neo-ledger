<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Send notification to a specific user.
     */
    public static function send(
        int $userId,
        string $type,
        string $title,
        string $message,
        ?string $referenceType = null,
        ?int $referenceId = null
    ): Notification {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
        ]);
    }

    /**
     * Send notification to all users with a given permission.
     */
    public static function sendToPermission(
        string $permission,
        string $type,
        string $title,
        string $message,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?int $exceptUserId = null
    ): void {
        $users = User::with('role')->where('is_active', true)->get();

        foreach ($users as $user) {
            if ($exceptUserId && $user->id === $exceptUserId) {
                continue;
            }

            if ($user->hasPermission($permission)) {
                self::send($user->id, $type, $title, $message, $referenceType, $referenceId);
            }
        }
    }

    /**
     * Notify approvers about a new pending transaction.
     */
    public static function transactionPending(int $transactionId, string $description, string $amount, string $creatorName): void
    {
        self::sendToPermission(
            'transactions.approve',
            'transaction_pending',
            'Transaksi Menunggu Persetujuan',
            "{$creatorName} membuat transaksi Rp {$amount}" . ($description ? " — {$description}" : ''),
            'transaction',
            $transactionId
        );
    }

    /**
     * Notify creator that transaction was approved.
     */
    public static function transactionApproved(int $transactionId, int $creatorId, string $approverName, string $amount): void
    {
        self::send(
            $creatorId,
            'transaction_approved',
            'Transaksi Disetujui',
            "Transaksi Rp {$amount} telah disetujui oleh {$approverName}.",
            'transaction',
            $transactionId
        );
    }

    /**
     * Notify creator that transaction was rejected.
     */
    public static function transactionRejected(int $transactionId, int $creatorId, string $approverName, string $amount, string $reason): void
    {
        self::send(
            $creatorId,
            'transaction_rejected',
            'Transaksi Ditolak',
            "Transaksi Rp {$amount} ditolak oleh {$approverName}. Alasan: {$reason}",
            'transaction',
            $transactionId
        );
    }
}
