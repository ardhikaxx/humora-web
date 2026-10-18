@extends('layouts.admin')

@section('title', 'Tambah Video Baru - HUMORA')

@section('content')
<!-- Breadcrumbs -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i class="bi bi-chevron-right mx-1" style="font-size: 0.65rem;"></i>
        <a href="{{ url('/admin/videos') }}" class="text-decoration-none text-muted">Kelola Video</a> <i class="bi bi-chevron-right mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-dark fw-semibold">Tambah Video</span>
    </small>
</div>

<!-- Header & Action Buttons -->
<form id="formCreateVideo" action="{{ url('/admin/videos') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="page-title mb-1">Tambah Video Baru</h1>
            <p class="page-subtitle mb-0">Tambahkan video pendek (~+2 menit) ke dalam judul besar konten pilihan. Thumbnail otomatis mengikuti sampul judul besar.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ url('/admin/videos') }}" class="btn btn-humora-secondary">
                <i class="bi bi-arrow-left"></i>
                <span>Batal</span>
            </a>
            <button type="submit" name="status" value="draft" class="btn btn-humora-secondary">
                <span>Simpan sebagai Draft</span>
            </button>
            <button type="submit" name="status" value="published" class="btn btn-humora-primary">
                <i class="bi bi-floppy"></i>
                <span>Simpan Video</span>
            </button>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-12 col-lg-8">
            <!-- 1. PILIH JUDUL KONTEN (JUDUL BESAR) -->
            <div class="humora-card mb-4">
                <div class="humora-card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">1. PILIH JUDUL KONTEN (JUDUL BESAR)</h5>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-pill" style="background-color: #F3E8FF; color: #6B21A8; font-size: 0.72rem;">4 Video Terdaftar</span>
                            <a href="{{ url('/admin/series/create') }}" class="text-decoration-none fw-semibold" style="color: #43135C; font-size: 0.78rem;">+ Buat Judul Baru</a>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="seriesSelect" class="form-label-custom">Judul Besar <span class="text-danger">*</span></label>
                        <select id="seriesSelect" name="series_id" class="form-select-custom">
                            <option value="tipe-penumpang-taxi-online" data-img="tipe-penumpang-taxi-online.jpg" data-desc="Suka duka dan serba-serbi kocak seorang driver taksi online yang tabah saat menghadapi beragam tingkah laku unik penumpang." data-genres="Komedi • Sketsa • Slice of Life" data-ep="5" selected>Tipe Penumpang Taxi Online (Komedi, Sketsa, Slice of Life)</option>
                            <option value="cara-deketin-cewek" data-img="cara-deketin-cewek.jpg" data-desc="Eksperimen kocak dan trik-trik PDKT absurd cowok pemalu demi mendapatkan pujaan hati." data-genres="Romantis • Komedi • Sketsa" data-ep="5">Cara Deketin Cewek (Romantis, Komedi, Sketsa)</option>
                            <option value="tipe-tipe-teman-sekantor" data-img="tipe-tipe-teman-sekantor.jpg" data-desc="Pahit getir dan dinamika kocak kehidupan para budak korporat menghadapi bos killer dan kawan kantor unik." data-genres="Komedi • Workplace • Sketsa" data-ep="5">Tipe-Tipe Teman Sekantor (Komedi, Workplace, Sketsa)</option>
                            <option value="drama-warga-grup-rt" data-img="drama-warga-grup-rt.jpg" data-desc="Perdebatan sengit dan hebohnya obrolan grup WhatsApp rukun tetangga soal iuran sampah dan arisan komplek." data-genres="Komedi • Slice of Life • Sketsa" data-ep="5">Drama Warga Grup RT (Komedi, Slice of Life, Sketsa)</option>
                            <option value="momen-canggung-kencan-pertama" data-img="momen-canggung-kencan-pertama.jpg" data-desc="Kumpulan momen salah tingkah, lupa nama kencan, dan insiden tak terduga saat first date." data-genres="Romantis • Komedi • Sketsa" data-ep="5">Momen Canggung Kencan Pertama (Romantis, Komedi, Sketsa)</option>
                            <option value="tipe-pembeli-di-toko-kelontong" data-img="tipe-pembeli-di-toko-kelontong.jpg" data-desc="Toko kelontong Madura buka 24 jam jadi saksi bisu berbagai keajaiban dan tingkah nyeleneh para pelanggan." data-genres="Komedi • Slice of Life • Sketsa" data-ep="5">Tipe Pembeli di Toko Kelontong (Komedi, Slice of Life, Sketsa)</option>
                        </select>
                    </div>

                    <!-- Selected Series Preview Card -->
                    <div class="p-3 rounded-3 bg-light border d-flex flex-column flex-sm-row align-items-sm-center gap-3">
                        <img id="selectedSeriesImg" src="{{ asset('images/thumbnails/tipe-penumpang-taxi-online.jpg') }}" alt="Series Preview" class="rounded-3 shadow-sm flex-shrink-0" style="width: 110px; height: 65px; object-fit: cover;">
                        <div>
                            <div class="fw-bold text-dark" id="selectedSeriesTitle" style="font-size: 0.88rem;">Tipe Penumpang Taxi Online</div>
                            <small class="text-muted d-block text-truncate mb-1" id="selectedSeriesDesc" style="max-width: 440px; font-size: 0.76rem;">
                                Suka duka dan serba-serbi kocak seorang driver taksi online yang tabah saat menghadapi beragam tingkah laku unik penumpang...
                            </small>
                            <div class="d-flex align-items-center gap-2" style="font-size: 0.72rem;">
                                <span class="text-success fw-bold" id="selectedSeriesNextEp"><i class="bi bi-lightbulb"></i> Nomor berikutnya: Video #5</span>
                                <span class="text-muted">&bull;</span>
                                <span class="text-muted" id="selectedSeriesGenres">Komedi &bull; Sketsa &bull; Slice of Life</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. INFORMASI & DETAIL VIDEO -->
            <div class="humora-card mb-4">
                <div class="humora-card-body p-4">
                    <h5 class="fw-bold text-dark mb-4" style="font-size: 0.95rem;">2. INFORMASI & DETAIL VIDEO</h5>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-4">
                            <label class="form-label-custom">Video Ke- <span class="text-danger">*</span></label>
                            <input type="number" id="episodeNumberInput" name="episode_number" class="form-control-custom" value="5" required>
                            <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Terisi otomatis berdasarkan jumlah video pada judul ini.</small>
                        </div>
                        <div class="col-12 col-md-8">
                            <label class="form-label-custom">Judul / Sub-Topik Video <span class="text-danger">*</span></label>
                            <input type="text" id="videoTitleInput" name="title" class="form-control-custom" placeholder="Misal: Tipe Bawel / Tipe Sensi / Pake Hadiah" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Deskripsi Cerita / Sketsa Video</label>
                        <textarea rows="3" name="description" class="form-control-custom" placeholder="Ceritakan adegan lucu atau momen unik yang terjadi di video ini..."></textarea>
                    </div>

                    <div class="row g-3 align-items-center">
                        <div class="col-12 col-md-7">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label-custom mb-0">Durasi Video (~+2 Menit)</label>
                                <small class="text-purple fw-semibold d-flex align-items-center gap-1" style="color: #7C3AED; font-size: 0.72rem;">
                                    <i class="bi bi-clock"></i> Standar HUMORA ~2m
                                </small>
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="number" name="duration_minutes" class="form-control-custom" value="2" id="minuteInput" min="0" max="59">
                                        <span class="input-group-text">Menit</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="number" name="duration_seconds" class="form-control-custom" value="0" id="secondInput" min="0" max="59">
                                        <span class="input-group-text">Detik</span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-1 mt-2">
                                <small class="text-muted me-1 align-self-center" style="font-size: 0.72rem;">Preset Cepat:</small>
                                <button type="button" class="preset-btn" data-min="1" data-sec="55">1m 55s</button>
                                <button type="button" class="preset-btn" data-min="1" data-sec="58">1m 58s</button>
                                <button type="button" class="preset-btn active" data-min="2" data-sec="0">2m 00s</button>
                                <button type="button" class="preset-btn" data-min="2" data-sec="5">2m 05s</button>
                                <button type="button" class="preset-btn" data-min="2" data-sec="14">2m 14s</button>
                                <button type="button" class="preset-btn" data-min="2" data-sec="20">2m 20s</button>
                            </div>
                        </div>

                        <div class="col-12 col-md-5">
                            <label class="form-label-custom">Tanggal Rilis Tayang</label>
                            <input type="text" name="release_date" class="form-control-custom" value="16 Feb 2026">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. UNGGAH BERKAS VIDEO -->
            <div class="humora-card mb-0">
                <div class="humora-card-body p-4">
                    <h5 class="fw-bold text-dark mb-3" style="font-size: 0.95rem;">3. UNGGAH BERKAS VIDEO</h5>
                    <label class="form-label-custom mb-2">File Video (MP4 / WEBM)</label>
                    <div class="humora-dropzone">
                        <div class="humora-dropzone-icon">
                            <i class="bi bi-cloud-arrow-up"></i>
                        </div>
                        <div class="fw-bold text-dark mb-1" style="font-size: 0.88rem;">
                            Tarik file ke sini, atau <span class="text-decoration-underline" style="color: #43135C;">pilih dari komputer</span>
                        </div>
                        <small class="text-muted" style="font-size: 0.76rem;">Unggah file video master (MP4/WEBM, maksimal 200MB)</small>
                        <input type="file" name="video_file" class="d-none" id="videoUpload" accept="video/mp4,video/webm">
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Options & Progress -->
        <div class="col-12 col-lg-4">
            <!-- Status Akses Pengguna -->
            <div class="humora-card mb-4">
                <div class="humora-card-body p-3">
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.82rem; text-transform: uppercase;">Status Akses Pengguna</h6>
                    <small class="text-muted d-block mb-3" style="font-size: 0.75rem;">Model HUMORA: Video 1 & 2 gratis ditonton, Video 3+ khusus pelanggan.</small>

                    <!-- Choice 1: Free -->
                    <div class="p-3 rounded-3 border mb-2 access-choice-box" id="boxAccessFree" style="background-color: #FAFBFD;">
                        <div class="form-check d-flex justify-content-between align-items-start">
                            <div>
                                <label class="form-check-label fw-bold text-dark d-flex align-items-center gap-1 mb-1" for="accessFree" style="font-size: 0.84rem; cursor: pointer;">
                                    <i class="bi bi-stars text-success"></i> Free Preview
                                </label>
                                <small class="text-muted d-block" style="font-size: 0.74rem;">Dapat ditonton secara gratis oleh seluruh pengguna aplikasi tanpa perlu berlangganan.</small>
                            </div>
                            <input class="form-check-input" type="radio" name="access_type" id="accessFree" value="free" style="cursor: pointer;">
                        </div>
                    </div>

                    <!-- Choice 2: Premium -->
                    <div class="p-3 rounded-3 border mb-3 access-choice-box" id="boxAccessPremium" style="background-color: #FAF5FF; border-color: #D8B4FE !important;">
                        <div class="form-check d-flex justify-content-between align-items-start">
                            <div>
                                <label class="form-check-label fw-bold text-purple d-flex align-items-center gap-1 mb-1" for="accessPremium" style="color: #6B21A8; font-size: 0.84rem; cursor: pointer;">
                                    <i class="bi bi-gem"></i> Premium (Terkunci)
                                </label>
                                <small class="text-muted d-block" style="font-size: 0.74rem;">Video terkunci dengan ikon gembok. Memerlukan akun berlangganan aktif untuk menonton.</small>
                            </div>
                            <input class="form-check-input" type="radio" name="access_type" id="accessPremium" value="premium" checked style="cursor: pointer; accent-color: #43135C;">
                        </div>
                    </div>

                    <!-- Warning note -->
                    <div class="p-2 rounded-3 border d-flex align-items-center gap-2" style="background-color: #FFFBEB; border-color: #FDE68A !important;">
                        <i class="bi bi-exclamation-triangle text-warning flex-shrink-0" style="font-size: 1rem;"></i>
                        <small class="text-muted" style="font-size: 0.72rem; line-height: 1.3;">Pengguna tanpa langganan akan diarahkan ke pop-up paket langganan jika memutar video ini.</small>
                    </div>
                </div>
            </div>

            <!-- Status Publikasi -->
            <div class="humora-card mb-4">
                <div class="humora-card-body p-3">
                    <h6 class="fw-bold text-dark mb-3" style="font-size: 0.82rem; text-transform: uppercase;">Status Publikasi</h6>
                    <div class="btn-group w-100 mb-2" role="group">
                        <input type="radio" class="btn-check" name="pub_status" id="pub1" value="published" checked>
                        <label class="btn btn-outline-info text-dark fw-semibold py-2" for="pub1" style="background-color: #E0F2FE; border-color: #BAE6FD; font-size: 0.82rem;">Terpublikasi</label>

                        <input type="radio" class="btn-check" name="pub_status" id="pub2" value="draft">
                        <label class="btn btn-outline-secondary py-2" for="pub2" style="font-size: 0.82rem;">Draft</label>
                    </div>
                    <small class="text-muted d-block" style="font-size: 0.72rem;">Hanya video berstatus "Terpublikasi" yang dapat ditonton pengguna aplikasi mobile.</small>
                </div>
            </div>

            <!-- Kelengkapan Video Progress Checklist -->
            <div class="humora-card mb-0">
                <div class="humora-card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.82rem; text-transform: uppercase;">Kelengkapan Video</h6>
                        <span class="fw-bold text-success" id="progressPercent" style="font-size: 0.82rem;">75%</span>
                    </div>
                    <div class="progress-humora mb-3">
                        <div class="progress-humora-bar" id="progressBar" style="width: 75%;"></div>
                    </div>
                    <div class="d-flex flex-column gap-2" style="font-size: 0.76rem;">
                        <div class="d-flex align-items-center gap-2 text-dark" id="stepTitle">
                            <i class="bi bi-circle text-muted"></i>
                            <span>Judul & nomor video terisi</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 text-dark">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <span>Judul besar terpilih</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 text-dark" id="stepFile">
                            <i class="bi bi-circle text-muted"></i>
                            <span>File video siap</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 text-dark">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <span>Thumbnail 16:9 mengikuti judul besar</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const seriesSelect = document.getElementById('seriesSelect');
        const selectedSeriesImg = document.getElementById('selectedSeriesImg');
        const selectedSeriesTitle = document.getElementById('selectedSeriesTitle');
        const selectedSeriesDesc = document.getElementById('selectedSeriesDesc');
        const selectedSeriesNextEp = document.getElementById('selectedSeriesNextEp');
        const selectedSeriesGenres = document.getElementById('selectedSeriesGenres');
        const epInput = document.getElementById('episodeNumberInput');
        const videoTitleInput = document.getElementById('videoTitleInput');
        const stepTitle = document.getElementById('stepTitle');
        const stepFile = document.getElementById('stepFile');
        const progressBar = document.getElementById('progressBar');
        const progressPercent = document.getElementById('progressPercent');

        // Series select change
        seriesSelect.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            const imgName = opt.dataset.img;
            const desc = opt.dataset.desc;
            const genres = opt.dataset.genres;
            const ep = opt.dataset.ep;

            selectedSeriesImg.src = `{{ asset('images/thumbnails') }}/${imgName}`;
            selectedSeriesTitle.innerText = opt.text.split('(')[0].trim();
            selectedSeriesDesc.innerText = desc;
            selectedSeriesNextEp.innerHTML = `<i class="bi bi-lightbulb"></i> Nomor berikutnya: Video #${ep}`;
            selectedSeriesGenres.innerHTML = genres;
            epInput.value = ep;

            window.showHumoraToast(`Judul besar terpilih: ${selectedSeriesTitle.innerText}`);
        });

        // Title input progress check
        videoTitleInput.addEventListener('input', function () {
            if (this.value.trim().length > 0) {
                stepTitle.innerHTML = '<i class="bi bi-check-circle-fill text-success"></i> <span>Judul & nomor video terisi</span>';
            } else {
                stepTitle.innerHTML = '<i class="bi bi-circle text-muted"></i> <span>Judul & nomor video terisi</span>';
            }
            updateProgress();
        });

        // Video upload check
        const videoUpload = document.getElementById('videoUpload');
        if (videoUpload) {
            videoUpload.addEventListener('change', function () {
                if (this.files && this.files.length > 0) {
                    stepFile.innerHTML = '<i class="bi bi-check-circle-fill text-success"></i> <span>File video siap</span>';
                } else {
                    stepFile.innerHTML = '<i class="bi bi-circle text-muted"></i> <span>File video siap</span>';
                }
                updateProgress();
            });
        }

        function updateProgress() {
            let score = 50; // series + thumbnail ready
            if (videoTitleInput.value.trim().length > 0) score += 25;
            if (videoUpload && videoUpload.files && videoUpload.files.length > 0) score += 25;
            progressBar.style.width = score + '%';
            progressPercent.innerText = score + '%';
        }

        // Access radio switch visual
        const accessFree = document.getElementById('accessFree');
        const accessPremium = document.getElementById('accessPremium');
        const boxFree = document.getElementById('boxAccessFree');
        const boxPremium = document.getElementById('boxAccessPremium');

        function updateAccessBoxes() {
            if (accessFree.checked) {
                boxFree.style.backgroundColor = '#ECFDF5';
                boxFree.style.borderColor = '#10B981';
                boxPremium.style.backgroundColor = '#FAF5FF';
                boxPremium.style.borderColor = '#E2E8F0';
            } else {
                boxPremium.style.backgroundColor = '#FAF5FF';
                boxPremium.style.borderColor = '#D8B4FE';
                boxFree.style.backgroundColor = '#FAFBFD';
                boxFree.style.borderColor = '#E2E8F0';
            }
        }
        accessFree.addEventListener('change', updateAccessBoxes);
        accessPremium.addEventListener('change', updateAccessBoxes);

        // Form submit
        document.getElementById('formCreateVideo').addEventListener('submit', function (e) {
            e.preventDefault();
            const title = videoTitleInput.value.trim();
            if (!title) {
                window.showHumoraToast('Judul video wajib diisi!', 'danger');
                videoTitleInput.focus();
                return;
            }
            window.showHumoraToast(`Video "${title}" berhasil disimpan!`);
            setTimeout(() => {
                window.location.href = "{{ url('/admin/videos') }}";
            }, 1200);
        });
    });
</script>
@endsection
