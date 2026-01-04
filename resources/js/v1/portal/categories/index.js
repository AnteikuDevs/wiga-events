let WigaClass = Wiga.class({
    render() {
        WigaTable.init({
            selector: '#table--content',
            responsive: true,
            ajax: {
                url: '/portal/category', // Endpoint API Category
            },
            columns: [
                { data: 'table.numbering' },
                { data: function(data) {
                    return `<span class="fw-bold text-dark fs-6">${data.name}</span>`;
                }},
                { data: function(data) {
                    return `<code class="bg-light-primary px-2 py-1 rounded text-primary small">${data.slug}</code>`;
                }},
                { data: function(data) {
                    return data.created_at_format;
                }},
                { 
                    data: function(data) {
                        return WigaComponent.dropdown({
                            triggerContent: '<i class="fa-duotone fa-gear"></i>',
                            color: 'light-primary',
                            items: [
                                { 
                                    text: '<span class="badge badge-light-primary">Edit</span>', 
                                    events: {
                                        click: function(e) {
                                            e.preventDefault();
                                            $wiga('#ModalForm [name=name]').val(data.name);
                                            $wiga('#ModalForm').attr('data-id', data.id);
                                            $wiga('#ModalForm').modal('show');
                                        }
                                    }
                                },
                                { 
                                    text: '<span class="badge badge-light-danger">Hapus</span>', 
                                    events: {
                                        click: function(e) {
                                            e.preventDefault();
                                            $wiga('#ModalDelete').attr('data-id', data.id);
                                            $wiga('#ModalDelete').modal('show');
                                        }
                                    }
                                }
                            ],
                        });
                    } 
                }
            ],
            pageLength: 10,
            searching: true,
            onDraw: function () {
                // Callback jika diperlukan
            }
        });
    },

    async store() {
        let response = await WigaHttp.post('/portal/category', WigaForm.data('#ModalForm form'));
        WigaHttp.handle(response, '#ModalForm form', function(res) {
            $wiga('#ModalForm').modal('hide');
            WigaClass.render();
            WigaNotify.showInline('#wiga-alert', {
                type: 'success',
                content: res.message,
            });
        }, function(res) {
            WigaNotify.showInline('#ModalFormAlert', {
                type: 'danger',
                content: res.message,
            });
        });
    },

    async update() {
        let data = WigaForm.data('#ModalForm form');
        data.append('_method', 'PUT');

        let id = $wiga('#ModalForm').attr('data-id');
        let response = await WigaHttp.post('/portal/category/' + id, data);
        
        WigaHttp.handle(response, '#ModalForm form', function(res) {
            $wiga('#ModalForm').modal('hide');
            WigaClass.render();
            WigaNotify.showInline('#wiga-alert', {
                type: 'success',
                content: res.message,
            });
        }, function(res) {
            WigaNotify.showInline('#ModalFormAlert', {
                type: 'danger',
                content: res.message,
            });
        });
    },

    async delete() {
        let id = $wiga('#ModalDelete').attr('data-id');
        let response = await WigaHttp.delete('/portal/category/' + id);
        
        WigaHttp.handle(response, '#ModalDelete form', function(res) {
            $wiga('#ModalDelete').modal('hide');
            WigaClass.render();
            WigaNotify.showInline('#wiga-alert', {
                type: 'success',
                content: res.message,
            });
        }, function(res) {
            WigaNotify.showInline('#ModalDeleteAlert', {
                type: 'danger',
                content: res.message,
            });
        });
    }
});

/**
 * Event Listeners
 */

$wiga('#ModalForm form').on('submit', function(e) {
    e.preventDefault();
    if ($wiga('#ModalForm').attr('data-id')) {
        $wiga('#ModalForm [type="submit"]').indicator(WigaClass.update());    
    } else {
        $wiga('#ModalForm [type="submit"]').indicator(WigaClass.store());
    }
});

$wiga('#ModalDelete form').on('submit', function(e) {
    e.preventDefault();
    $wiga('#ModalDelete [type="submit"]').indicator(WigaClass.delete());    
});

$wiga('#ModalForm').on('hidden.bs.modal', function (e) {
    WigaForm.reset('#ModalForm form');
    $wiga('#ModalForm').removeAttr('data-id');
    $wiga('#ModalFormAlert').html('');
});