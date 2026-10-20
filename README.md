# 🎬 HUMORA — Backend API & Web Admin Portal

<p align="center">
  <img src="./public/images/logo-humora.png" alt="HUMORA Logo" width="130"/>
</p>

<p align="center">
  <strong>Platform Streaming Serial Sketsa Komedi Vertikal Pendek (~2 Menit)</strong><br>
  <em>"scroll, laugh, repeat"</em>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.4+"/>
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12.x"/>
  <img src="https://img.shields.io/badge/FFmpeg-VP9%20%2F%20Opus-007808?style=for-the-badge&logo=ffmpeg&logoColor=white" alt="FFmpeg"/>
  <img src="https://img.shields.io/badge/WebP-Auto--Compress-38B2AC?style=for-the-badge" alt="WebP"/>
  <img src="https://img.shields.io/badge/License-Proprietary-43135C?style=for-the-badge" alt="License"/>
</p>

---

## 📖 Tentang HUMORA

**HUMORA** adalah ekosistem hiburan digital modern yang berfokus pada serial sketsa komedi vertikal pendek dengan durasi terstandarisasi sekitar 2 menit per episode. Dirancang khusus untuk audiens generasi muda dan pengguna mobile yang menginginkan hiburan humor cepat, berkualitas, dan adiktif di sela-sela rutinitas harian.

Repositori ini merupakan **Backend RESTful API (v1)** sekaligus **Portal Web Admin Manajemen Konten & Finansial**. Dibangun di atas fondasi **Laravel** modern berkecepatan tinggi dengan integrasi kompresi media otomatis (*WebP & WebM*), manajemen langganan VIP, pelacakan analitik tontonan *real-time*, serta *streaming* video berbasis HTTP Range.

---

## ⚡ Fitur Utama Sistem

### 1. 🎭 Manajemen Konten Serial & Episode Komedi
* **Pengorganisasian Judul Serial**: Pengelompokan episode ke dalam judul besar serial (series), dilengkapi sinopsis tematik, tahun rilis, genre/kategori komedi, dan lencana unggulan (*spotlight hero*).
* **Episode Sketsa Pendek (~2 Menit)**: Input nomor urut episode, sub-judul cerita, durasi terformat menit & detik, serta preset cepat durasi HUMORA (~2m).
* **Aturan Akses Konten (Freemium Paywall)**:
  * **Episode 1 & 2**: Otomatis berstatus *Free Preview* (dapat ditonton bebas oleh semua pengguna/tamu).
  * **Episode 3 ke atas**: Otomatis terkunci khusus pengguna dengan paket langganan **Premium VIP** aktif.

### 2. 🗜️ Otomatisasi Kompresi Media & Efisiensi Server
* **Kompresi & Konversi Foto ke WebP (Client-Side & Server)**:
  * Setiap admin memilih foto thumbnail sampul di browser, sistem secara otomatis mengompresi dan mengonversi format ke **WebP (.webp)** melalui HTML5 Canvas resolusi tinggi.
  * Menghemat kapasitas disk hosting dan bandwidth unggah hingga **85% – 95%** (file 5–10 MB menyusut menjadi ~150–250 KB).
* **Transcoding Video ke WebM (VP9 & Opus)**:
  * Video master yang diunggah otomatis diproses melalui **FFmpeg** dengan konfigurasi *multi-threading* CPU (`-c:v libvpx-vp9 -crf 32 -b:v 0 -row-mt 1 -c:a libopus`).
  * **Resolusi asli video terjaga 100% tanpa penurunan dimensi**, menghasilkan ukuran file yang sangat padat (~6–12 MB untuk video 2 menit) sehingga bebas *buffering* dan hemat kuota pengguna mobile.
* **Penyimpanan Fisik Aman**: Semua berkas media disimpan langsung ke direktori fisik terkelola tanpa ketergantungan pada *symbolic link* (*symlink*).

### 3. 📡 Streaming Video Responsif (HTTP Range Requests)
* **Dukungan Byte-Range (206 Partial Content)**: Mendukung pemutaran instan, pelompatan durasi (*seeking*), dan *buffering* efisien pada pemutar video aplikasi mobile Flutter.
* **Streaming Aman**: File video terlindungi dan disalurkan melalui controller streaming terverifikasi.

### 4. 💳 Manajemen Langganan & Transaksi VIP
* **Katalog Paket Langganan**: Konfigurasi paket VIP (1 Bulan, 3 Bulan, 1 Tahun) beserta nominal harga, badge terpopuler/terhemat, dan benefit akses tanpa batas.
* **Verifikasi Pembayaran Manual**:
  * Pengguna mengunggah bukti transfer manual (struk/screenshot m-Banking).
  * Panel verifikasi transaksi bagi admin keuangan untuk menyetujui (*approve*) atau menolak (*reject*) dengan alasan yang transparan.
  * Otomatis mengaktifkan masa berlaku VIP pengguna begitu pembayaran disetujui.

