<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Langganan Baru - HUMORA</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #F8FAFC;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #1E293B;
            -webkit-font-smoothing: antialiased;
        }
        table {
            border-collapse: collapse;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #FFFFFF;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #E2E8F0;
        }
        .btn-action {
            display: inline-block;
            background: linear-gradient(135deg, #43135C 0%, #601880 100%);
            background-color: #43135C;
            color: #FFFFFF !important;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            padding: 14px 28px;
            border-radius: 10px;
            text-align: center;
            letter-spacing: 0.02em;
        }
        .info-table td {
            padding: 10px 14px;
            font-size: 14px;
            border-bottom: 1px solid #F1F5F9;
        }
        .info-table tr:last-child td {
            border-bottom: none;
        }
    </style>
</head>
<body style="margin: 0; padding: 24px 12px; background-color: #F8FAFC;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td align="center">
                <table class="container" role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; background-color: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);">
                    
                    <!-- Header Banner -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #43135C 0%, #2A093B 100%); padding: 32px 28px; text-align: center;">
                            <div style="display: inline-block; background-color: rgba(255, 255, 255, 0.12); padding: 4px 14px; border-radius: 20px; font-size: 11px; font-weight: 800; color: #FFC107; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 12px;">
                                HUMORA PORTAL ADMIN
                            </div>
                            <h1 style="margin: 0; color: #FFFFFF; font-size: 22px; font-weight: 800; letter-spacing: -0.02em;">
                                Pemberitahuan Langganan Baru
                            </h1>
                            <p style="margin: 6px 0 0 0; color: #E9D5FF; font-size: 13px;">
                                {{ $eventType === 'proof_uploaded' ? 'Pengguna telah mengunggah bukti pembayaran dan siap diverifikasi' : 'Pengguna baru saja membuat pesanan langganan paket VIP' }}
                            </p>
                        </td>
                    </tr>

                    <!-- Event Status Pill -->
                    <tr>
                        <td style="padding: 24px 28px 12px 28px;">
                            @if ($eventType === 'proof_uploaded')
                            <div style="background-color: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 10px; padding: 12px 16px; text-align: center;">
                                <span style="color: #047857; font-weight: 700; font-size: 13px;">
                                    &#10003; Bukti Transfer Diunggah &mdash; Menunggu Persetujuan Admin
                                </span>
                            </div>
                            @else
                            <div style="background-color: #FEF3C7; border: 1px solid #FDE68A; border-radius: 10px; padding: 12px 16px; text-align: center;">
                                <span style="color: #B45309; font-weight: 700; font-size: 13px;">
                                    &#9203; Pesanan Baru Dibuat &mdash; Menunggu Pembayaran
                                </span>
                            </div>
                            @endif
                        </td>
                    </tr>

                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 12px 28px 24px 28px;">
                            <p style="margin: 0 0 16px 0; font-size: 14px; color: #475569; line-height: 1.5;">
                                Halo Tim Administrator HUMORA, ada permintaan langganan VIP baru dari pengguna. Berikut adalah rincian lengkap transaksinya:
                            </p>

                            <!-- Data Summary Table -->
                            <table class="info-table" role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #F8FAFC; border-radius: 12px; border: 1px solid #E2E8F0; overflow: hidden; margin-bottom: 24px;">
                                <tr>
                                    <td width="38%" style="color: #64748B; font-weight: 600;">Kode Transaksi</td>
                                    <td width="62%" style="font-family: 'Courier New', Courier, monospace; font-weight: 700; color: #43135C; font-size: 15px;">
                                        {{ $transaction->trx_code }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #64748B; font-weight: 600;">Nama Pemesan</td>
                                    <td style="color: #0F172A; font-weight: 700;">
                                        {{ $user->name ?? 'Pengguna' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #64748B; font-weight: 600;">Email Akun</td>
                                    <td style="color: #0F172A;">
                                        <a href="mailto:{{ $user->email ?? '' }}" style="color: #43135C; text-decoration: none; font-weight: 600;">
                                            {{ $user->email ?? '-' }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #64748B; font-weight: 600;">No. HP / WA</td>
                                    <td style="color: #0F172A;">
                                        {{ $user->phone ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #64748B; font-weight: 600;">Paket Langganan</td>
                                    <td style="color: #0F172A; font-weight: 700;">
                                        <span style="display: inline-block; background-color: #F3E8FF; color: #6D28D9; border: 1px solid #E9D5FF; border-radius: 6px; padding: 2px 8px; font-size: 12px;">
                                            {{ $plan->name ?? 'Paket VIP' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #64748B; font-weight: 600;">Nominal Pembayaran</td>
                                    <td style="color: #0F172A; font-weight: 800; font-size: 16px;">
                                        Rp {{ number_format((float) ($transaction->amount ?? 0), 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #64748B; font-weight: 600;">Metode Pembayaran</td>
                                    <td style="color: #0F172A; font-weight: 600; text-transform: uppercase;">
                                        {{ $transaction->payment_method ?? 'Manual Transfer' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color: #64748B; font-weight: 600;">Waktu Pesanan</td>
                                    <td style="color: #64748B; font-size: 13px;">
                                        {{ optional($transaction->created_at)->format('d M Y, H:i') }} WIB
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 30px 0 24px 0;">
                                <a href="{{ $verificationUrl }}" class="btn-action" style="display: inline-block; background-color: #43135C; color: #FFFFFF !important; text-decoration: none; font-weight: 700; font-size: 15px; padding: 14px 28px; border-radius: 10px; box-shadow: 0 4px 12px rgba(67, 19, 92, 0.25);">
                                    Lihat &amp; Verifikasi Transaksi &rarr;
                                </a>
                            </div>

                            <p style="margin: 0; font-size: 12px; color: #94A3B8; text-align: center; line-height: 1.5;">
                                Jika tombol di atas tidak dapat diklik, buka tautan berikut di browser Anda:<br>
                                <a href="{{ $verificationUrl }}" style="color: #43135C; word-break: break-all; text-decoration: underline;">
                                    {{ $verificationUrl }}
                                </a>
                            </p>
                        </td>
                    </tr>

                    <!-- Instructions / Security Note -->
                    <tr>
                        <td style="background-color: #FAF5FF; border-top: 1px dashed #E9D5FF; padding: 18px 28px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td style="font-size: 12px; color: #6D28D9; line-height: 1.5;">
                                        <strong>Catatan Akses Admin:</strong><br>
                                        &bull; Email ini diteruskan serentak ke seluruh akun administrator aktif HUMORA.<br>
                                        &bull; Jika Anda belum masuk (*login*) di peramban, sistem akan meminta login terlebih dahulu menggunakan akun admin masing-masing, kemudian otomatis membuka transaksi terkait untuk disetujui.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #F1F5F9; padding: 18px 28px; text-align: center; border-top: 1px solid #E2E8F0;">
                            <p style="margin: 0; font-size: 11px; color: #64748B; line-height: 1.5;">
                                &copy; {{ date('Y') }} PT Global Mobile Content &bull; <strong>HUMORA Video Platform</strong><br>
                                Pesan ini dihasilkan otomatis oleh sistem notifikasi backend. Harap tidak membalas email ini secara langsung.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
