let WigaClass = Wiga.class({
    // 1. Inisialisasi awal halaman
    render() {
        this.fetchCategories();
        // this.setupListeners();
    },

    // 2. Ambil data kategori untuk Select2
    async fetchCategories() {
        let response = await WigaHttp.get('/portal/category/list');
        WigaHttp.handle(response, null, function(res) {
            let select = $('[name="category_ids[]"]').select2({
                placeholder: 'Pilih Kategori',
                allowClear: true,
                width: '100%',
                data: res.data.map(cat => ({id: cat.id, text: cat.name}))
            });
        });
    },

    // 3. Simpan data baru
    async store() {
        let response = await WigaHttp.post('/portal/events', WigaForm.data('#form-event-action'));
        WigaHttp.handle(response, '#form-event-action', function(res) {
            WigaNotify.success(res.message);
            // Redirect kembali ke index setelah 1.5 detik
            let data = res.data;
            setTimeout(() => {
                window.location.href = WigaRoute.url('/portal/events/'+ data.id + '/certificates');
            }, 1500);
        }, function(res) {
            WigaNotify.showInline('#wiga-alert', {
                type: 'danger',
                content: res.message
            });
        });
    },

});


$wiga('#form-event-action').on('submit', async function(e) {
    e.preventDefault();
    $wiga('#form-event-action [type="submit"]').indicator(_this.update());
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