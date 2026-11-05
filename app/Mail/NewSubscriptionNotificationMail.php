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
        return new Content(
            view: 'emails.admin.new-subscription',
            with: [
                'transaction' => $this->transaction,
                'user' => $this->transaction->user,
                'plan' => $this->transaction->plan,
                'eventType' => $this->eventType,
                'verificationUrl' => route('admin.transactions.index', ['search' => $this->transaction->trx_code]),
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
        return [];
    }
}