### 5. 📊 Dasbor Analitik & Real-Time Viewer Tracking
* **Pelacakan Tontonan Realtime**: Endpoint pencatatan tayangan (*views*) dan akumulasi jam tonton (*watch time*) yang aktif memantau retensi audiens.
* **Statistik Performa**: Metrik total penayangan, tingkat penyelesaian video (*completion rate*), konversi pelanggan VIP, dan performa video terpopuler.

---

## 💻 Prasyarat Sistem

Sebelum menjalankan proyek, pastikan lingkungan server memenuhi persyaratan berikut:

| Komponen | Versi Minimal | Keterangan |
| :--- | :--- | :--- |
| **PHP** | `v8.4.0` atau lebih tinggi | Ekstensi: `gd`, `pdo_mysql`, `mbstring`, `fileinfo`, `openssl` |
| **Composer** | `v2.7.0` atau lebih tinggi | Manajer dependensi PHP |
| **Database** | MySQL `8.0+` / MariaDB `10.6+` | Penyimpanan data relasional |
| **FFmpeg** | `v6.0+` (disarankan `v9.0+`) | Wajib terpasang pada PATH sistem untuk transcoding WebM |
| **Node.js** | `v20.0+` *(Opsional)* | Untuk kompilasi aset frontend jika diperlukan |

---

## 🚀 Panduan Instalasi & Menjalankan Proyek

### 1. Kloning Repositori
```bash
git clone https://github.com/ardhikaxx/humora-web.git
cd humora-web
```

### 2. Instalasi Dependensi PHP
```bash
composer install --optimize-autoloader --no-dev
```
*(Untuk lingkungan pengembangan lokal, jalankan `composer install`)*

### 3. Konfigurasi Lingkungan (.env)
Salin berkas konfigurasi default dan sesuaikan kredensial database Anda:
```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan parameter database di dalam berkas `.env`:
```ini
APP_NAME=HUMORA
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost/humora-web

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=humora_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi & Seed Database
Jalankan migrasi database beserta data awal katalog:
```bash
php artisan migrate --seed
```

### 5. Menyiapkan Direktori Penyimpanan Berkas
Pastikan folder unggahan media telah tersedia dengan hak akses tulis (*write permissions*):
```bash
mkdir -p storage/uploads/thumbnails storage/uploads/videos storage/uploads/proofs storage/uploads/system
```

### 6. Menjalankan Server Lokal
```bash
php artisan serve
```
Akses portal web admin di browser melalui: `http://127.0.0.1:8000/admin`

---

## 🧪 Pengujian Sistem (Automated Tests)

Sistem telah dilengkapi dengan test suite lengkap menggunakan framework **Pest PHP**:

```bash
# Menjalankan seluruh rangkaian tes
php artisan test

# Menjalankan tes spesifik untuk konversi media & storage
php artisan test --filter FileStorageServiceTest
```

---

## 💖 Dukungan & Donasi

Jika proyek **HUMORA** ini bermanfaat bagi Anda, menginspirasi, atau membantu percepatan pengembangan karya digital Anda, Anda dapat memberikan apresiasi dan traktiran kopi (donasi sukarela) kepada pengembang melalui pemindaian kode **QRIS** di bawah ini:

<p align="center">
  <img src="./qris.png" alt="QRIS Donasi Pengembang HUMORA" width="280"/>
</p>

> *Donasi sepenuhnya bersifat sukarela dan tidak mengikat. Seluruh fitur sistem tetap dapat digunakan secara optimal tanpa donasi.*

---

## 📄 Dokumen Pendukung Lainnya

Untuk informasi lebih mendalam mengenai kebijakan dan pemeliharaan proyek, silakan baca:
* [`CHANGELOG.md`](./CHANGELOG.md) — Catatan riwayat pembaruan dan rilis versi.
* [`CITATION.md`](./CITATION.md) — Format sitasi akademik karya (APA, BibTeX, IEEE, MLA).
* [`CODE_OF_CONDUCT.md`](./CODE_OF_CONDUCT.md) — Standar etika dan pedoman komunitas.
* [`CONTRIBUTING.md`](./CONTRIBUTING.md) — Panduan kontribusi kode dan standar teknis.
* [`SECURITY.md`](./SECURITY.md) — Kebijakan pelaporan kerentanan dan privasi data.
* [`SUPPORT.md`](./SUPPORT.md) — Layanan konsultasi, bantuan teknis, dan dukungan donasi.

---

## 👨‍💻 Pengembang & Hak Cipta

Dirancang dan dikembangkan dengan penuh dedikasi oleh:  
**[Yanuar Ardhika Rahmadhani Ubaidillah (@ardhikaxx)](https://github.com/ardhikaxx)**  
*Lead Software Architect & Maintainer*  
Email Resmi: `ardhikayanuar58@gmail.com`

Proyek ini dilindungi oleh lisensi **Proprietary Software License (All Rights Reserved)**.
