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
     * Get all active administrator email addresses directly from the database.
     *
     * @return array<string>
     */
    public function getAdminRecipients(): array
    {
        try {
            return Admin::where('is_active', true)
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->pluck('email')
                ->map(fn ($email) => strtolower(trim((string) $email)))
                ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
                ->unique()
                ->values()
                ->all();
        } catch (\Throwable $e) {
            Log::warning('[AdminNotificationService] Tidak dapat membaca tabel admins dari database: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Notify all active admins in database about a new subscription / transaction event.
     *
     * @param  string  $eventType  'checkout' | 'proof_uploaded'
     * @return array<string> List of email recipients
     */
    public function notifyAdminsNewSubscription(Transaction $transaction, string $eventType = 'proof_uploaded'): array
    {
        try {
            $adminEmails = $this->getAdminRecipients();

            if (empty($adminEmails)) {
                Log::warning('[AdminNotificationService] Tidak ada akun admin aktif yang ditemukan di database untuk menerima notifikasi.', [
                    'trx_code' => $transaction->trx_code ?? null,
                ]);

                return [];
            }

            // Ensure related models are loaded
            $transaction->loadMissing(['user', 'plan']);

            // If SMTP driver is enabled but MAIL_PASSWORD is empty, log safely without throwing an exception
            $defaultMailer = config('mail.default', 'log');
            $smtpPassword = config('mail.mailers.smtp.password');

            if ($defaultMailer === 'smtp' && empty($smtpPassword) && ! app()->environment('testing')) {
                Log::warning("[AdminNotificationService] Pengiriman email ({$transaction->trx_code}) ditunda karena MAIL_PASSWORD di .env belum diisi.", [
                    'trx_code' => $transaction->trx_code,
                    'recipients' => $adminEmails,
                ]);

                // Fallback to log driver so details are not lost in development
                try {
                    Mail::mailer('log')->to($adminEmails[0])->send(new NewSubscriptionNotificationMail($transaction, $eventType));
                } catch (\Throwable) {
                    // Safe ignore
                }

                return $adminEmails;
            }

            $sentRecipients = [];
            $failedRecipients = [];

            foreach ($adminEmails as $email) {
                try {
                    Mail::to($email)->send(new NewSubscriptionNotificationMail($transaction, $eventType));
                    $sentRecipients[] = $email;
                    Log::info("[AdminNotificationService] Email notifikasi ({$transaction->trx_code}) berhasil dikirim ke admin: {$email}", [
                        'trx_code' => $transaction->trx_code,
                        'recipient' => $email,
                        'event' => $eventType,
                    ]);
                } catch (\Throwable $sendError) {
                    $failedRecipients[$email] = $sendError->getMessage();
                    Log::error("[AdminNotificationService] Gagal mengirim email notifikasi ke admin {$email}: {$sendError->getMessage()}", [
                        'trx_code' => $transaction->trx_code,
                        'recipient' => $email,
                        'error' => $sendError->getMessage(),
                    ]);
                }
            }

            // If SMTP failed for all recipients in non-testing environment, fallback to writing to log mailer
            if (empty($sentRecipients) && ! empty($failedRecipients) && ! app()->environment('testing')) {
                try {
                    Mail::mailer('log')->to($adminEmails[0])->send(new NewSubscriptionNotificationMail($transaction, $eventType));
                    Log::warning("[AdminNotificationService] Email notifikasi ({$transaction->trx_code}) dicadangkan ke channel log karena pengiriman SMTP gagal.", [
                        'failed_recipients' => $failedRecipients,
                    ]);
                } catch (\Throwable) {
                    // Safe ignore
                }
            }

            return $adminEmails;
        } catch (\Throwable $e) {
            Log::error('[AdminNotificationService] Terjadi kesalahan fatal pada layanan notifikasi admin: '.$e->getMessage(), [
                'trx_code' => $transaction->trx_code ?? null,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }
}
