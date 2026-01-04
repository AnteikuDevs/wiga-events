
let WigaClass = Wiga.class({
    render()
    {

        WigaTable.init({
            selector: '#table--content',
            responsive: true,
            ajax: {
                url: '/portal/events/certificates',
                data: {
                    event: WigaRoute.segment(3)
                }
            },
            columns: [
                { data: 'table.numbering' },
                { data: function(data){
                    return `${data.name} <br> <span class="badge badge-light-primary">Model ${data.model_id}</span>`
                }},
                { data: function(data){
                    return `<div class="img-fluid" wigaimage-lazyload="${WigaRoute.storageUrl(data.image_id)}">`
                }},
                { data: 'certificate_number' },
                { data: function(data){
                    let statusDefault = `<span class="badge badge-light-primary">Default</span>`;
                    if(data.is_default == 0)
                    {
                        let statusId = '__' + WigaString.rand(6)
                        $wiga('#'+statusId).on('click',function(e){
                            $wiga('#ModalSetDefault').attr('data-id',data.id);
                            $wiga('#ModalSetDefault').modal('show');
                        })
                        statusDefault = `<span class="badge badge-light-danger cursor-pointer" id="${statusId}">Bukan Default</span>`
                    }
                    return statusDefault
                }},
                { data: function(data){


                    const userActions = [
                        { 
                            text: '<span class="badge badge-light-primary">Edit</span>', 
                            events: {
                                click: function(e) {

                                    WigaUploadImage.clear({
                                        name: 'image'
                                    });

                                    WigaUploadImage.preview({
                                        name: 'image',
                                        id: data.image_id,
                                        filename: data.image.name
                                    })
                                    
                                    $wiga('#ModalForm [name=name]').val(data.name);
                                    $wiga('#ModalForm [name=model][value="'+data.model_id+'"]').trigger('click');
                                    $wiga('#ModalForm [name=certificate_number]').val(data.certificate_number);
                                    $wiga('#ModalForm [name=certificate_as]').val(data.certificate_as);

                                    $wiga('#ModalForm #btn-cert-preview').attr('image-id',data.image_id);

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
                        { 
                            text: '<span class="badge badge-info">Preview</span>', 
                            events: {
                                click: function(e) {
                                    let form = WigaForm.data()
                                    form.append('event',WigaRoute.segment(3));
                                    form.append('model',data.model_id);
                                    form.append('image_id',data.image_id);
                                    form.append('certificate_number',data.certificate_number);
                                    form.append('certificate_as',data.certificate_as);
                                    WigaForm.postBlank('/portal/events/certificates/preview',form);
                                }
                            }
                        },
                    ];

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
                WigaImageLazyload.render()
            }
        });

    },
    async store()
    {
        let data = WigaForm.data('#ModalForm form')
        data.append('event',WigaRoute.segment(3))
        let response = await WigaHttp.post('/portal/events/certificates', data);
        WigaHttp.handle(response,'#ModalForm form',function(res) {
            $wiga('#ModalForm').modal('hide');
            WigaClass.render();
            WigaNotify.showInline('#wiga-alert',{
                type: 'success',
                content: res.message,
            })
            $wiga('#ModalForm').removeAttr('data-id');
            WigaUploadImage.clear({
                name: 'image'
            });
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
        data.append('event',WigaRoute.segment(3))
        data.append('_method','PUT');

        let response = await WigaHttp.post('/portal/events/certificates/'+$wiga('#ModalForm').attr('data-id'), data);
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

        let response = await WigaHttp.delete('/portal/events/certificates/'+$wiga('#ModalDelete').attr('data-id'));
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
    async setDefault()
    {
        let response = await WigaHttp.post('/portal/events/certificates/'+$wiga('#ModalSetDefault').attr('data-id')+'/set-default');
        WigaHttp.handle(response,'#ModalSetDefault form',function(res) {
            $wiga('#ModalSetDefault').modal('hide');
            WigaClass.render();
            WigaNotify.showInline('#wiga-alert',{
                type: 'success',
                content: res.message,
            })
        },function(res){
            WigaNotify.showInline('#ModalSetDefaultAlert',{
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
$wiga('#ModalSetDefault form').on('submit',function(e){
    e.preventDefault();
    $wiga('#ModalSetDefault [type="submit"]').indicator(WigaClass.setDefault());    
})

$wiga('#ModalForm').on('hidden.bs.modal', function (e) {
    WigaForm.reset('#ModalForm form');
    $wiga('#ModalForm').removeAttr('data-id');
    $wiga('#ModalForm #btn-cert-preview').removeAttr('image-id')
    $wiga('#ModalFormAlert').html('');
    WigaUploadImage.clear({
        name: 'image'
    });
});

$wiga('#ModalForm #btn-cert-preview').on('click', function(e){
    e.preventDefault()
    let data = WigaForm.data('#ModalForm form')
    data.append('event',WigaRoute.segment(3));
    if($wiga(this).attr('image-id')) data.append('image_id', $wiga(this).attr('image-id'));
    WigaForm.postBlank('/portal/events/certificates/preview',data);
})