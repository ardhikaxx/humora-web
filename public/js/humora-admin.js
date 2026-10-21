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
    let iconName = 'check-circle-2';
    let iconClass = 'text-success';
    let titleText = 'Berhasil';

    if (type === 'danger' || type === 'error') {
        iconName = 'x-circle';
        iconClass = 'text-danger';
        titleText = 'Gagal';
    } else if (type === 'warning') {
        iconName = 'alert-triangle';
        iconClass = 'text-warning';
        titleText = 'Peringatan';
    } else if (type === 'info') {
        iconName = 'info';
        iconClass = 'text-info';
        titleText = 'Informasi';
    }

    const toastHtml = `
        <div id="${toastId}" class="toast toast-humora align-items-center border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex align-items-center p-3">
                <i data-lucide="${iconName}" class="${iconClass} fs-5 me-2 flex-shrink-0"></i>
                <div class="toast-body p-0 flex-grow-1" style="font-size: 0.85rem;">
                    <strong>${titleText}:</strong> ${message}
                </div>
                <button type="button" class="btn-close btn-close-white ms-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', toastHtml);
    if (window.lucide) {
        window.lucide.createIcons();
    }
    const toastEl = document.getElementById(toastId);
    if (toastEl && window.bootstrap) {
        const bsToast = new bootstrap.Toast(toastEl, { delay: duration });
        bsToast.show();
        toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
    }
};

document.addEventListener('DOMContentLoaded', function () {
    if (window.lucide) {
        window.lucide.createIcons();
    }
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
                            <i data-lucide="search"></i>
                            <div class="fw-bold">Tidak ada data ditemukan</div>
                            <small class="text-muted">Coba ubah kata kunci pencarian atau sesuaikan filter Anda.</small>
                        </td>
                    `;
                    table.querySelector('tbody').appendChild(emptyRow);
                    if (window.lucide) {
                        window.lucide.createIcons();
                    }
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
                        <i data-lucide="${isVideo ? 'clapperboard' : 'file-image'}" class="fs-5"></i>
                    </div>
                    <div class="text-start">
                        <div class="fw-bold text-dark" style="font-size: 0.88rem;">${file.name}</div>
                        <small class="text-muted" style="font-size: 0.75rem;">${formattedSize} &bull; ${file.type || 'Format Terverifikasi'}</small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-replace-file" style="font-size: 0.78rem;">
                        <i data-lucide="rotate-cw" class="me-1"></i> Ganti
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 btn-remove-file" style="font-size: 0.78rem;">
                        <i data-lucide="trash-2" class="me-1"></i>
                    </button>
                </div>
            </div>
        `;
        dropzone.appendChild(fileInput);
        if (window.lucide) {
            window.lucide.createIcons();
        }

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
                        <i data-lucide="cloud-upload"></i>
                    </div>
                    <div class="fw-bold text-dark mb-1" style="font-size: 0.88rem;">
                        Tarik file ke sini, atau <span class="text-decoration-underline" style="color: #43135C;">pilih dari komputer</span>
                    </div>
                    <small class="text-muted" style="font-size: 0.76rem;">Pilih berkas ${isImage ? 'gambar' : 'video'} yang sesuai</small>
                `;
                dropzone.appendChild(fileInput);
                if (window.lucide) {
                    window.lucide.createIcons();
                }

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
                    badge.innerHTML = '<i data-lucide="clock"></i> Draft';
                    window.showHumoraToast('Status diubah menjadi Draft.', 'warning');
                } else {
                    badge.className = 'badge-pill-success';
                    badge.innerHTML = '<i data-lucide="check-circle"></i> Terpublikasi';
                    window.showHumoraToast('Status diubah menjadi Terpublikasi.', 'success');
                }
                if (window.lucide) {
                    window.lucide.createIcons();
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
                    badge.innerHTML = '<i data-lucide="gem"></i> Premium';
                    window.showHumoraToast('Akses video diubah menjadi: Khusus Premium (VIP)', 'info');
                } else {
                    badge.className = 'badge-pill-free badge-interactive';
                    badge.innerHTML = '<i data-lucide="play-circle"></i> Free Preview';
                    window.showHumoraToast('Akses video diubah menjadi: Free Preview (Gratis)', 'success');
                }
                if (window.lucide) {
                    window.lucide.createIcons();
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

    // -------------------------------------------------------------
    // 8. MOBILE SIDEBAR DRAWER NAVIGATION & RESIZE HANDLER
    // -------------------------------------------------------------
    const adminSidebar = document.getElementById('adminSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    function closeMobileSidebar() {
        if (window.innerWidth < 992 && adminSidebar && sidebarOverlay) {
            adminSidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        }
    }

    // Auto-close drawer when clicking on any nav link on mobile
    document.querySelectorAll('.admin-sidebar .nav-humora-link, .admin-sidebar .sidebar-brand-link').forEach(link => {
        link.addEventListener('click', function () {
            closeMobileSidebar();
        });
    });

    // Handle screen resize smoothly between mobile and desktop
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 992) {
            if (adminSidebar) adminSidebar.classList.remove('show');
            if (sidebarOverlay) sidebarOverlay.classList.remove('show');
        }
    });

    // -------------------------------------------------------------
    // 9. CLIENT-SIDE AUTOMATIC IMAGE COMPRESSION & WEBP CONVERTER
    // -------------------------------------------------------------
    window.formatBytes = function (bytes, decimals = 1) {
        if (!bytes || bytes === 0) return '0 B';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    };

    window.compressImageFileToWebp = function (file, options = {}) {
        return new Promise((resolve, reject) => {
            if (!file || !file.type.startsWith('image/')) {
                return reject(new Error('File yang dipilih bukan berkas gambar valid.'));
            }

            const maxWidth = options.maxWidth || 1920;
            const maxHeight = options.maxHeight || 1080;
            const quality = typeof options.quality === 'number' ? options.quality : 0.82;

            const reader = new FileReader();
            reader.onerror = () => reject(new Error('Gagal membaca file gambar.'));
            reader.onload = function (e) {
                const img = new Image();
                img.onerror = () => reject(new Error('Gagal memuat gambar ke browser.'));
                img.onload = function () {
                    let width = img.width;
                    let height = img.height;

                    // Calculate aspect-ratio-preserving dimensions
                    if (width > maxWidth || height > maxHeight) {
                        const ratio = Math.min(maxWidth / width, maxHeight / height);
                        width = Math.round(width * ratio);
                        height = Math.round(height * ratio);
                    }

                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;

                    const ctx = canvas.getContext('2d');
                    if (!ctx) {
                        return reject(new Error('Gagal menginisialisasi canvas context.'));
                    }

                    // Enable high quality image smoothing
                    ctx.imageSmoothingEnabled = true;
                    ctx.imageSmoothingQuality = 'high';

                    // Draw image to canvas
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob(
                        function (blob) {
                            if (!blob) {
                                return reject(new Error('Browser tidak mendukung konversi WebP.'));
                            }

                            // Generate clean .webp filename
                            const baseName = file.name.replace(/\.[^/.]+$/, '').trim() || 'image';
                            const newFilename = baseName + '.webp';
                            const webpFile = new File([blob], newFilename, {
                                type: 'image/webp',
                                lastModified: Date.now(),
                            });

                            const originalSize = file.size;
                            const compressedSize = webpFile.size;
                            const savingsPercent = originalSize > 0
                                ? Math.max(0, Math.round((1 - compressedSize / originalSize) * 100))
                                : 0;

                            const previewUrl = URL.createObjectURL(webpFile);

                            resolve({
                                file: webpFile,
                                originalSize,
                                compressedSize,
                                savingsPercent,
                                previewUrl,
                                width,
                                height,
                                originalFormat: file.type.replace('image/', '').toUpperCase(),
                            });
                        },
                        'image/webp',
                        quality
                    );
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    };

    // Auto-attach client-side compressor to all image inputs
    document.querySelectorAll('input[type="file"][name="thumbnail"], input[type="file"][data-auto-webp="true"]').forEach(input => {
        // Prevent double binding
        if (input.dataset.webpBound === 'true') return;
        input.dataset.webpBound = 'true';

        // Find or create compression feedback container
        let statusContainer = document.getElementById(input.id + '_status');
        if (!statusContainer) {
            statusContainer = document.createElement('div');
            statusContainer.id = input.id + '_status';
            statusContainer.className = 'mt-2';
            input.parentNode.appendChild(statusContainer);
        }

        input.addEventListener('change', async function (e) {
            const rawFile = e.target.files && e.target.files[0];
            if (!rawFile) {
                statusContainer.innerHTML = '';
                return;
            }

            // If already processed webp by our script, skip re-compression
            if (rawFile._humoraCompressed) {
                return;
            }

            // Show interactive processing indicator
            statusContainer.innerHTML = `
                <div class="p-2 px-3 rounded-2 d-flex align-items-center gap-2 border" style="background: #F1F5F9; border-color: #CBD5E1; font-size: 0.76rem; color: #334155;">
                    <div class="spinner-border spinner-border-sm text-purple" role="status" style="width: 14px; height: 14px; color: #43135C;"></div>
                    <span>Mengompresi otomatis & mengonversi gambar ke <strong>WebP</strong> di browser...</span>
                </div>
            `;

            try {
                const result = await window.compressImageFileToWebp(rawFile, {
                    maxWidth: 1920,
                    maxHeight: 1080,
                    quality: 0.82,
                });

                // Tag compressed file to prevent infinite event loop
                result.file._humoraCompressed = true;

                // Replace input file via DataTransfer API
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(result.file);
                input.files = dataTransfer.files;

                // Update preview image if exists
                const previewImg = document.getElementById('imagePreview');
                const placeholder = document.getElementById('placeholderText');
                if (previewImg) {
                    previewImg.src = result.previewUrl;
                    previewImg.style.display = 'block';
                }
                if (placeholder) {
                    placeholder.style.display = 'none';
                }

                // Render success badge with clear savings info
                statusContainer.innerHTML = `
                    <div class="p-2 px-3 rounded-2 border d-flex flex-wrap align-items-center justify-content-between gap-2" style="background: #ECFDF5; border-color: #A7F3D0; font-size: 0.76rem; color: #065F46;">
                        <div class="d-flex align-items-center gap-2">
                            <i data-lucide="check-circle-2" class="text-success" style="width: 15px; height: 15px;"></i>
                            <span>
                                <strong>Terkonversi ke WebP:</strong> 
                                <span class="text-decoration-line-through text-muted">${window.formatBytes(result.originalSize)}</span> 
                                &rarr; <span class="fw-bold text-success">${window.formatBytes(result.compressedSize)}</span> 
                                <span class="badge bg-success ms-1">Hemat ${result.savingsPercent}%</span>
                            </span>
                        </div>
                        <span class="badge py-1 px-2 text-uppercase" style="background: rgba(16, 185, 129, 0.2); color: #065F46; font-size: 0.7rem;">
                            ${result.width} &times; ${result.height} px
                        </span>
                    </div>
                `;

                if (window.lucide) {
                    window.lucide.createIcons();
                }

                if (window.showHumoraToast) {
                    window.showHumoraToast(`Foto otomatis dikompresi ke WebP (${window.formatBytes(result.compressedSize)}, hemat ${result.savingsPercent}%).`, 'success');
                }
            } catch (err) {
                console.warn('Kompresi WebP client-side gagal, fallback ke file asli:', err);
                statusContainer.innerHTML = `
                    <div class="p-2 px-3 rounded-2 border" style="background: #FFFBEB; border-color: #FDE68A; font-size: 0.74rem; color: #92400E;">
                        <i data-lucide="info" class="text-warning me-1" style="width: 14px; height: 14px;"></i>
                        File asli digunakan (${window.formatBytes(rawFile.size)}). Kompresi WebP server akan dijalankan saat disimpan.
                    </div>
                `;
                if (window.lucide) {
                    window.lucide.createIcons();
                }
            }
        });
    });

    // =========================================================================
    // HUMORA UNIVERSAL NOTCHED FLOATING LABEL INPUT STATES
    // Spec: 6a152b7e212dd2cfa2d272fe3238a1fc.png
    // States: DEFAULT, HOVER, ACTIVE, TYPING, DISABLED, ERROR
    // =========================================================================
    window.initHumoraInputStates = function (root = document) {
        if (!root) return;

        // 1. Process explicit .humora-field elements
        const existingFields = root.querySelectorAll ? root.querySelectorAll('.humora-field') : [];
        existingFields.forEach(field => setupFieldEvents(field));

        // 2. Automatically wrap and enhance standalone form inputs with labels
        const inputSelectors = [
            'input.form-control-custom:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]):not([type="file"]):not([type="submit"]):not([type="button"])',
            'textarea.form-control-custom',
            'select.form-select-custom',
            'input.form-control:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]):not([type="file"]):not([type="submit"]):not([type="button"])',
            'textarea.form-control'
        ];

        const elements = root.querySelectorAll ? root.querySelectorAll(inputSelectors.join(',')) : [];
        elements.forEach(el => {
            // Skip elements already in .humora-field or inside input-group or select2 search fields or explicitly opted out
            if (el.closest('.humora-field') || el.closest('.input-group') || el.closest('.select2-container') || el.classList.contains('select2-search__field') || el.dataset.noFloating === 'true') {
                return;
            }

            // Find associated label
            let label = null;
            if (el.id) {
                label = document.querySelector(`label[for="${el.id}"]`);
            }
            if (!label && el.parentElement) {
                if (el.previousElementSibling && el.previousElementSibling.tagName === 'LABEL') {
                    label = el.previousElementSibling;
                } else {
                    label = el.parentElement.querySelector('label.form-label-custom, label.form-label, label');
                }
            }

            let labelText = '';
            if (label) {
                labelText = label.innerText.replace('*', '').trim();
            } else if (el.placeholder && el.placeholder.trim().length > 0 && el.placeholder !== ' ') {
                labelText = el.placeholder.trim();
            }

            if (!labelText) return;

            // Create .humora-field container
            const field = document.createElement('div');
            field.className = 'humora-field';
            if (el.tagName === 'TEXTAREA') field.classList.add('is-textarea');
            if (el.tagName === 'SELECT') field.classList.add('is-select');

            // Check if there is a password toggle button or suffix icon
            const parent = el.parentElement;
            let suffixBtn = null;
            if (parent) {
                suffixBtn = parent.querySelector('#togglePassword, .btn-toggle-password');
                if (suffixBtn) {
                    field.classList.add('has-suffix');
                    suffixBtn.classList.add('humora-field-suffix');
                }
            }

            // Wrap input
            if (parent) {
                parent.insertBefore(field, el);
                field.appendChild(el);

                // Create floating label
                const floatingLabel = document.createElement('label');
                if (el.id) floatingLabel.setAttribute('for', el.id);
                floatingLabel.className = 'humora-floating-label';
                floatingLabel.innerText = labelText;
                field.appendChild(floatingLabel);

                if (suffixBtn) {
                    field.appendChild(suffixBtn);
                }

                // If original label existed as an external element, hide or remove it to prevent duplicate labels
                if (label && label.parentElement !== field) {
                    label.style.display = 'none';
                }

                setupFieldEvents(field);
            }
        });

        function setupFieldEvents(field) {
            if (field._humoraEventsAttached) {
                syncFieldState(field);
                return;
            }
            field._humoraEventsAttached = true;

            const input = field.querySelector('input, textarea, select');
            if (!input) return;

            // Ensure placeholder is empty or space for native CSS floating mechanics
            if (!input.placeholder || input.placeholder === '') {
                input.placeholder = ' ';
            }

            function syncFieldState() {
                const val = input.value !== undefined && input.value !== null ? input.value.toString().trim() : '';
                const hasVal = val.length > 0 || (input.tagName === 'SELECT' && input.selectedIndex > 0 && input.value !== '');
                const isInvalid = input.classList.contains('is-invalid') || 
                                  field.classList.contains('is-invalid') ||
                                  (field.parentElement && field.parentElement.querySelector('.invalid-feedback.d-block'));
                const isDisabled = input.disabled || input.hasAttribute('disabled');

                // 1. TYPING / FILLED state
                if (hasVal) {
                    field.classList.add('has-value', 'is-typing');
                } else {
                    field.classList.remove('has-value', 'is-typing');
                }

                // 2. DISABLED state
                if (isDisabled) {
                    field.classList.add('is-disabled');
                } else {
                    field.classList.remove('is-disabled');
                }

                // 3. ERROR state
                if (isInvalid) {
                    field.classList.add('is-error');
                } else {
                    field.classList.remove('is-error');
                }
            }

            // Event bindings
            input.addEventListener('focus', () => {
                field.classList.add('is-active');
                syncFieldState();
            });

            input.addEventListener('blur', () => {
                field.classList.remove('is-active');
                syncFieldState();
            });

            input.addEventListener('input', () => {
                syncFieldState();
            });

            input.addEventListener('change', () => {
                syncFieldState();
            });

            // Initial state sync
            syncFieldState();
            setTimeout(syncFieldState, 100);
            setTimeout(syncFieldState, 500);
        }
    };

    // Run on initial page load
    window.initHumoraInputStates();

    // Hook to Bootstrap modals
    function syncModalFields(modalEl) {
        window.initHumoraInputStates(modalEl);
        const inputs = modalEl.querySelectorAll('input, textarea, select');
        inputs.forEach(inp => {
            inp.dispatchEvent(new Event('input', { bubbles: true }));
        });
    }

    document.addEventListener('show.bs.modal', function (e) {
        setTimeout(() => syncModalFields(e.target), 30);
        setTimeout(() => syncModalFields(e.target), 150);
    });
    document.addEventListener('shown.bs.modal', function (e) {
        setTimeout(() => syncModalFields(e.target), 50);
        setTimeout(() => syncModalFields(e.target), 200);
    });

    // -------------------------------------------------------------
    // HUMORA GENRE / CATEGORY SELECTION CARDS HANDLER
    // -------------------------------------------------------------
    function updateGenreSelector(container) {
        if (!container) return;
        const checkboxes = container.querySelectorAll('.genre-checkbox-native');
        let selectedCount = 0;
        checkboxes.forEach(cb => {
            const card = cb.closest('.genre-chip-card');
            if (cb.checked) {
                selectedCount++;
                if (card) card.classList.add('is-selected');
            } else {
                if (card) card.classList.remove('is-selected');
            }
        });

        const countSpan = container.querySelector('#genreCountNumber');
        const badge = container.querySelector('#genreCounterBadge');
        if (countSpan) countSpan.textContent = selectedCount;
        if (badge) {
            badge.classList.toggle('has-selected', selectedCount > 0);
        }

        if (selectedCount > 0) {
            container.classList.remove('is-invalid');
            const feedback = container.querySelector('.invalid-feedback');
            if (feedback) feedback.style.display = 'none';
        }
    }

    document.addEventListener('change', function (e) {
        if (e.target && e.target.classList.contains('genre-checkbox-native')) {
            const container = e.target.closest('.humora-genre-selector');
            if (container) {
                updateGenreSelector(container);
            }
        }
    });

    // Initial sync for genre selectors on load
    document.querySelectorAll('.humora-genre-selector').forEach(sel => {
        updateGenreSelector(sel);
    });

    // Observer for dynamically added elements (e.g., dynamic bank account rows)
    const domObserver = new MutationObserver(function (mutations) {
        let shouldInit = false;
        mutations.forEach(m => {
            if (m.addedNodes && m.addedNodes.length > 0) {
                m.addedNodes.forEach(node => {
                    if (node.nodeType === 1 && (node.querySelector('input, textarea, select') || node.matches('input, textarea, select'))) {
                        shouldInit = true;
                    }
                });
            }
        });
        if (shouldInit) {
            window.initHumoraInputStates();
        }
    });
    domObserver.observe(document.body, { childList: true, subtree: true });
});
