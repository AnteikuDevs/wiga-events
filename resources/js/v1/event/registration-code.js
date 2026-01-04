let WigaClass = Wiga.class({
    async submit() {
        let response = await WigaHttp.post('/event/' + WigaRoute.segment(2), WigaForm.data('#form-payment'));
        WigaHttp.handle(response, '#form-payment', function(res) {
            $wiga('#ModalConfirm').modal('hide');
            

            WigaNotify.success(res.message,'_reg_/' + res.data.reg_code);

        }.bind(this), function(res) {
            WigaNotify.showInline('#alert-message', {
                type: 'danger',
                content: res.message,
            })
            $wiga('#ModalConfirm').modal('hide');
        });
    },
})

$wiga('#ModalConfirm form').on('submit', function(e) {
    e.preventDefault();
    $wiga('#ModalConfirm form [type="submit"]').indicator(WigaClass.submit());
})

$wiga('#btn-copy-link').on('click',function(e){
    e.preventDefault();
    $wiga(WigaRoute.url('_reg_/' + WigaRoute.segment(2))).clipboard('Link Bukti Pendaftaran');
})

$wiga('#btn-generate-cert').on('click',function(e){
    e.preventDefault();
    WigaRoute.redirect('_reg_/' + WigaRoute.segment(2)+'/certificate');
})