let WigaClass = Wiga.class({
    async render() {
        let response = await WigaHttp.get('event/' + WigaRoute.segment(2));
        
        WigaHttp.handle(response, null, function(res) {
            let data = res.data;
            
            // 1. Logika Badge Status
            let statusBadge = `<span class="badge badge-light-secondary px-3 py-2 rounded-pill ms-2">Belum Dimulai</span>`;
            if (data.status_id == '1') {
                statusBadge = `<span class="badge badge-light-success px-3 py-2 rounded-pill text-success d-inline-flex align-items-center ms-2"><span class="pulse-icon me-2"></span> Sedang Berlangsung</span>`;
            } else if (data.status_id == '2') {
                statusBadge = `<span class="badge bg-light-danger px-3 py-2 rounded-pill text-danger ms-2">Event Selesai</span>`;
            }

            let priceInfo = '';
            if (data.registration_fee > 0) {
                priceInfo = `
                    <div class="col-sm-6">
                        <div class="info-item">
                            <i class="fa-solid fa-coins shadow-sm text-warning"></i>
                            <div>
                                <small class="text-muted d-block">Biaya Pendaftaran</small>
                                <span class="fw-bold text-dark">
                                    Rp ${WigaString.toIdr(data.registration_fee)}
                                </span>
                            </div>
                        </div>
                    </div>
                    `;
            } else {
                priceInfo = `<div class="col-sm-6">
                        <div class="info-item">
                            <i class="fa-solid fa-coins shadow-sm text-warning"></i>
                            <div>
                                <small class="text-muted d-block">Biaya Pendaftaran</small>
                                <span class="fw-bold text-success">Gratis</span>
                            </div>
                        </div>
                    </div>`;
            }

            let regDeadlineInfo = '';
            let isRegistrationClosed = false;

            if (data.registration_end) {
                isRegistrationClosed = data.registration_end_status? true : false;

                regDeadlineInfo = `
                    <div class="col-sm-6">
                        <div class="info-item">
                            <i class="fa-solid fa-hourglass-end shadow-sm text-danger"></i>
                            <div>
                                <small class="text-muted d-block">Batas Pendaftaran</small>
                                <span class="fw-bold ${isRegistrationClosed ? 'text-danger' : 'text-dark'}">
                                    ${data.registration_end_format}
                                </span>
                            </div>
                        </div>
                    </div>`;
            }

            // 2. Logika Badge Kuota
            let quotaBadge = '';
            if (data.quota > 0) {
                const remains = data.quota - data.participants_count;
                const isFull = remains <= 0;
                quotaBadge = `
                    <div class="quota-badge ${isFull ? 'full' : 'limited'} shadow-sm">
                        <i class="fa-solid ${isFull ? 'fa-lock' : 'fa-users'} me-2"></i> 
                        ${isFull ? 'Kuota Penuh' : `${remains} Slot Tersisa`}
                    </div>`;
            } else {
                quotaBadge = `
                    <div class="quota-badge unlimited shadow-sm">
                        <i class="fa-solid fa-infinity me-2"></i> Kuota Terbuka
                    </div>`;
            }

            // 3. Logika Progress Bar Kuota (Hanya tampil jika terbatas)
            // Ganti bagian logika progres bar di JS Anda dengan ini:
            let quotaProgress = '';
            if (data.quota > 0) {
                const percent = Math.min((data.participants_count / data.quota) * 100, 100);
                quotaProgress = `
                    <div class="mt-4 mb-2 animate__animated animate__fadeInUp">
                        <div class="quota-label-group">
                            <span class="quota-label-text">KUOTA PESERTA</span>
                            <span class="quota-percent-text">${Math.round(percent)}%</span>
                        </div>
                        <div class="progress progress-container-dark" style="height: 12px;">
                            <div class="progress-bar bg-primary-gradient progress-bar-striped progress-bar-animated" 
                                role="progressbar" 
                                style="width: ${percent}%; border-radius: 20px;">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-light">${data.participants_count} Terdaftar</small>
                            <small class="text-light">Kapasitas ${data.quota}</small>
                        </div>
                    </div>`;
            }

            let content = `
                <div class="col-lg-6 mb-4 mb-lg-0 animate__animated animate__fadeInLeft">
                    <div class="pe-lg-4">
                        <div wigaimage-lazyload="${WigaRoute.storageUrl(data.image_id)}" class="img-fluid event-image shadow-lg" style="border-radius: 30px;"></div>
                        <small class="text-light d-block mt-2 text-center">Klik gambar untuk memperbesar</small>
                    </div>
                </div>

                <div class="col-lg-6 animate__animated animate__fadeInRight">
                    <div class="ps-lg-2">
                        <div class="d-flex align-items-center flex-wrap mb-2">
                            ${quotaBadge}
                            ${statusBadge}
                        </div>
                        <h1 class="event-title mb-4" style="font-size: 2.5rem; letter-spacing: -1px;">${data.title}</h1>

                        <div class="info-card mb-4 border-0 shadow-sm p-4" style="background: white; border-radius: 24px;">
                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <div class="info-item">
                                        <i class="fa-solid fa-calendar-day shadow-sm"></i>
                                        <div>
                                            <small class="text-muted d-block">Tanggal</small>
                                            <span class="fw-bold text-dark">${data.date_format}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="info-item">
                                        <i class="fa-solid fa-clock shadow-sm"></i>
                                        <div>
                                            <small class="text-muted d-block">Waktu</small>
                                            <span class="fw-bold text-dark">${data.time_format}</span>
                                        </div>
                                    </div>
                                </div>
                                ${regDeadlineInfo}
                                ${priceInfo}
                                <div class="col-12 mt-3">
                                    <div class="info-item border-top pt-3">
                                        <i class="${data.type == 'online' ? 'fa-solid fa-video' : 'fa-solid fa-map-location-dot'} shadow-sm"></i>
                                        <div class="w-100">
                                            <small class="text-muted d-block">Lokasi Pelaksanaan</small>
                                            <div class="fw-semibold location-text mt-1 text-dark">
                                                ${data.location ? this.formatText(data.location) : 'Online'}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        ${quotaProgress}

                        <div class="description-section my-5 p-2">
                            <h5 class="fw-bold mb-3 d-flex align-items-center">
                                <span class="bg-primary-gradient me-3" style="width: 6px; height: 24px; border-radius: 10px; display: inline-block;"></span>
                                Tentang Event
                            </h5>
                            <div class="text-secondary opacity-90" style="line-height: 1.8; font-size: 1.1rem;">
                                ${data.description ? this.formatText(data.description) : '<em class="text-muted">Tidak ada deskripsi.</em>'}
                            </div>
                        </div>

                        <div id="wiga-alert"></div>
                        ${data.registration_end_status? `<div class="action-button mt-4 text-center">
                                <h4 class="badge badge-light-danger fw-bold fs-4 px-4">Pendaftaran Telah Ditutup</h4>
                            </div>` : `<div class="action-button mt-4">
                            ${(data.status_id != '2' && (data.quota <= 0 || data.participants_count < data.quota)) ? `
                                <button type="button" class="btn btn-gradient w-100 py-3 shadow-lg btn-lg animate__animated animate__pulse animate__infinite animate__slow" data-bs-toggle="modal" data-bs-target="#ModalRegister">
                                    <i class="fa-solid fa-paper-plane me-2"></i> Daftarkan Diri Saya
                                </button>
                            ` : `
                                <button class="btn btn-light w-100 py-3 border text-muted fw-bold rounded-4" disabled>
                                    <i class="fa-solid fa-circle-xmark me-2"></i> ${data.status_id == '2' ? 'Event Telah Berakhir' : 'Kuota Telah Terpenuhi'}
                                </button>
                            `}
                        </div>`}
                        
                    </div>
                </div>`;

            $wiga('#event--content').hide().html(content).fadeIn(800);
            WigaImageLazyload.render();
        }.bind(this));
    },

    formatText(text) {
        return text.replace(/(https?:\/\/[^\s]+|www\.[^\s]+)/g, url => {
            const href = url.startsWith('http') ? url : `http://${url}`;
            return `<a href="${href}" target="_blank" rel="noopener noreferrer" class="text-primary fw-medium text-break-url">${url}</a>`;
        }).replace(/\n/g, '<br>');
    },

    async store() {
        let response = await WigaHttp.post('/event/' + WigaRoute.segment(2), WigaForm.data('#ModalRegister'));
        WigaHttp.handle(response, '#ModalRegister', function(res) {
            $wiga('#ModalRegister').modal('hide');
            $wiga('#ModalConfirm').modal('hide');
            
            // Refresh render setelah sukses daftar untuk update kuota real-time
            // this.render();

            // WigaRoute.redirect('reg/' + res.data.reg_code);

            WigaNotify.success(res.message,'reg/' + res.data.reg_code);

            // WigaNotify.show({
            //     type: 'success',
            //     content: `<div class="p-2"><strong>Berhasil Mendaftar!</strong><br>${res.message}</div>`,
            // })
        }.bind(this), function(res) {
            WigaNotify.showInline('#ModalRegisterAlert', {
                type: 'danger',
                content: res.message,
            })
            $wiga('#ModalConfirm').modal('hide');
            $wiga('#ModalRegister').modal('show');
        });
    },
})

$wiga('#ModalConfirm form').on('submit', function(e) {
    e.preventDefault();
    $wiga('#ModalConfirm [type="submit"]').indicator(WigaClass.store());
})