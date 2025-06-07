
let WigaClass = Wiga.class({
    async render(){
        let response = await WigaHttp.get('event/'+WigaRoute.segment(1));
        WigaHttp.handle(response, null,function(res) {
            let data = res.data
            
            let statusBadge = `<span class="badge rounded-pill bg-secondary-light fs-6 mb-2">Belum Dimulai</span>`

            if(data.status_id == '1'){
                statusBadge = `<span class="badge rounded-pill bg-success-light fs-6 mb-2">Sedang Berlangsung</span>`
            }else if(data.status_id == '2'){
                statusBadge = `<span class="badge rounded-pill bg-danger-light fs-6 mb-2">Selesai</span>`
            }

            let content = `<div class="col-lg-6">
                                <div wigaimage-lazyload="${WigaRoute.storageUrl(data.image_id)}" class="img-fluid event-image"></div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-4">
                                    ${statusBadge}
                                    <h3 class="event-title">${data.title}</h3>
                                </div>

                                <div class="info-card mb-4">
                                    <div class="info-item d-flex align-items-center mb-3">
                                        <i class="fa-solid fa-calendar-alt fa-fw fa-xl me-3"></i>
                                        <div>
                                            <h6 class="mb-0 fw-bold">Tanggal</h6>
                                            <p class="mb-0 text-muted">${data.date_format}</p>
                                        </div>
                                    </div>
                                    <div class="info-item d-flex align-items-center mb-3">
                                        <i class="fa-solid fa-clock fa-fw fa-xl me-3"></i>
                                        <div>
                                            <h6 class="mb-0 fw-bold">Waktu</h6>
                                            <p class="mb-0 text-muted">${data.time_format}</p>
                                        </div>
                                    </div>
                                    ${data.type == 'online'
                                        ? ` <div class="info-item d-flex align-items-center">
                                                <i class="fa-solid fa-link fa-fw fa-xl me-3"></i>
                                                <div>
                                                    <h6 class="mb-0 fw-bold">Akses Online</h6>
                                                    <p class="mb-0 text-muted">
                                                        <a href="${data.link}" target="_blank" rel="noopener noreferrer" class="text-info">Klik untuk bergabung</a>
                                                    </p>
                                                </div>
                                            </div>`
                                        : ` <div class="info-item d-flex">
                                                <i class="fa-solid fa-map-marker-alt fa-fw fa-xl me-3 mt-3"></i>
                                                <div>
                                                    <h6 class="mb-0 fw-bold">Lokasi</h6>
                                                    <p class="mb-0 text-muted location-text">
                                                        ${
                                                            data.location ? 
                                                            data.location.replace(/(https?:\/\/[^\s]+|www\.[^\s]+)/g, url => {
                                                                const href = url.startsWith('http') ? url : `http://${url}`;
                                                                return `<a href="${href}" target="_blank" rel="noopener noreferrer" class="text-info">${url}</a>`;
                                                            })
                                                            .replace(/\n/g, '<br>') 
                                                            : ''
                                                        }
                                                    </p>
                                                </div>
                                            </div>`
                                    }
                                </div>
                                <div class="mb-5">
                                    <h5 class="fw-bold">Deskripsi Event</h5>
                                    <p class="text-secondary text-break-url" style="line-height: 1.8;">
                                        ${
                                            data.description ? 
                                            data.description.replace(/(https?:\/\/[^\s]+|www\.[^\s]+)/g, url => {
                                                const href = url.startsWith('http') ? url : `http://${url}`;
                                                return `<a href="${href}" target="_blank" rel="noopener noreferrer" class="text-info">${url}</a>`;
                                            })
                                            .replace(/\n/g, '<br>') 
                                            : ''
                                        }
                                    </p>
                                </div>
                                <div id="wiga-alert"></div>
                                <div class="d-grid">
                                    ${data.status_id != '2' ? `<button type="button" class="btn btn-gradient btn-lg fw-bold py-3" data-bs-toggle="modal" data-bs-target="#ModalRegister">
                                            <i class="fa-solid fa-ticket me-2"></i>
                                                Daftar Sekarang
                                    </button>` : ''}
                                </div>
                            </div>`
            $wiga('#event--content').html(content);
            WigaImageLazyload.render()
        },function(res){
        });
    },
    async store()
    {

        let response = await WigaHttp.post('/event/'+WigaRoute.segment(1), WigaForm.data('#ModalRegister'));
        WigaHttp.handle(response,'#ModalRegister',function(res) {
            $wiga('#ModalRegister').modal('hide');
            $wiga('#ModalConfirm').modal('hide');
            WigaNotify.showInline('#wiga-alert',{
                type: 'success',
                content: res.message,
            })
        },function(res){
            WigaNotify.showInline('#ModalRegisterAlert',{
                type: 'danger',
                content: res.message,
            })
            $wiga('#ModalConfirm').modal('hide');
            $wiga('#ModalRegister').modal('show');
        });

    },
})


$wiga('#ModalConfirm form').on('submit',function(e){
    e.preventDefault();
    $wiga('#ModalConfirm [type="submit"]').indicator(WigaClass.store());    
})