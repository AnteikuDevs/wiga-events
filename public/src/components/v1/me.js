
Wiga.class({
    async render(){
        let response = await WigaHttp.post('me');
        WigaHttp.handle(response,'#WigaFormPage',function(res) {
            let data = res.data
            $wiga('[wiga-auth=name]').html(data.name);
            $wiga('[wiga-auth=email]').html(data.email);
        },function(res){
            Wiga.log(res)
            WigaNotify.showInline('#wiga-alert',{
                type: 'danger',
                content: res.message,
            })
        });
    }
})