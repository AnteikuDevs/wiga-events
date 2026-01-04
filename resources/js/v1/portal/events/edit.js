let WigaClass = Wiga.class({
    // 1. Inisialisasi awal halaman edit
    async render() {
        const eventId = WigaRoute.segment(3);
        await this.fetchCategories();
        await this.loadDetail(eventId);
        // this.setupListeners();
    },

    // 2. Ambil list kategori
    async fetchCategories() {
        let response = await WigaHttp.get('/portal/category/list');
        return new Promise((resolve) => {
            WigaHttp.handle(response, null, (res) => {
                $('[name="category_ids[]"]').select2({
                    placeholder: 'Pilih Kategori',
                    width: '100%',
                    data: res.data.map(cat => ({id: cat.id, text: cat.name}))
                });
                resolve();
            });
        });
    },

    // 3. Ambil data detail event dan isi ke form
    async loadDetail(id) {
        let response = await WigaHttp.get('/portal/events/' + id);
        WigaHttp.handle(response, null, (res) => {
            let data = res.data;

            // Preview Image
            WigaUploadImage.preview({ name: 'image', id: data.image_id, filename: data.image.name });

            // Fill Inputs
            $wiga('[name=title]').val(data.title);
            $wiga('[name=description]').val(data.description);
            $wiga('[name=start_time]').val(WigaString.formatForDateTimeLocal(data.start_time));
            
            // Multi-select Categories
            if(data.categories) {
                let selectedIds = data.categories.map(cat => cat.id);
                $wiga('[name="category_ids[]"]').val(selectedIds).trigger('change');
            }

            // Type Logic
            $wiga('[name=type]').val(data.type).trigger('change');
            if(data.type == 'online') $wiga('[name=link]').val(data.link);
            else $wiga('[name=location]').val(data.location);

            // Time & Registration
            if(data.registration_end) $wiga('[name=registration_end]').val(data.registration_end);
            if(data.end_time) {
                $wiga('[name=end_time]').val(WigaString.formatForDateTimeLocal(data.end_time));
                $wiga('[name=until_finish]').prop('checked', false).trigger('change');
            } else {
                $wiga('[name=until_finish]').prop('checked', true).trigger('change');
            }

            // Quota & Fee
            if(data.quota) {
                $wiga('[name=limited_quota]').prop('checked', true).trigger('change');
                $wiga('[name=quota]').val(data.quota);
            }
            if(data.registration_fee > 0) {
                $wiga('[name=is_paid]').prop('checked', true).trigger('change');
                $wiga('[name=registration_fee]').val(data.registration_fee);

                $wiga('[name=bank_name]').val(data.bank_name);
                $wiga('[name=bank_account_number]').val(data.bank_account_number);
                $wiga('[name=bank_account_name]').val(data.bank_account_name);
                $wiga('[data-content="paid"]').removeClass('d-none');
            }
        });
    },

    // 4. Proses Update (PUT)
    async update() {
        let eventId = WigaRoute.segment(3);
        let data = WigaForm.data('#form-event-action');
        
        data.append('_method', 'PUT');
        data.append('image_deleted', WigaUploadImage.deletedImage({name:'image'}).length ? 1 : 0);

        let response = await WigaHttp.post('/portal/events/' + eventId, data);
        WigaHttp.handle(response, '#form-event-action', (res) => {
            WigaNotify.success(res.message);
            setTimeout(() => { window.location.href = WigaRoute.url('/portal/events'); }, 1500);
        }, (res) => {
            WigaNotify.showInline('#wiga-alert', { type: 'danger', content: res.message });
        });
    },

    // setupListeners() {
    //     const _this = this;
    //     $wiga('#form-event-action').on('submit', async function(e) {
    //         e.preventDefault();
    //         $wiga('#form-event-action [type="submit"]').indicator(_this.update());
    //     });

    //     // $wiga('[name=until_finish]').on('change', function() {
    //     //     $wiga('[name=end_time]').val('').parents('.form-floating').toggleClass('d-none');
    //     // });

    //     $wiga('[name=limited_quota]').on('change', function() {
    //         $wiga('[name=quota]').val('');
    //         $wiga('[data-content="limited"]').toggleClass('d-none', !this.checked);
    //     });

    //     $wiga('[name=is_paid]').on('change', function() {
    //         $wiga('[name=registration_fee]').val('');
    //         if(this.checked) {
    //             $wiga('[data-content="paid"]').removeClass('d-none');
    //         }else{
    //             $wiga('[data-content="paid"]').addClass('d-none');
    //         }
    //     });

    //     $wiga('[name=type]').on('change', function() {
    //         let val = this.value;
    //         $wiga('[data-content="online"], [data-content="offline"]').addClass('d-none')
    //         $wiga('[data-content="online"], [data-content="offline"]').find('textarea').val('');
    //         $wiga(`[data-content="${val}"]`).removeClass('d-none');
    //     });

    //     $wiga('#form-event-action [name=until_finish]').on('change',function(e){
    //         // $wiga(this).val(this.checked ? 1 : 0);
    //         $wiga('#form-event-action [name=end_time]').val('');
    //         if(this.checked){
    //             $wiga('#form-event-action [name=end_time]').parents('.form-floating').addClass('d-none')
    //         }else{
    //             $wiga('#form-event-action [name=end_time]').parents('.form-floating').removeClass('d-none')
    //         }
    //     })
    // }
});



$wiga('#form-event-action').on('submit', async function(e) {
    e.preventDefault();
    $wiga('#form-event-action [type="submit"]').indicator(WigaClass.update());
});

// $wiga('[name=until_finish]').on('change', function() {
//     $wiga('[name=end_time]').val('').parents('.form-floating').toggleClass('d-none');
// });

$wiga('[name=limited_quota]').on('change', function() {
    $wiga('[name=quota]').val('');
    $wiga('[data-content="limited"]').toggleClass('d-none', !this.checked);
});

$wiga('[name=is_paid]').on('change', function() {
    $wiga('[name=registration_fee]').val('');
    if(this.checked) {
        $wiga('[data-content="paid"]').removeClass('d-none');
    }else{
        $wiga('[data-content="paid"]').addClass('d-none');
    }
});

$wiga('[name=type]').on('change', function() {
    let val = this.value;
    $wiga('[data-content="online"], [data-content="offline"]').addClass('d-none')
    $wiga('[data-content="online"], [data-content="offline"]').find('textarea').val('');
    $wiga(`[data-content="${val}"]`).removeClass('d-none');
});

$wiga('#form-event-action [name=until_finish]').on('change',function(e){
    // $wiga(this).val(this.checked ? 1 : 0);
    $wiga('#form-event-action [name=end_time]').val('');
    if(this.checked){
        $wiga('#form-event-action [name=end_time]').parents('.form-floating').addClass('d-none')
    }else{
        $wiga('#form-event-action [name=end_time]').parents('.form-floating').removeClass('d-none')
    }
})