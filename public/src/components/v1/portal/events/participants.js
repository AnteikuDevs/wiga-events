let eventID = WigaRoute.segment(3)

let WigaClass = Wiga.class({
    render()
    {

        WigaTable.init({
            selector: '#table--content',
            responsive: true,
            ajax: {
                url: '/portal/events/'+eventID+'/participants'
            },
            columns: [
                { data: 'table.numbering' },
                { data: function(val){
                    return val.name + '<br><small class="text-muted">' + val.email + '</small><br>' + (val.type == 'participant' ? '<span class="badge badge-light-primary">Peserta</span>' : '<span class="badge badge-light-danger">Panitia Acara</span>')
                } },
                { data: 'agency'},
                { data: 'phone_number' },
                { data: function(data){
                    let btnCpid = '__'+WigaString.rand(7)
                    $wiga('#'+btnCpid).on('click',function(e){
                        $wiga(data.reg_code).clipboard('Kode pendaftaran')
                    })

                    let statusId = '__' + WigaString.rand(6)

                    let status = `<span class="badge badge-light-danger mt-2 cursor-pointer user-select-none" id="${statusId}">Belum Terdaftar</span>`

                    $wiga('#'+statusId).on('click',function(e){
                        $wiga('#ModalVerify').attr('data-id',data.id);
                        if(data.proof_of_payment)
                        {
                            $wiga('#ModalVerify #content--proof-of-payment').html(`<div wigaimage-lazyload="${WigaRoute.storageUrl(data.proof_of_payment_id)}" class="card-img-top" style="width: 100%"></div>`).addClass('mb-4').removeClass('d-none')
                        }else{
                            $wiga('#ModalVerify #content--proof-of-payment').addClass('d-none')
                        }
                        $wiga('#ModalVerify').modal('show');
                        WigaImageLazyload.render();
                    })

                    if(data.status)
                    {
                        status = `<span class="badge badge-light-success mt-2">Terdaftar</span>`
                    }



                    return `<span class="badge badge-light-primary">${data.reg_code? data.reg_code : '-'} <i class="far fa-copy ms-2" id="${btnCpid}" title="Copy"></i></span><br>` + status
                } },
                { data: function(data){
                    if(data.attendance)
                    {
                        return `<span class="badge badge-light-success">Hadir</span>`
                    }

                    return `<span class="badge badge-light-warning">Belum Hadir / Tidak Hadir</span>`
                } },
                { data: function(data){

                    const userActions = [
                        { 
                            text: '<span class="badge badge-light-primary">Edit</span>', 
                            events: {
                                click: function(e) {
                                    $wiga('#ModalForm [name=name]').val(data.name);
                                    $wiga('#ModalForm [name=agency]').val(data.agency);
                                    $wiga('#ModalForm [name=email]').val(data.email);
                                    $wiga('#ModalForm [name=phone_number]').val(data.phone_number);
                                    $wiga('#ModalForm [name=type]').val(data.type).trigger('change');
                                    $wiga('#ModalForm').attr('data-id',data.id);
                                    $wiga('#ModalForm').modal('show');
                                }
                            }
                        },
                        { 
                            text: '<span class="badge badge-light-danger">Hapus</span>', 
                            events: {
                                click: function(e) {
                                    $wiga('#ModalDelete').attr('data-id',data.id);
                                    $wiga('#ModalDelete').modal('show');
                                }
                            }
                        },
                    ];

                    // if(data.attendance)
                    // {
                        userActions.push({
                            text: '<span class="badge badge-light-warning">Cetak Sertifikat</span>', 
                            href: WigaRoute.url('/certificate/cert-'+WigaString.rtrim(btoa(data.id),'=')),
                            // events: {
                            //     click: function(e) {
                            //         WigaRoute.redirect(data.event.slug+'/certificate/'+data.student_id);
                            //     }
                            // }
                        });
                    // }

                    return WigaComponent.dropdown({
                        triggerContent: '<i class="fa-duotone fa-gear"></i>',
                        color: 'light-primary',
                        items: userActions,
                        
                    });
                } }
            ],
            pageLength: 10,
            searching: true,
            toolbarSelector: '#toolbar-content',
            onDraw: function () {
                // console.log('Tabel di-draw ulang');
            }
        });

    },
    async store()
    {

        let response = await WigaHttp.post('/portal/events/'+eventID+'/participants', WigaForm.data('#ModalForm form'));
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

        let response = await WigaHttp.post('/portal/events/'+eventID+'/participants'+'/'+$wiga('#ModalForm').attr('data-id'), data);
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

        let response = await WigaHttp.delete('/portal/events/'+eventID+'/participants'+'/'+$wiga('#ModalDelete').attr('data-id'));
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

    async processVerify(action) {
        let id = $wiga('#ModalVerify').attr('data-id');
        let response = await WigaHttp.post(`/portal/events/${WigaRoute.segment(3)}/participant-verify/${id}`, { status: action });
        
        WigaHttp.handle(response, null, function(res) {
            $wiga('#ModalVerify').modal('hide');
            WigaClass.render();
            WigaNotify.success(res.message);
        }, function(res) {
            WigaNotify.showInline('#ModalVerifyAlert', { type: 'danger', content: res.message });
        });
    },
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

$wiga('#ModalForm [name=until_finish]').on('change',function(e){
    // $wiga(this).val(this.checked ? 1 : 0);
    if(this.checked){
        $wiga('#ModalForm [name=end_time]').parents('.form-floating').addClass('d-none')
        $wiga('#ModalForm [name=end_time]').val('');
    }else{
        $wiga('#ModalForm [name=end_time]').parents('.form-floating').removeClass('d-none')
    }
})

$wiga('#ModalForm').on('hidden.bs.modal', function (e) {
    WigaForm.reset('#ModalForm form');
    $wiga('#ModalForm').removeAttr('data-id');
    $wiga('#ModalFormAlert').html('');
    $wiga('#ModalForm [name=until_finish]').trigger('change');
    $wiga('#ModalForm [name=until_finish]').prop('checked',false).trigger('change');
})

$wiga('#ModalVerify #btn--confirm').on('click',function(e){
    e.preventDefault();
    $wiga('#ModalVerify [type="submit"]').indicator(WigaClass.processVerify('approve'));
})
$wiga('#ModalVerify #btn--reject').on('click',function(e){
    e.preventDefault();
    $wiga('#ModalVerify [type="submit"]').indicator(WigaClass.processVerify('reject'));
})