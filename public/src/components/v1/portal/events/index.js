
let WigaClass = Wiga.class({
    render()
    {

        WigaTable.init({
            selector: '#table--content',
            responsive: true,
            ajax: {
                url: '/portal/events',
            },
            columns: [
                { data: 'table.numbering' },
                { data: function(data){
                    return `<div class="img-fluid" wigaimage-lazyload="${WigaRoute.storageUrl(data.image_id)}">`
                }},
                { data: function(data){ 
                    let type = `<span class="badge badge-light-${data.type == 'offline' ? 'danger' : 'success'}">${data.type.toUpperCase()}</span>`
                    return `<a href="${WigaRoute.url('event/'+data.slug)}" class="fw-bold mb-2">${data.title}</a><br>${type}`
                }},
                { data: function(data) {
                        // Mengubah kolom deskripsi menjadi daftar kategori
                        if (data.categories && data.categories.length > 0) {
                            let badges = data.categories.map(cat => {
                                return `<span class="badge badge-light-primary mb-1 me-1">${cat.name}</span>`;
                            }).join('');
                            return `<div class="d-flex flex-wrap">${badges}</div>`;
                        }
                        return '<span class="text-muted small">Tanpa Kategori</span>';
                }},
                { data: function(data){
                    // Menampilkan Tanggal Acara & Batas Registrasi jika ada
                    let regEnd = data.registration_end ? `<br><small class="text-danger"><strong>Batas Reg:</strong> ${data.registration_end_format}</small>` : '';
                    return '<strong>Tanggal:</strong> ' + data.date_format + ' <br> <strong>Pukul:</strong> ' + data.time_format + regEnd
                } },
                { data: function(data){
                    // Menampilkan Biaya Pendaftaran
                    let fee = (data.registration_fee && data.registration_fee > 0) 
                            ? `<span class="text-success fw-bold">Rp ${WigaString.toIdr(data.registration_fee)}</span>` 
                            : '<span class="badge badge-light-primary">Gratis</span>';
                    return fee;
                } },
                { data: function(data){
                    return data.participants_count + (data.quota > 0 ? ' / ' + data.quota : '') + ' Peserta'
                } },
                { data: function(data){
                    if(data.status_id == 1){
                        return `<span class="badge badge-light-danger">${data.status}</span>`
                    }else if(data.status_id == 2){
                        return `<span class="badge badge-light-success">${data.status}</span>`
                    }
                    return `<span class="badge badge-light-warning">${data.status}</span>`
                } },
                { data: function(data){

                    // if(data.status_id == 0){

                        const userActions = [
                            { 
                                text: '<span class="badge badge-info fw-bold">Peserta</span>',
                                className: 'fw-bold',
                                href: WigaRoute.url('/portal/events/'+data.id+'/participants')
                                // events: {
                                //     click: function(e) {
                                //         e.preventDefault();
                                //         WigaRoute.redirect('/portal/events/'+data.id+'/participants');
                                //     }
                                // }
                            },
                            // {
                            //     type: 'separator'
                            // },
                            ...(data.status_id == '1' && data.status_publish == '0'? [{
                                text: '<span class="badge badge-primary">Generate Link Kehadiran</span>', 
                                events: {
                                    click: function(e) {
                                        e.preventDefault();
                                        WigaClass.generateAttendance(data.id)
                                    }
                                }
                            }] : []),
                            // ...([{
                            //     text: '<span class="badge badge-primary">Generate Link Kehadiran</span>', 
                            //     events: {
                            //         click: function(e) {
                            //             e.preventDefault();
                            //             WigaClass.generateAttendance(data.id)
                            //         }
                            //     }
                            // }]),
                            ...(data.status_id != '2' && data.status_publish == '0'? [{
                                text: '<span class="badge badge-secondary">Salin Link Pendaftaran</span>', 
                                events: {
                                    click: function(e) {
                                        e.preventDefault();
                                        $wiga(WigaRoute.url('event/'+data.slug)).clipboard('Link pendaftaran')
                                    }
                                }
                            }] : []),
                            ...(data.status_id == '0' && data.status_publish == '0'? [{
                                text: '<span class="badge badge-primary">Notifikasi Pengingat</span>', 
                                events: {
                                    click: function(e) {
                                        e.preventDefault();
                                        $wiga('#ModalNotification').attr('data-id',data.id);
                                        $wiga('#ModalNotification').modal('show');
                                    }
                                }
                            }] : []),
                            ...(data.status_id != '' && data.status_publish == '0'? [{
                                text: '<span class="badge badge-success">Publish Sertifikat</span>', 
                                events: {
                                    click: function(e) {
                                        $wiga('#ModalConfirmPublish').attr('data-id',data.id);
                                        $wiga('#ModalConfirmPublish').modal('show');
                                    }
                                }
                            }] : []),                            
                            ...(data.status_id != '2'? [{ 
                                text: '<span class="badge badge-light-primary">Edit</span>', 
                                href: WigaRoute.url('/portal/events/'+data.id)
                            }] : [])
                        ];

                        if(data.status_id == '0')
                        {
                            userActions.push({
                                text: '<span class="badge badge-light-danger">Hapus</span>', 
                                events: {
                                    click: function(e) {
                                        $wiga('#ModalDelete').attr('data-id',data.id);
                                        $wiga('#ModalDelete').modal('show');
                                    }
                                }
                            })
                        }

                        return WigaComponent.dropdown({
                            triggerContent: '<i class="fa-duotone fa-gear"></i>',
                            color: 'light-primary',
                            items: userActions,
                        });
                    // }

                    // return WigaComponent.button({
                    //     color: 'light-primary',
                    //     text: 'Preview',
                    //     events: {
                    //         click: function(e) {
                    //             e.preventDefault();
                    //             window.open(WigaRoute.url(data.slug), '_blank');
                    //         }
                    //     }
                    // })
                } }
            ],
            pageLength: 10,
            searching: true,
            toolbarSelector: '#toolbar-content',
            onDraw: function () {
                // console.log('Tabel di-draw ulang');
                WigaImageLazyload.render()
            }
        });

        this.fetchCategories();

    },

    async fetchCategories() {
        let response = await WigaHttp.get('/portal/category/list');
        WigaHttp.handle(response, null, function(res) {
            let select = $('#ModalForm [name="category_ids[]"]').select2({
                multiple: true,
                placeholder: 'Pilih Kategori',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#ModalForm'),
                data: res.data.map(cat => ({id: cat.id, text: cat.name})),
            });
        });
    },

    async store()
    {

        let response = await WigaHttp.post('/portal/events', WigaForm.data('#ModalForm form'));
        WigaHttp.handle(response,'#ModalForm form',function(res) {
            $wiga('#ModalForm').modal('hide');
            WigaClass.render();
            WigaNotify.showInline('#wiga-alert',{
                type: 'success',
                content: res.message,
            })
            $wiga('#ModalForm').removeAttr('data-id');
        },function(res){
            WigaNotify.showInline('#ModalFormAlert',{
                type: 'danger',
                content: res.message,
            })
        });

    },
    async update()
    {
        let data = WigaForm.data('#ModalForm form')

        data.append('image_deleted',WigaUploadImage.deletedImage({name:'image'}).length? 1 : 0);
        data.append('_method','PUT');

        let response = await WigaHttp.post('/portal/events/'+$wiga('#ModalForm').attr('data-id'), data);
        WigaHttp.handle(response,'#ModalForm form',function(res) {
            $wiga('#ModalForm').modal('hide');
            WigaClass.render();
            WigaNotify.showInline('#wiga-alert',{
                type: 'success',
                content: res.message,
            })
            $wiga('#ModalForm').removeAttr('data-id');
        },function(res){
            WigaNotify.showInline('#ModalFormAlert',{
                type: 'danger',
                content: res.message,
            })
        });

    },
    async delete()
    {

        let response = await WigaHttp.delete('/portal/events/'+$wiga('#ModalDelete').attr('data-id'));
        WigaHttp.handle(response,'#ModalDelete form',function(res) {
            $wiga('#ModalDelete').modal('hide');
            WigaClass.render();
            WigaNotify.showInline('#wiga-alert',{
                type: 'success',
                content: res.message,
            })
        },function(res){
            WigaNotify.showInline('#ModalDeleteAlert',{
                type: 'danger',
                content: res.message,
            })
        });
    },
    async publish()
    {

        let response = await WigaHttp.post('/portal/events/'+$wiga('#ModalConfirmPublish').attr('data-id')+'/publish');
        WigaHttp.handle(response,'#ModalConfirmPublish form',function(res) {
            $wiga('#ModalConfirmPublish').modal('hide');
            WigaClass.render();
            WigaNotify.showInline('#wiga-alert',{
                type: 'success',
                content: res.message,
            })
        },function(res){
            WigaNotify.showInline('#ModalConfirmPublishAlert',{
                type: 'danger',
                content: res.message,
            })
        });
    },
    async sendNotification()
    {

        let response = await WigaHttp.post('/portal/events/'+$wiga('#ModalNotification').attr('data-id')+'/send-notification');
        WigaHttp.handle(response,'#ModalNotification form',function(res) {
            $wiga('#ModalNotification').modal('hide');
            WigaClass.render();
            WigaNotify.showInline('#wiga-alert',{
                type: 'success',
                content: res.message,
            })
        },function(res){
            $wiga('#ModalNotification').modal('hide');
            WigaNotify.showInline('#wiga-alert',{
                type: 'danger',
                content: res.message,
            })
        });
    },
    async generateAttendance(id)
    {

        let response = await WigaHttp.post('/portal/events/'+id+'/generate-attendance');
        WigaHttp.handle(response,null,function(res) {
            let data = res.data
            $wiga('#ModalGenerateAttendance #content-attendance').html('');
            let url = WigaRoute.url('attendance_'+data.token)
            let text = `<span class="text-bold-600" style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">${url}</span>`;
            let btnCopy = WigaComponent.button({
                color: 'primary px-5',
                content: '<i class="fa-regular fa-copy"></i>',
                class: 'ms-auto',
                title: 'Copy Link',
                events: {
                    click: function(e) {
                        e.preventDefault();
                        $wiga(url).clipboard('Link pendaftaran')
                    }
                }
            })
            $wiga('#ModalGenerateAttendance #content-attendance').append(text,btnCopy);

            $wiga('#ModalGenerateAttendance #btn-qr').off('click')
            $wiga('#ModalGenerateAttendance #btn-qr').on('click',function(e){
                e.preventDefault();
                WigaRoute.redirect('attendance:'+data.token,true);
            })

            $wiga('#ModalGenerateAttendance').modal('show');
        },function(res){
            WigaNotify.showInline('#wiga-alert',{
                type: 'danger',
                content: res.message,
            })
        });

    }
})

