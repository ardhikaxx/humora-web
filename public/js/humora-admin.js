/**
 * HUMORA Admin Portal - Interactive UI & Functionality Script
 * Fully client-side interactive with backend-ready bindings.
 */

// Toast notification helper
window.showHumoraToast = function (message, type = 'success', duration = 3500) {
    let container = document.getElementById('humoraToastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'humoraToastContainer';
        container.className = 'humora-toast-container toast-container';
        document.body.appendChild(container);
    }

    const toastId = 'toast-' + Date.now();
    let iconClass = 'bi-check-circle-fill text-success';
    let titleText = 'Berhasil';

    if (type === 'danger' || type === 'error') {
        iconClass = 'bi-x-circle-fill text-danger';
        titleText = 'Gagal';
    } else if (type === 'warning') {
        iconClass = 'bi-exclamation-triangle-fill text-warning';
        titleText = 'Peringatan';
    } else if (type === 'info') {
        iconClass = 'bi-info-circle-fill text-info';
        titleText = 'Informasi';
    }

    const toastHtml = `
        <div id="${toastId}" class="toast toast-humora align-items-center border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex align-items-center p-3">
                <i class="bi ${iconClass} fs-5 me-2 flex-shrink-0"></i>
                <div class="toast-body p-0 flex-grow-1" style="font-size: 0.85rem;">
                    <strong>${titleText}:</strong> ${message}
                </div>
                <button type="button" class="btn-close btn-close-white ms-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', toastHtml);
    const toastEl = document.getElementById(toastId);
    if (toastEl && window.bootstrap) {
        const bsToast = new bootstrap.Toast(toastEl, { delay: duration });
        bsToast.show();
        toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
    }
};

document.addEventListener('DOMContentLoaded', function () {
    // -------------------------------------------------------------
    // 1. GLOBAL DELETE CONFIRMATION MODAL
    // -------------------------------------------------------------
    let pendingDeleteTarget = null;
    let pendingDeleteCallback = null;

    const confirmModalEl = document.getElementById('confirmDeleteModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const confirmDeleteText = document.getElementById('confirmDeleteText');
    let bsConfirmModal = null;
    if (confirmModalEl && window.bootstrap) {
        bsConfirmModal = new bootstrap.Modal(confirmModalEl);
    }

    window.promptHumoraDelete = function (itemName, onConfirm, targetRow) {
        pendingDeleteTarget = targetRow || null;
        pendingDeleteCallback = onConfirm;
        if (confirmDeleteText) {
            confirmDeleteText.innerHTML = `Apakah Anda yakin ingin menghapus <strong>${itemName}</strong>? Tindakan ini tidak dapat dibatalkan.`;
        }
        if (bsConfirmModal) {
            bsConfirmModal.show();
        } else if (confirm(`Apakah Anda yakin ingin menghapus ${itemName}?`)) {
            if (typeof onConfirm === 'function') onConfirm();
        }
    };

    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function () {
            if (bsConfirmModal) {
                bsConfirmModal.hide();
            }
            if (typeof pendingDeleteCallback === 'function') {
                pendingDeleteCallback(pendingDeleteTarget);
            } else if (pendingDeleteTarget) {
                pendingDeleteTarget.classList.add('table-row-fade-out');
                setTimeout(() => {
                    pendingDeleteTarget.remove();
                    window.showHumoraToast('Data berhasil dihapus.');
                }, 300);
            }
        });
    }

    // Attach to elements with data-confirm-delete
    document.addEventListener('click', function (e) {
        const delBtn = e.target.closest('[data-confirm-delete]');
        if (delBtn) {
            e.preventDefault();
            const itemName = delBtn.getAttribute('data-item-name') || 'item ini';
            const row = delBtn.closest('tr');
            window.promptHumoraDelete(itemName, function (target) {
                const targetRow = target || row;
                if (targetRow) {
                    targetRow.classList.add('table-row-fade-out');
                    setTimeout(() => {
                        targetRow.remove();
                        window.showHumoraToast(`"${itemName}" berhasil dihapus.`);
                    }, 300);
                }
            }, row);
        }
    });

    // -------------------------------------------------------------
    // 2. UNIVERSAL TABLE FILTER & SEARCH
    // -------------------------------------------------------------
    function setupTableFiltering(table) {
        if (!table) return;
        const card = table.closest('.humora-card') || table.parentElement;
        const searchInput = card.querySelector('input[type="text"]') || 
                            document.querySelector(`input[data-table-search="${table.id}"]`) ||
                            table.closest('.admin-content')?.querySelector('.search-input-wrap input');
        
        const filterSelects = card.querySelectorAll('select.form-select-custom') || [];
        const rows = table.querySelectorAll('tbody tr:not(.empty-state-row)');
        const paginationText = card.querySelector('.border-top small') || card.parentElement.querySelector('.border-top small');

        function applyFilters() {
            const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
            let visibleCount = 0;

            rows.forEach(row => {
                let matchesSearch = true;
                let matchesSelects = true;

                if (query) {
                    const rowText = row.innerText.toLowerCase();
                    matchesSearch = rowText.includes(query);
                }

                filterSelects.forEach(sel => {
                    const selectedVal = sel.value;
                    if (selectedVal && !selectedVal.startsWith('Semua') && !selectedVal.startsWith('Terbaru') && !selectedVal.startsWith('Terlama')) {
                        const rowText = row.innerText;
                        if (!rowText.toLowerCase().includes(selectedVal.toLowerCase())) {
                            matchesSelects = false;
                        }
                    }
                });

                if (matchesSearch && matchesSelects) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Handle empty state row
            let emptyRow = table.querySelector('.empty-state-row');
            if (visibleCount === 0) {
                if (!emptyRow) {
                    const cols = table.querySelectorAll('thead th').length || 6;
                    emptyRow = document.createElement('tr');
                    emptyRow.className = 'empty-state-row';
                    emptyRow.innerHTML = `
                        <td colspan="${cols}" class="empty-table-state">
                            <i class="bi bi-search"></i>
                            <div class="fw-bold">Tidak ada data ditemukan</div>
                            <small class="text-muted">Coba ubah kata kunci pencarian atau sesuaikan filter Anda.</small>
                        </td>
                    `;
                    table.querySelector('tbody').appendChild(emptyRow);
                }
                emptyRow.style.display = '';
            } else if (emptyRow) {
                emptyRow.style.display = 'none';
            }

            // Update pagination text
            if (paginationText) {
                paginationText.innerHTML = `Menampilkan <strong>${visibleCount > 0 ? 1 : 0} - ${visibleCount}</strong> dari <strong>${rows.length}</strong> data`;
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
        }
        filterSelects.forEach(sel => {
            sel.addEventListener('change', applyFilters);
        });
    }

    document.querySelectorAll('.table-humora').forEach(table => {
        setupTableFiltering(table);
    });

    // -------------------------------------------------------------
    // 3. FILE DROPZONE ENHANCEMENTS (DRAG & DROP + PREVIEW)
    // -------------------------------------------------------------
    document.querySelectorAll('.humora-dropzone').forEach(dropzone => {
        const fileInput = dropzone.querySelector('input[type="file"]');
        if (!fileInput) return;

        // Click to open file picker
        dropzone.addEventListener('click', (e) => {
            if (!e.target.closest('button')) {
                fileInput.click();
            }
        });

        // Drag and drop events
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('dragover');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('dragover');
            });
        });

        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                fileInput.files = files;
                handleFileSelection(files[0], dropzone, fileInput);
            }
        });

        fileInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                handleFileSelection(this.files[0], dropzone, fileInput);
            }
        });
    });

    function handleFileSelection(file, dropzone, fileInput) {
        const isImage = file.type.startsWith('image/');
        const isVideo = file.type.startsWith('video/') || file.name.endsWith('.mp4') || file.name.endsWith('.webm');
        const formattedSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';

        dropzone.classList.add('has-file');

        // Check if there is an external preview image tag
        const previewImageTag = document.getElementById('previewImageTag');
        const previewEmptyIcon = document.getElementById('previewEmptyIcon');

        if (isImage) {
            const reader = new FileReader();
            reader.onload = function (e) {
                if (previewImageTag && previewEmptyIcon) {
                    previewImageTag.src = e.target.result;
                    previewImageTag.classList.remove('d-none');
                    previewEmptyIcon.classList.add('d-none');
                }
            };
            reader.readAsDataURL(file);
        }

        // Update dropzone UI
        dropzone.innerHTML = `
            <div class="d-flex align-items-center justify-content-between p-3 bg-white rounded-3 border">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon-wrap ${isVideo ? 'stat-icon-blue' : 'stat-icon-green'}" style="width: 44px; height: 44px;">
                        <i class="bi ${isVideo ? 'bi-camera-reels' : 'bi-file-earmark-image'} fs-5"></i>
                    </div>
                    <div class="text-start">
                        <div class="fw-bold text-dark" style="font-size: 0.88rem;">${file.name}</div>
                        <small class="text-muted" style="font-size: 0.75rem;">${formattedSize} &bull; ${file.type || 'Format Terverifikasi'}</small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-replace-file" style="font-size: 0.78rem;">
                        <i class="bi bi-arrow-repeat"></i> Ganti
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 btn-remove-file" style="font-size: 0.78rem;">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
        dropzone.appendChild(fileInput);

        const replaceBtn = dropzone.querySelector('.btn-replace-file');
        const removeBtn = dropzone.querySelector('.btn-remove-file');

        if (replaceBtn) {
            replaceBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                fileInput.click();
            });
        }

        if (removeBtn) {
            removeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                fileInput.value = '';
                dropzone.classList.remove('has-file');
                dropzone.innerHTML = `
                    <div class="humora-dropzone-icon">
                        <i class="bi bi-cloud-arrow-up"></i>
                    </div>
                    <div class="fw-bold text-dark mb-1" style="font-size: 0.88rem;">
                        Tarik file ke sini, atau <span class="text-decoration-underline" style="color: #43135C;">pilih dari komputer</span>
                    </div>
                    <small class="text-muted" style="font-size: 0.76rem;">Pilih berkas ${isImage ? 'gambar' : 'video'} yang sesuai</small>
                `;
                dropzone.appendChild(fileInput);

                if (previewImageTag && previewEmptyIcon) {
                    previewImageTag.classList.add('d-none');
                    previewEmptyIcon.classList.remove('d-none');
                    previewImageTag.src = '';
                }
                window.showHumoraToast('File telah dihapus.', 'info');
            });
        }

        window.showHumoraToast(`Berkas "${file.name}" (${formattedSize}) berhasil dipilih.`);
    }

    // -------------------------------------------------------------
    // 4. PRESET BUTTONS (e.g. Duration in Videos Form)
    // -------------------------------------------------------------
    const minInput = document.getElementById('minuteInput');
    const secInput = document.getElementById('secondInput');
    document.querySelectorAll('.preset-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            if (minInput && this.dataset.min !== undefined) minInput.value = this.dataset.min;
            if (secInput && this.dataset.sec !== undefined) secInput.value = this.dataset.sec;
            document.querySelectorAll('.preset-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // -------------------------------------------------------------
    // 5. SERIES TOGGLE STATUS (Terpublikasi <-> Draft)
    // -------------------------------------------------------------
    document.addEventListener('click', function (e) {
        const toggleBtn = e.target.closest('[data-toggle-status]');
        if (toggleBtn) {
            e.preventDefault();
            const row = toggleBtn.closest('tr');
            const badge = row ? row.querySelector('.badge-pill-success, .badge-pill-warning') : null;
            if (badge) {
                const isPublished = badge.classList.contains('badge-pill-success');
                if (isPublished) {
                    badge.className = 'badge-pill-warning';
                    badge.innerHTML = '<i class="bi bi-clock"></i> Draft';
                    window.showHumoraToast('Status diubah menjadi Draft.', 'warning');
                } else {
                    badge.className = 'badge-pill-success';
                    badge.innerHTML = '<i class="bi bi-check-circle"></i> Terpublikasi';
                    window.showHumoraToast('Status diubah menjadi Terpublikasi.', 'success');
                }
            }
        }
    });

    // -------------------------------------------------------------
    // 6. VIDEO ACCESS TOGGLE (Free Preview <-> Premium)
    // -------------------------------------------------------------
    document.addEventListener('click', function (e) {
        const toggleAccess = e.target.closest('[data-toggle-access], .badge-pill-free, .badge-pill-premium');
        if (toggleAccess && toggleAccess.closest('table')) {
            e.preventDefault();
            const badge = toggleAccess.classList.contains('badge-pill-free') || toggleAccess.classList.contains('badge-pill-premium') 
                ? toggleAccess 
                : toggleAccess.querySelector('.badge-pill-free, .badge-pill-premium');
            
            if (badge) {
                const isFree = badge.classList.contains('badge-pill-free');
                if (isFree) {
                    badge.className = 'badge-pill-premium badge-interactive';
                    badge.innerHTML = '<i class="bi bi-gem"></i> Premium';
                    window.showHumoraToast('Akses video diubah menjadi: Khusus Premium (VIP)', 'info');
                } else {
                    badge.className = 'badge-pill-free badge-interactive';
                    badge.innerHTML = '<i class="bi bi-play-circle"></i> Free Preview';
                    window.showHumoraToast('Akses video diubah menjadi: Free Preview (Gratis)', 'success');
                }
            }
        }
    });

    // -------------------------------------------------------------
    // 7. FORM SUBMISSIONS WITH VALIDATION & FEEDBACK
    // -------------------------------------------------------------
    document.querySelectorAll('form').forEach(form => {
        // Prevent default submit for demonstration if method is GET or no backend route yet, showing confirmation toast
        if (form.getAttribute('action') && form.getAttribute('action').includes('/admin/')) {
            form.addEventListener('submit', function (e) {
                if (!this.checkValidity()) {
                    return; // Let standard HTML5 validation highlight fields
                }
                // If it's a demo or has data-ajax-submit
                if (this.hasAttribute('data-demo-submit')) {
                    e.preventDefault();
                    window.showHumoraToast('Perubahan berhasil disimpan!');
                }
            });
        }
    });
});
