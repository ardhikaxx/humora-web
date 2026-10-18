@extends('layouts.admin')

@section('title', 'Edit Video: Tipe Bawel - HUMORA')

@section('content')
<!-- Breadcrumbs -->
<div class="mb-2">
    <small class="text-muted" style="font-size: 0.78rem;">
        <span>Admin</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <a href="{{ url('/admin/videos') }}" class="text-decoration-none text-muted">Kelola Video</a> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-muted">Video #1</span> <i data-lucide="chevron-right" class="mx-1" style="font-size: 0.65rem;"></i>
        <span class="text-dark fw-semibold">Edit</span>
    </small>
</div>

<!-- Header & Actions -->
<form id="formEditVideo" action="{{ url('/admin/videos/1') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="page-title mb-1">Edit Video: Tipe Bawel</h1>
            <p class="page-subtitle mb-0">Bagian dari judul besar "Tipe Penumpang Taxi Online". Thumbnail otomatis mengikuti sampul judul besar.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ url('/admin/videos') }}" class="btn btn-humora-secondary">
                <i data-lucide="arrow-left"></i>
                <span>Batal</span>
            </a>
            <button type="submit" class="btn btn-humora-primary">
                <i data-lucide="save"></i>
                <span>Simpan Video</span>
            </button>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-12 col-lg-8">
            <!-- INFORMASI VIDEO -->
            <div class="humora-card mb-4">
                <div class="humora-card-body p-4">
                    <h5 class="fw-bold text-dark mb-4" style="font-size: 0.95rem;">INFORMASI VIDEO</h5>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-4">
                            <label class="form-label-custom">Nomor Video</label>
                            <input type="number" name="episode_number" class="form-control-custom" value="1" required>
                            <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Terisi otomatis berdasarkan jumlah video pada judul ini.</small>
                        </div>
                        <div class="col-12 col-md-8">
                            <label class="form-label-custom">Judul / Sub-Topik Video</label>
                            <input type="text" id="editVideoTitle" name="title" class="form-control-custom" value="Tipe Bawel" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Deskripsi Episode</label>
                        <textarea rows="3" name="description" class="form-control-custom">Baru masuk pintu mobil langsung curhat tanpa henti, cerita dari silsilah keluarga besar sampai resep rahasia opor ayam neneknya.</textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label-custom">Durasi Video</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="number" name="duration_minutes" class="form-control-custom" value="2" min="0" max="59">
                                        <span class="input-group-text">m</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="number" name="duration_seconds" class="form-control-custom" value="14" min="0" max="59">
                                        <span class="input-group-text">s</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label-custom">Tanggal Rilis</label>
                            <input type="text" name="release_date" class="form-control-custom" value="10 Feb 2026">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ASET VIDEO -->
            <div class="humora-card mb-0">
                <div class="humora-card-body p-4">
                    <h5 class="fw-bold text-dark mb-3" style="font-size: 0.95rem;">ASET VIDEO</h5>
                    <label class="form-label-custom mb-2">Video File</label>
                    <div id="existingVideoAsset" class="rounded-3 p-3 position-relative" style="background-color: #FAF8FD; border: 2px dashed #A7F3D0;">
                        <div class="d-flex align-items-center justify-content-between p-3 bg-white rounded-3 border">
                            <div class="d-flex align-items-center gap-3">
                                <div class="stat-icon-wrap stat-icon-green" style="width: 40px; height: 40px;">
                                    <i data-lucide="file-video" class="fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 0.88rem;">ForBiggerBlazes.mp4</div>
                                    <small class="text-muted" style="font-size: 0.74rem;">Aset master video (1080p • 24.2 MB)</small>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2" id="btnReplaceVideoAsset" title="Ganti video">
                                <i data-lucide="rotate-cw" class="me-1"></i> Ganti
                            </button>
                        </div>
                    </div>

                    <div class="humora-dropzone d-none mt-3" id="editVideoDropzone">
                        <div class="humora-dropzone-icon">
                            <i data-lucide="cloud-upload"></i>
                        </div>
                        <div class="fw-bold text-dark mb-1" style="font-size: 0.88rem;">
                            Tarik file video baru ke sini, atau <span class="text-decoration-underline" style="color: #43135C;">pilih dari komputer</span>
                        </div>
                        <small class="text-muted" style="font-size: 0.76rem;">Format MP4 atau WEBM master video</small>
                        <input type="file" name="video_file" class="d-none" id="editVideoUpload" accept="video/mp4,video/webm">
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Akses & Publikasi -->
        <div class="col-12 col-lg-4">
            <div class="humora-card mb-0">
                <div class="humora-card-body p-3">
                    <h6 class="fw-bold text-dark mb-3" style="font-size: 0.82rem; text-transform: uppercase;">Akses & Publikasi</h6>

                    <label class="form-label-custom mb-2">Tipe Akses Pengguna</label>
                    <!-- Choice 1: Free (checked) -->
                    <div class="p-3 rounded-3 border mb-2" id="boxAccessFreeEdit" style="background-color: #ECFDF5; border-color: #10B981 !important;">
                        <div class="form-check d-flex justify-content-between align-items-start">
                            <div>
                                <label class="form-check-label fw-bold text-success d-flex align-items-center gap-1 mb-1" for="accessFreeEdit" style="font-size: 0.84rem; cursor: pointer;">
                                    <i data-lucide="sparkles"></i> Free Preview
                                </label>
                                <small class="text-muted d-block" style="font-size: 0.74rem;">Dapat ditonton tanpa berlangganan.</small>
                            </div>
                            <input class="form-check-input" type="radio" name="access_type" id="accessFreeEdit" value="free" checked style="cursor: pointer; accent-color: #10B981;">
                        </div>
                    </div>

                    <!-- Choice 2: Premium -->
                    <div class="p-3 rounded-3 border mb-4" id="boxAccessPremiumEdit" style="background-color: #FAF5FF; border-color: #E9D5FF !important;">
                        <div class="form-check d-flex justify-content-between align-items-start">
                            <div>
                                <label class="form-check-label fw-bold text-purple d-flex align-items-center gap-1 mb-1" for="accessPremiumEdit" style="color: #6B21A8; font-size: 0.84rem; cursor: pointer;">
                                    <i data-lucide="gem"></i> Premium
                                </label>
                                <small class="text-muted d-block" style="font-size: 0.74rem;">Hanya untuk subscriber aktif.</small>
                            </div>
                            <input class="form-check-input" type="radio" name="access_type" id="accessPremiumEdit" value="premium" style="cursor: pointer;">
                        </div>
                    </div>

                    <label class="form-label-custom mb-2">Status Publikasi</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="pub_status_edit" id="pubEdit1" value="published" checked>
                        <label class="btn btn-outline-info text-dark fw-semibold py-2" for="pubEdit1" style="background-color: #E0F2FE; border-color: #BAE6FD; font-size: 0.82rem;">Terpublikasi</label>

                        <input type="radio" class="btn-check" name="pub_status_edit" id="pubEdit2" value="draft">
                        <label class="btn btn-outline-secondary py-2" for="pubEdit2" style="font-size: 0.82rem;">Draft</label>
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
        const btnReplace = document.getElementById('btnReplaceVideoAsset');
        const existingAsset = document.getElementById('existingVideoAsset');
        const editDropzone = document.getElementById('editVideoDropzone');
        const editUpload = document.getElementById('editVideoUpload');

        if (btnReplace && editDropzone) {
            btnReplace.addEventListener('click', function () {
                existingAsset.classList.add('d-none');
                editDropzone.classList.remove('d-none');
                editUpload.click();
            });
        }

        const accessFreeEdit = document.getElementById('accessFreeEdit');
        const accessPremiumEdit = document.getElementById('accessPremiumEdit');
        const boxFree = document.getElementById('boxAccessFreeEdit');
        const boxPremium = document.getElementById('boxAccessPremiumEdit');

        function updateAccess() {
            if (accessFreeEdit.checked) {
                boxFree.style.backgroundColor = '#ECFDF5';
                boxFree.style.borderColor = '#10B981';
                boxPremium.style.backgroundColor = '#FAF5FF';
                boxPremium.style.borderColor = '#E9D5FF';
            } else {
                boxPremium.style.backgroundColor = '#FAF5FF';
                boxPremium.style.borderColor = '#D8B4FE';
                boxFree.style.backgroundColor = '#FAFBFD';
                boxFree.style.borderColor = '#E2E8F0';
            }
        }
        accessFreeEdit.addEventListener('change', updateAccess);
        accessPremiumEdit.addEventListener('change', updateAccess);

        document.getElementById('formEditVideo').addEventListener('submit', function (e) {
            e.preventDefault();
            const title = document.getElementById('editVideoTitle').value.trim();
            window.showHumoraToast(`Video "${title}" berhasil diperbarui!`);
            setTimeout(() => {
                window.location.href = "{{ url('/admin/videos') }}";
            }, 1200);
        });
    });
</script>
@endsection