$wiga('#ModalForm form').on('submit',function(e){
    e.preventDefault();
    if($wiga('#ModalForm').attr('data-id'))
    {
        $wiga('#ModalForm [type="submit"]').indicator(WigaClass.update());    
    }else{
        $wiga('#ModalForm [type="submit"]').indicator(WigaClass.store());
    }
})

$wiga('#ModalDelete form').on('submit',function(e){
    e.preventDefault();
    $wiga('#ModalDelete [type="submit"]').indicator(WigaClass.delete());    
})

$wiga('#ModalConfirmPublish form').on('submit',function(e){
    e.preventDefault();
    $wiga('#ModalConfirmPublish [type="submit"]').indicator(WigaClass.publish());    
})

$wiga('#ModalNotification form').on('submit',function(e){
    e.preventDefault();
    $wiga('#ModalNotification [type="submit"]').indicator(WigaClass.sendNotification());    
})

$wiga('#ModalForm [name=until_finish]').on('change',function(e){
    // $wiga(this).val(this.checked ? 1 : 0);
    $wiga('#ModalForm [name=end_time]').val('');
    if(this.checked){
        $wiga('#ModalForm [name=end_time]').parents('.form-floating').addClass('d-none')
    }else{
        $wiga('#ModalForm [name=end_time]').parents('.form-floating').removeClass('d-none')
    }
})

