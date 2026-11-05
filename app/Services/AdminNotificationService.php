<?php

namespace App\Services;

use App\Mail\NewSubscriptionNotificationMail;
use App\Models\Admin;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminNotificationService
{
    /**
     * Notify all active admins about a new subscription / transaction event.
     *
     * @param  string  $eventType  'checkout' | 'proof_uploaded'
     * @return array<string> List of email recipients
     */
    public function notifyAdminsNewSubscription(Transaction $transaction, string $eventType = 'proof_uploaded'): array
    {
        try {
            $adminEmails = Admin::where('is_active', true)
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->pluck('email')
                ->map(fn ($email) => trim((string) $email))
                ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
                ->unique()
                ->values()
                ->all();

            if (empty($adminEmails)) {
                Log::info('[AdminNotificationService] Tidak ada email admin aktif yang ditemukan di database untuk menerima notifikasi.');

                return [];
            }

            // Ensure related models are loaded
            $transaction->loadMissing(['user', 'plan']);

            // If SMTP driver is enabled but MAIL_PASSWORD is empty, log safely without throwing an exception
            $defaultMailer = config('mail.default', 'log');
            $smtpPassword = config('mail.mailers.smtp.password');

            if ($defaultMailer === 'smtp' && empty($smtpPassword)) {
                Log::info("[AdminNotificationService] Notifikasi email langganan ({$transaction->trx_code}) ditunda karena MAIL_PASSWORD belum diatur di .env.", [
                    'trx_code' => $transaction->trx_code,
                    'recipients_count' => count($adminEmails),
                    'recipients' => $adminEmails,
                ]);

                return $adminEmails;
            }

            foreach ($adminEmails as $email) {
                Mail::to($email)->send(new NewSubscriptionNotificationMail($transaction, $eventType));
            }

            Log::info("[AdminNotificationService] Notifikasi email langganan ({$transaction->trx_code}) berhasil dikirim ke seluruh admin.", [
                'trx_code' => $transaction->trx_code,
                'event' => $eventType,
                'recipients' => $adminEmails,
            ]);

            return $adminEmails;
        } catch (\Throwable $e) {
            Log::error('[AdminNotificationService] Terjadi kesalahan saat mengirim notifikasi email ke admin: '.$e->getMessage(), [
                'trx_code' => $transaction->trx_code ?? null,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }
}
