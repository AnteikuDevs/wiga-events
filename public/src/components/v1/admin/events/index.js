
let WigaClass = Wiga.class({
    render()
    {

        WigaTable.init({
            selector: '#table--content',
            responsive: true,
            ajax: {
                url: '/admin/events',
            },
            columns: [
                { data: 'table.numbering' },
                { data: function(data){
                    return `<div class="img-fluid" wigaimage-lazyload="${WigaRoute.storageUrl(data.image_id)}">`
                }},
                { data: function(data){ 

                    let type = `<span class="badge badge-light-${data.type == 'offline' ? 'danger' : 'success'}">${data.type.toUpperCase()}</span>`

                    return `<a href="${WigaRoute.url(data.slug)}" class="fw-bold mb-2">${data.title}</a><br>${type}`
                }},
                { data: 'description' },
                { data: function(data){
                    return '<strong>Tanggal:</strong> ' + data.date_format + ' <br> <strong>Pukul:</strong> ' + data.time_format
                } },
                { data: function(data){
                    return data.participants_count + ' Peserta'
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
                            ...(data.status_id == '1' && data.status_publish == '0'? [{
                                text: '<span class="badge badge-primary">Generate Link Kehadiran</span>', 
                                events: {
                                    click: function(e) {
                                        e.preventDefault();
                                        WigaClass.generateAttendance(data.id)
                                    }
                                }
                            }] : []),
                            ...(data.status_publish == '0'? [{
                                text: '<span class="badge badge-secondary">Salin Link Pendaftaran</span>', 
                                events: {
                                    click: function(e) {
                                        e.preventDefault();
                                        navigator.clipboard.writeText(WigaRoute.url(data.slug));
                                            alert('Link berhasil disalin');
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
                            { 
                                text: '<span class="badge badge-light-info">Panitia Acara</span>',
                                href: WigaRoute.url('/admin/events/'+data.id+'/committees')
                                // events: {
                                //     click: function(e) {
                                //         e.preventDefault();
                                //         WigaRoute.redirect('/admin/events/'+data.id+'/committees');
                                //     }
                                // }
                            },
                            { 
                                text: '<span class="badge badge-light-warning">Peserta</span>',
                                href: WigaRoute.url('/admin/events/'+data.id+'/participants')
                                // events: {
                                //     click: function(e) {
                                //         e.preventDefault();
                                //         WigaRoute.redirect('/admin/events/'+data.id+'/participants');
                                //     }
                                // }
                            },
                            
                            { 
                                text: '<span class="badge badge-light-primary">Edit</span>', 
                                events: {
                                    click: function(e) {

                                        WigaUploadImage.preview({
                                            name: 'image',
                                            id: data.image_id,
                                            filename: data.image.name
                                        })

                                        $wiga('#ModalForm [name=title]').val(data.title);
                                        $wiga('#ModalForm [name=type]').val(data.type).trigger('change');
                                        if(data.type == 'online')
                                        {
                                            $wiga('#ModalForm [name=link]').val(data.link);
                                        }else if(data.type == 'offline')
                                        {
                                            $wiga('#ModalForm [name=location]').val(data.location);
                                        }
                                        $wiga('#ModalForm [name=description]').val(data.description);
                                        $wiga('#ModalForm [name=start_time]').val(WigaString.formatForDateTimeLocal(data.start_time));
                                        if(data.end_time)
                                        {
                                            $wiga('#ModalForm [name=end_time]').val(WigaString.formatForDateTimeLocal(data.end_time));
                                        }else{
                                            $wiga('#ModalForm [name=until_finish]').prop('checked',true).trigger('change');
                                        }
                                        $wiga('#ModalForm').attr('data-id',data.id);
                                        $wiga('#ModalForm').modal('show');
                                    }
                                }
                            }
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

    },
    async store()
    {

        let response = await WigaHttp.post('/admin/events', WigaForm.data('#ModalForm form'));
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

        let response = await WigaHttp.post('/admin/events/'+$wiga('#ModalForm').attr('data-id'), data);
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

        let response = await WigaHttp.delete('/admin/events/'+$wiga('#ModalDelete').attr('data-id'));
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

        let response = await WigaHttp.post('/admin/events/'+$wiga('#ModalConfirmPublish').attr('data-id')+'/publish');
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

        let response = await WigaHttp.post('/admin/events/'+$wiga('#ModalNotification').attr('data-id')+'/send-notification');
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

        let response = await WigaHttp.post('/admin/events/'+id+'/generate-attendance');
        WigaHttp.handle(response,null,function(res) {
            let data = res.data
            $wiga('#ModalGenerateAttendance #content-attendance').html('');
            let url = WigaRoute.url('attendance_'+data.token)
            let text = `<span class="text-bold-600" style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden;max-width: 325px">${url}</span>`;
            let btnCopy = WigaComponent.button({
                color: 'light-primary',
                content: 'Copy Link',
                class: 'ms-auto',
                events: {
                    click: function(e) {
                        e.preventDefault();
                        navigator.clipboard.writeText(url);
                        WigaNotify.showInline('#alert--inline-copy',{
                            type: 'success',
                            content: 'Link berhasil disalin',
                        })
                    }
                }
            })
            $wiga('#ModalGenerateAttendance #content-attendance').append(text,btnCopy);

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
    if(this.checked){
        $wiga('#ModalForm [name=end_time]').parents('.form-floating').addClass('d-none')
        $wiga('#ModalForm [name=end_time]').val('');
    }else{
        $wiga('#ModalForm [name=end_time]').parents('.form-floating').removeClass('d-none')
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
    $wiga('#ModalForm [name=until_finish]').trigger('change');
    $wiga('#ModalForm [name=until_finish]').prop('checked',false).trigger('change');
})