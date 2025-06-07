let eventID = WigaRoute.segment(3)

let WigaClass = Wiga.class({
    render()
    {

        WigaTable.init({
            selector: '#table--content',
            responsive: true,
            ajax: {
                url: '/admin/events/'+eventID+'/committees'
            },
            columns: [
                { data: 'table.numbering' },
                { data: 'student_id'},
                { data: 'name' },
                { data: 'parallel_class' },
                { data: 'email' },
                { data: 'phone_number' },
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
                                    $wiga('#ModalForm [name=student_id]').val(data.student_id);
                                    $wiga('#ModalForm [name=name]').val(data.name);
                                    $wiga('#ModalForm [name=parallel_class]').val(data.parallel_class);
                                    $wiga('#ModalForm [name=email]').val(data.email);
                                    $wiga('#ModalForm [name=phone_number]').val(data.phone_number);
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

                    if(data.attendance)
                    {
                        userActions.push({
                            text: '<span class="badge badge-light-warning">Cetak Sertifikat</span>', 
                            events: {
                                click: function(e) {
                                    WigaRoute.redirect(data.event.slug+'/certificate/'+data.student_id);
                                }
                            }
                        });
                    }

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

        let response = await WigaHttp.post('/admin/events/'+eventID+'/committees', WigaForm.data('#ModalForm form'));
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

        let response = await WigaHttp.post('/admin/events/'+eventID+'/committees'+'/'+$wiga('#ModalForm').attr('data-id'), data);
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

        let response = await WigaHttp.delete('/admin/events/'+eventID+'/committees'+'/'+$wiga('#ModalDelete').attr('data-id'));
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