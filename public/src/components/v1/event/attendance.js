
let WigaClass = Wiga.class({
    render()
    {

        let sessAttendance = WigaCookie.get(WigaRoute.segment(1));

        let successHtml = $wiga('#success-attendance').html();
        let attendanceHtml = $wiga('#attendance-template').html();

        let content = attendanceHtml

        if(sessAttendance)
        {
            content = successHtml

        }
        
        $wiga('#page--content').html(content);

    },
    async store()
    {

        let response = await WigaHttp.post('/event/attendance', {
            token: WigaRoute.segment(1).replace('attendance_',''),
            student_id: $wiga('#WigaFormPage [name=student_id]').val(),
        });
        WigaHttp.handle(response,'#WigaFormPage',function(res) {
            // WigaNotify.showInline('#wiga-alert',{
            //     type: 'success',
            //     content: res.message,
            // })

            WigaCookie.set(WigaRoute.segment(1), res.data.id + '' + res.data.participant_id + '' + res.data.event_id);

            WigaClass.render();
        },function(res){
            WigaNotify.showInline('#wiga-alert',{
                type: 'danger',
                content: res.message,
            })
        });

    },
})


$wiga('#WigaFormPage').on('submit',function(e){
    e.preventDefault();
    $wiga('#WigaFormPage [type="submit"]').indicator(WigaClass.store());    
})