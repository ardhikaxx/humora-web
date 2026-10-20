# 🛡️ Kebijakan Keamanan (Security Policy)

Keamanan server, keandalan transmisi streaming, serta kerahasiaan data pribadi pengguna dan pelanggan VIP merupakan prioritas tertinggi dalam pengembangan platform **HUMORA**.

Sistem ini dibangun dengan mematuhi prinsip-prinsip *secure coding practices*, perlindungan dari ancaman OWASP Top 10, serta keselarasan dengan regulasi **Undang-Undang Perlindungan Data Pribadi (UU PDP No. 27 Tahun 2022)**.

---

## 📦 Versi yang Didukung Pembaruan Keamanan

Pembaruan keamanan dan perbaikan celah (*security patches*) aktif dipelihara untuk versi rilis berikut:

| Versi | Status Keamanan |
| :--- | :--- |
| **v1.2.x** | ✅ **Didukung Penuh** (*Active Security Support*) |
| **v1.1.x** | ⚠️ Patch Kritis Saja |
| < 1.1.0 | ❌ Tidak Didukung |

---

## 🔒 Lapisan Pertahanan Keamanan Bawaan

1. **Autentikasi & Otorisasi API (Laravel Sanctum)**:
   * Seluruh request mobile yang membutuhkan privasi dilindungi oleh Bearer Token Sanctum.
   * Token kedaluwarsa secara berkala dan dienkripsi di basis data.
2. **Proteksi Otorisasi Multi-Role (RBAC)**:
   * Portal admin dilindungi oleh middleware `admin.role` dengan hak akses berjenjang (`super_admin`, `content_admin`, `finance_admin`).
   * Setiap request mutasi data (POST, PUT, DELETE) memvalidasi izin peran pengguna sebelum dieksekusi.
3. **Perlindungan Injeksi & Serangan Web**:
   * **SQL Injection**: Dilindungi sepenuhnya melalui PDO Prepared Statements pada Eloquent ORM.
   * **Cross-Site Scripting (XSS)**: Seluruh output antarmuka Blade di-escape otomatis menggunakan `{{ }}`.
   * **CSRF (Cross-Site Request Forgery)**: Semua form mutasi web dilindungi oleh token CSRF bawaan Laravel.
4. **Keamanan Berkas & Sanitasi Media**:
   * Seluruh berkas upload melewati validasi MIME type ketat (`image/webp,image/jpeg,image/png` untuk foto, dan `video/mp4,video/webm,video/quicktime` untuk video).
   * Nama file diacak menggunakan hash kriptografi (`Str::random(12)`) guna mencegah eksploitasi *Path Traversal* (`../`) dan eksekusi skrip berbahaya.
   * Direktori penyimpanan berkas diisolasi di luar public web root dan dialirkan melalui controller khusus.
5. **Kepatuhan Privasi Data (UU PDP No. 27 Tahun 2022)**:
   * Password pengguna di-hash menggunakan algoritma Bcrypt dengan cost factor yang aman.
   * Data kontak dan riwayat transaksi finansial pengguna tidak pernah diekspos ke pihak ketiga tanpa enkripsi.

---

## 🚨 Pelaporan Celah Keamanan (Vulnerability Reporting)

Jika Anda menemukan celah keamanan (*vulnerability*) atau bug berisiko tinggi pada sistem HUMORA, mohon untuk **TIDAK mempublikasikannya secara terbuka** di GitHub Issues atau media sosial publik.

Silakan kirimkan laporan secara bertanggung jawab (*responsible disclosure*) ke:

* **Email Kontak Keamanan**: `ardhikayanuar58@gmail.com`
* **Subjek Email**: `[SECURITY] Laporan Kerentanan HUMORA - (Nama Celah Singkat)`

### Harap Sertakan Informasi Berikut dalam Laporan Anda:
1. Deskripsi lengkap mengenai potensi dampak celah keamanan.
2. Langkah-langkah detail untuk mereproduksi masalah (*proof of concept*).
3. Lingkungan pengujian (versi PHP, sistem operasi, browser).
4. Solusi atau saran perbaikan jika Anda telah mengidentifikasinya.

Kami berkomitmen untuk memberikan tanggapan awal dalam waktu **1x24 jam** dan segera merilis perbaikan dalam pembaruan berkala.

---

**Yanuar Ardhika Rahmadhani Ubaidillah (@ardhikaxx)**  
*Lead Software Architect & Security Maintainer*
