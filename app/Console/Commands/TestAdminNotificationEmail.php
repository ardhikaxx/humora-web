<?php

namespace App\Console\Commands;

use App\Mail\NewSubscriptionNotificationMail;
use App\Models\Admin;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestAdminNotificationEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'humora:test-admin-email {email? : Alamat email penerima spesifik untuk pengujian}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uji coba pengiriman email notifikasi langganan baru ke admin via SMTP';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('====================================================');
        $this->info('  HUMORA - Uji Coba Notifikasi Email Langganan Admin');
        $this->info('====================================================');

        $mailer = config('mail.default');
        $host = config('mail.mailers.smtp.host');
        $port = config('mail.mailers.smtp.port');
        $username = config('mail.mailers.smtp.username');
        $password = config('mail.mailers.smtp.password');
        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name');

        $this->table(
            ['Parameter', 'Nilai Saat Ini'],
            [
                ['Mailer', $mailer],
                ['SMTP Host', $host ?? '-'],
                ['SMTP Port', $port ?? '-'],
                ['SMTP Username', $username ?? '-'],
                ['SMTP Password Status', empty($password) ? '<fg=yellow>KOSONG (Perlu diisi App Password di .env)</>' : '<fg=green>TERSEDIA ('.strlen((string) $password).' karakter)</>'],
                ['From Address', "{$fromName} <{$fromAddress}>"],
            ]
        );

        if ($mailer === 'smtp' && empty($password)) {
            $this->warn('Peringatan: MAIL_PASSWORD di file .env masih kosong.');
            $this->line('Silakan isi 16 digit Sandi Aplikasi (App Password) Google terlebih dahulu di file .env.');

            if (! $this->confirm('Apakah Anda ingin tetap mencoba mengirim email sekarang?', false)) {
                return Command::FAILURE;
            }
        }

        // Determine destination emails
        $specificEmail = $this->argument('email');
        if ($specificEmail) {
            $recipients = [$specificEmail];
        } else {
            $recipients = Admin::where('is_active', true)
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->pluck('email')
                ->unique()
                ->values()
                ->all();
        }

        if (empty($recipients)) {
            $this->error('Tidak ada alamat email admin yang ditemukan untuk dikirimkan notifikasi.');

            return Command::FAILURE;
        }

        $this->line('Penerima Email: <fg=cyan>'.implode(', ', $recipients).'</>');

        // Create a simulated transaction for testing
        $mockUser = User::first() ?? new User([
            'name' => 'Budi Pengujian',
            'email' => 'budi.test@example.com',
            'phone' => '081234567890',
        ]);

        $mockPlan = SubscriptionPlan::first() ?? new SubscriptionPlan([
            'name' => 'Paket Akses Mingguan (15 Video)',
            'price' => 15000,
            'duration_days' => 7,
            'video_quota' => 15,
        ]);

        $mockTransaction = new Transaction([
            'trx_code' => 'TRX-TEST-'.strtoupper(bin2hex(random_bytes(3))),
            'user_id' => $mockUser->id ?? 1,
            'plan_id' => $mockPlan->id ?? 1,
            'payment_method' => 'qris',
            'amount' => $mockPlan->price ?? 15000,
            'status' => 'pending',
            'proof_file' => 'test-proof.jpg',
            'created_at' => Carbon::now(),
        ]);

        $mockTransaction->setRelation('user', $mockUser);
        $mockTransaction->setRelation('plan', $mockPlan);

        $this->line('Mengirim pesan email uji coba...');

        try {
            foreach ($recipients as $recipient) {
                Mail::to($recipient)->send(new NewSubscriptionNotificationMail($mockTransaction, 'proof_uploaded'));
                $this->info(" [OK] Berhasil terkirim ke: {$recipient}");
            }

            $this->newLine();
            $this->info('Pengujian selesai! Silakan periksa Kotak Masuk (Inbox) atau folder Spam akun email admin.');

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->newLine();
            $this->error('Gagal mengirim email: '.$e->getMessage());
            $this->line('<fg=yellow>Tips:</> Jika menggunakan Gmail, pastikan 2FA aktif dan gunakan Sandi Aplikasi (App Password) 16 digit, bukan password akun biasa.');

            return Command::FAILURE;
        }
    }
}