$wiga('#ModalForm [name=limited_quota]').on('change',function(e){
    // $wiga(this).val(this.checked ? 1 : 0);
    $wiga('#ModalForm [name=quota]').val('');
    if(this.checked){
        $wiga('#ModalForm [data-content="limited"]').removeClass('d-none')
    }else{
        $wiga('#ModalForm [data-content="limited"]').addClass('d-none')
    }
})

$wiga('#ModalForm [name=type]').on('change',function(e){
    // $wiga(this).val(this.checked ? 1 : 0);
    let typeValue = this.value
    $wiga('#ModalForm [data-content] textarea').val('')
    $wiga('#ModalForm [data-content]').addClass('d-none')
    $wiga('#ModalForm [data-content='+typeValue+']').removeClass('d-none')
})

$wiga('#ModalForm').on('hidden.bs.modal', function (e) {
    WigaForm.reset('#ModalForm form');
    $wiga('#ModalForm').attr('data-id',null);
    $wiga('#ModalFormAlert').html('');
    $wiga('#ModalForm [name=until_finish]').prop('checked',false).trigger('change');
    $wiga('#ModalForm [name=limited_quota]').prop('checked',false).trigger('change');
    $wiga('#ModalForm [name=is_paid]').prop('checked',false).trigger('change');
    $wiga('#ModalForm [name="category_ids[]"]').val(null).trigger('change');

})

$wiga('#ModalForm [name=is_paid]').on('change', function(e){
    $wiga('#ModalForm [name=registration_fee]').val('');
    if(this.checked){
        $wiga('#ModalForm [data-content="paid"]').removeClass('d-none');
    } else {
        $wiga('#ModalForm [data-content="paid"]').addClass('d-none');
    }
});
