let WigaClass = Wiga.class({
    render() {
        WigaTable.init({
            selector: '#table--content',
            responsive: true,
            ajax: {
                url: '/portal/users', // Endpoint API User
            },
            columns: [
                { data: 'table.numbering' },
                { data: function(data) {
                    return `
                        <div class="d-flex flex-column">
                            <span class="fw-bold text-dark fs-6">${data.name}</span>
                            <span class="text-muted small">${data.email}</span>
                        </div>
                    `;
                }},
                { data: function(data) {
                    return `<span class="badge badge-light-primary fw-bold">${data.username}</span>`;
                }},
                { data: 'agency_name' },
                { data: 'phone_number' },
                { data: function(data) {
                    // Logika Status Verifikasi (Acc)
                    if (data.status == 1) {
                        return `<span class="badge badge-light-success px-3 py-2">Disetujui</span>`;
                    } else if (data.status == 2) {
                        return `<span class="badge badge-light-danger px-3 py-2">Ditolak</span>`;
                    }
                    // Jika pending, tampilkan tombol untuk memicu modal verifikasi
                    return `
                        <button class="btn btn-sm btn-warning animate__animated animate__pulse animate__infinite" 
                                onclick="WigaClass.openVerify('${data.id}')">
                            Menunggu
                        </button>`;
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
                                            WigaClass.edit(data);
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
            searching: true
        });
    },

    edit(data) {
        WigaForm.reset('#ModalForm form');
        $wiga('#ModalForm [name=role_id]').val(data.role_id).trigger('change');
        $wiga('#ModalForm [name=name]').val(data.name);
        $wiga('#ModalForm [name=username]').val(data.username);
        $wiga('#ModalForm [name=email]').val(data.email);
        $wiga('#ModalForm [name=phone_number]').val(data.phone_number);
        $wiga('#ModalForm [name=agency_name]').val(data.agency_name);
        
        // Mode Edit: Password opsional
        $wiga('#password-note').show();
        $wiga('#ModalForm').attr('data-id', data.id);
        $wiga('#ModalForm').modal('show');
    },

    async store() {
        let response = await WigaHttp.post('/portal/users', WigaForm.data('#ModalForm form'));
        WigaHttp.handle(response, '#ModalForm form', function(res) {
            $wiga('#ModalForm').modal('hide');
            WigaClass.render();
            WigaNotify.success(res.message);
        }, function(res) {
            WigaNotify.showInline('#ModalFormAlert', { type: 'danger', content: res.message });
        });
    },

    async update() {
        let data = WigaForm.data('#ModalForm form');
        data.append('_method', 'PUT');
        let id = $wiga('#ModalForm').attr('data-id');
        
        let response = await WigaHttp.post('/portal/users/' + id, data);
        WigaHttp.handle(response, '#ModalForm form', function(res) {
            $wiga('#ModalForm').modal('hide');
            WigaClass.render();
            WigaNotify.success(res.message);
        }, function(res) {
            WigaNotify.showInline('#ModalFormAlert', { type: 'danger', content: res.message });
        });
    },

    // Fitur Verifikasi
    openVerify(id) {
        $wiga('#ModalVerify').attr('data-id', id);
        $wiga('#ModalVerify').modal('show');
    },

    async processVerify(action) {
        let id = $wiga('#ModalVerify').attr('data-id');
        let response = await WigaHttp.post(`/portal/users/${id}/verify`, { status: action });
        
        WigaHttp.handle(response, null, function(res) {
            $wiga('#ModalVerify').modal('hide');
            WigaClass.render();
            WigaNotify.success(res.message);
        }, function(res) {
            WigaNotify.showInline('#ModalVerifyAlert', { type: 'danger', content: res.message });
        });
    },

    async delete() {
        let id = $wiga('#ModalDelete').attr('data-id');
        let response = await WigaHttp.delete('/portal/users/' + id);
        WigaHttp.handle(response, '#ModalDelete form', function(res) {
            $wiga('#ModalDelete').modal('hide');
            WigaClass.render();
            WigaNotify.success(res.message);
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

$wiga('#ModalForm').on('hidden.bs.modal', function () {
    WigaForm.reset('#ModalForm form');
    $wiga('#ModalForm').removeAttr('data-id');
    $wiga('#password-note').hide();
    $wiga('#ModalFormAlert').html('');
    $wiga('#ModalForm [name=role_id]').val(null).trigger('change');
});

$wiga('#ModalVerify #btn--approve').on('click', function (e) {
    e.preventDefault();
    $wiga('#ModalVerify [type="submit"]').indicator(WigaClass.processVerify('approve'));
});

$wiga('#ModalVerify #btn--reject').on('click', function (e) {
    e.preventDefault();
    $wiga('#ModalVerify [type="submit"]').indicator(WigaClass.processVerify('reject'));
});