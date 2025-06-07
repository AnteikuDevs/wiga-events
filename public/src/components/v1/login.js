
let WigaClass = Wiga.class({
    async submit(){
        let response = await WigaHttp.post('login', WigaForm.json('#WigaFormPage'));
        WigaHttp.handle(response,'#WigaFormPage',function(res) {
            WigaSigned(res.data)
            WigaRoute.reload()
        },function(res){
            Wiga.log(res)
            WigaNotify.showInline('#wiga-alert',{
                type: 'danger',
                content: res.message,
            })
        });
    }
})

$wiga('#WigaFormPage').on('submit', function(e) {
    e.preventDefault();
    $wiga('#WigaFormPage [type="submit"]').indicator(WigaClass.submit());
})