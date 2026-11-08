<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewSubscriptionNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Transaction $transaction,
        public string $eventType = 'proof_uploaded'
    ) {
        $this->transaction->loadMissing(['user', 'plan']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $userName = $this->transaction->user->name ?? 'Pengguna';
        $planName = $this->transaction->plan->name ?? 'Paket VIP';
        $trxCode = $this->transaction->trx_code;

        if ($this->eventType === 'proof_uploaded') {
            $subject = "[HUMORA] Bukti Pembayaran Masuk: {$trxCode} - {$userName} ({$planName})";
        } else {
            $subject = "[HUMORA] Permintaan Langganan Baru: {$trxCode} - {$userName} ({$planName})";
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $configured = config('app.url');
        $appUrl = (! empty($configured) && ! str_contains($configured, 'localhost') && ! str_contains($configured, '127.0.0.1'))
            ? $configured
            : 'https://humora.web.id';

        $appUrl = rtrim((string) $appUrl, '/');
        $verificationUrl = $appUrl.'/admin/transactions?search='.urlencode($this->transaction->trx_code);

        return new Content(
            view: 'emails.admin.new-subscription',
            with: [
                'transaction' => $this->transaction,
                'user' => $this->transaction->user,
                'plan' => $this->transaction->plan,
                'eventType' => $this->eventType,
                'verificationUrl' => $verificationUrl,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        if (! empty($this->transaction->proof_file)) {
            $proofPath = storage_path('uploads/proofs/'.$this->transaction->proof_file);
            if (file_exists($proofPath)) {
                $ext = pathinfo($proofPath, PATHINFO_EXTENSION) ?: 'jpg';
                $attachments[] = Attachment::fromPath($proofPath)
                    ->as('bukti_transfer_'.$this->transaction->trx_code.'.'.$ext);
            }
        }

        return $attachments;
    }
}
