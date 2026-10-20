# 🤝 Panduan Kontribusi (Contributing Guidelines)

Terima kasih atas minat dan antusiasme Anda untuk berkontribusi pada pengembangan **HUMORA Backend & Admin Portal**!

Proyek ini dirancang dan dikembangkan secara terpadu oleh **Yanuar Ardhika Rahmadhani Ubaidillah (@ardhikaxx)**. Agar kualitas basis kode tetap terstandarisasi, aman, dan berkinerja tinggi, seluruh kontribusi wajib mematuhi panduan di bawah ini.

---

## 📌 Ketentuan Kepemilikan & Hak Cipta

1. **Hak Cipta Proyek**: Seluruh kode sumber, arsitektur sistem, dan aset digital pada repositori ini dilindungi hak cipta atas nama **Yanuar Ardhika Rahmadhani Ubaidillah**.
2. **Karya Orisinal**: Setiap kontributor menyatakan bahwa kode yang diajukan merupakan karya orisinalnya sendiri atau memiliki izin yang sah untuk disertakan ke dalam proyek.
3. **Pemberian Hak**: Dengan mengajukan *Pull Request* (PR), Anda menyetujui bahwa kode tersebut akan digabungkan ke dalam basis kode utama proyek di bawah naungan lisensi resmi yang berlaku.

---

## 🛠️ Standar Pengembangan Kode (Code Standards)

* **Versi Bahasa & Framework**: PHP 8.4+ dan Laravel 12.x.
* **Kepatuhan PSR**: Seluruh kode PHP wajib mematuhi standar **PSR-12** (gunakan Laravel Pint untuk memformat kode: `vendor/bin/pint`).
* **Pola Desain Arsitektur**:
  * Pisahkan logika bisnis dari Controller ke lapisan **Service** (`app/Services/`) atau **Action**.
  * Gunakan **FormRequest** untuk seluruh validasi data masuk dengan pesan validasi berbahasa Indonesia yang ramah pengguna.
  * Hindari raw SQL queries yang rentan terhadap SQL Injection; selalu gunakan **Eloquent ORM** atau **Query Builder** dengan *parameter binding*.
* **Penanganan Media & Storage**:
  * Seluruh pemrosesan gambar thumbnail wajib menggunakan utilitas WebP (kualitas ~82%).
  * Seluruh pemrosesan video master wajib melalui `FileStorageService` untuk dikonversi ke format WebM (VP9/Opus) tanpa mereduksi resolusi video.
  * Hindari membuat path symlink manual di server; gunakan helper penyimpanan terpusat.
* **Pengujian Otomatis (Testing)**:
  * Setiap penambahan fitur atau perbaikan celah wajib disertai pengujian unit/fitur menggunakan **Pest PHP**.
  * Seluruh test suite harus lulus 100% sebelum mengajukan PR:
    ```bash
    php artisan test
    ```

---

## 🌿 Alur Kerja Git (Git Workflow)

1. **Fork & Clone Repositori**:
   ```bash
   git clone https://github.com/ardhikaxx/humora-web.git
   cd humora-web
   ```
2. **Buat Branch Fitur Baru**:
   Gunakan format penamaan branch yang deskriptif:
   ```bash
   git checkout -b feature/nama-fitur-baru
   # atau
   git checkout -b fix/perbaikan-bug-tertentu
   ```
3. **Format Pesan Komit (Conventional Commits)**:
   ```text
   feat: tambah kompresi otomatis video webm vp9
   fix: perbaiki validasi durasi episode pada form video
   refactor: optimasi query pelacakan analitik realtime
   docs: perbarui dokumentasi dukungan dan panduan instalasi
   ```
4. **Verifikasi Kualitas Sebelum Push**:
   ```bash
   vendor/bin/pint --test
   php artisan test
   ```
5. **Kirimkan Pull Request (PR)**:
   Jelaskan konteks perubahan, motivasi, serta tangkapan layar/hasil pengujian yang relevan pada deskripsi PR.

---

## 💬 Kanal Pertanyaan & Diskusi

Jika Anda memiliki pertanyaan teknis seputar arsitektur atau ingin mendiskusikan ide fitur baru sebelum mulai menulis kode, silakan buka diskusi melalui:
* **GitHub Issues**: [https://github.com/ardhikaxx/humora-web/issues](https://github.com/ardhikaxx/humora-web/issues)
* **Email Resmi**: `ardhikayanuar58@gmail.com`

---

**Yanuar Ardhika Rahmadhani Ubaidillah (@ardhikaxx)**
