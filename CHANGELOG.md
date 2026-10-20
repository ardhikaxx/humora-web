# 📝 Catatan Perubahan (Changelog)

Semua perubahan penting pada proyek **HUMORA Backend & Admin Portal** dicatat secara berkala dalam dokumen ini.

Format pencatatan ini berpedoman pada [Keep a Changelog](https://keepachangelog.com/id/1.1.0/) dan menganut prinsip [Semantic Versioning (SemVer)](https://semver.org/spec/v2.0.0.html).

---

## [1.2.0] - 2026-10-20

### Ditambahkan (Added)
- **Kompresi & Konversi WebP Otomatis Sisi Klien (Client-Side)**:
  - Implementasi kompresi foto thumbnail di browser admin sebelum form diunggah via HTML5 Canvas dengan interpolasi resolusi tinggi.
  - Pengurangan ukuran gambar hingga 85–95% (3–8 MB menyusut menjadi ~150–250 KB) berformat `.webp`.
  - Integrasi DataTransfer API pada input file agar berkas WebP langsung menggantikan file mentah tanpa intervensi manual.
  - Tampilan indikator realtime perbandingan ukuran file awal vs terkompresi serta persentase penghematan storage.
- **Transcoding Video WebM Server-Side Berperforma Tinggi**:
  - Implementasi otomatis transcoding berkas video ke format WebM dengan codec VP9 & Opus pada `FileStorageService.php`.
  - Pemanfaatan konfigurasi `-row-mt 1 -deadline good -cpu-used 4 -map 0:v:0 -map 0:a?` untuk eksekusi multi-threading CPU yang cepat dan tahan terhadap klip video tanpa audio.
  - Preservasi resolusi visual 100% tanpa penurunan dimensi visual video.
- **Unit Testing Ekstensi Media**:
  - Penambahan unit test `test_video_is_compressed_and_stored_as_webm` pada `FileStorageServiceTest.php`.
- **Feedback Interaktif Pemrosesan Media**:
  - Penambahan modal loading interaktif SweetAlert2 saat form episode video disimpan guna menginformasikan proses transcoding video ke WebM.
- **Dukungan Donasi & QRIS**:
  - Penambahan kanal apresiasi pengembang berbasis QRIS terintegrasi pada dokumentasi resmi.

### Diperbaiki (Fixed)
- Penanganan path executable FFmpeg pada environment Windows (`AppData\Local\Microsoft\WinGet\Links\ffmpeg.exe`).
- Validasi form episode agar menerima format video master MP4, MOV, dan WebM.

---

## [1.1.0] - 2026-10-18

### Ditambahkan (Added)
- **RESTful API v1 Streaming Video**:
  - Endpoint `GET /api/v1/files/stream/{episodeId}` dengan dukungan penuh HTTP Byte-Range Requests (Status 206 Partial Content) untuk seek instan di aplikasi mobile.
- **Manajemen Transaksi Langganan VIP**:
  - Endpoint upload bukti pembayaran dan endpoint approval/rejection transaksi oleh role finance admin.
- **Pelacakan Analitik Realtime**:
  - Endpoint `POST /api/v1/videos/track-view` untuk pelacakan durasi tonton dan akumulasi tayangan serial.
- **Kebijakan Akses Otomatis**:
  - Episode 1 & 2 otomatis gratis; Episode 3+ otomatis terkunci untuk pelanggan VIP.

---

## [1.0.0] - 2026-10-15

### Ditambahkan (Added)
- Rilis inisial arsitektur backend Laravel 12.x untuk platform streaming HUMORA.
- Manajemen autentikasi berbasis Laravel Sanctum untuk aplikasi mobile dan session RBAC untuk admin portal.
- Skema database relasional: `users`, `series`, `episodes`, `categories`, `transactions`, `subscriptions`, `likes`, `watch_histories`, dan `notifications`.
- Antarmuka web admin dengan desain responsif berbasis Bootstrap 5.3, Lucide Icons, dan Chart.js.

---

**Yanuar Ardhika Rahmadhani Ubaidillah (@ardhikaxx)**
