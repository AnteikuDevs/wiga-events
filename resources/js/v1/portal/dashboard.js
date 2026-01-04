

let WigaClass = Wiga.class({
    render()
    {

        WigaTable.init({
            selector: '#table--content',
            responsive: true,
            ajax: {
                url: '/portal/events',
            },
            columns: [
                { data: function(data){
                    return `<div class="img-fluid" wigaimage-lazyload="${WigaRoute.storageUrl(data.image_id)}">`
                }},
                { data: function(data){ 

                    let type = `<span class="badge badge-light-${data.type == 'offline' ? 'danger' : 'success'}">${data.type.toUpperCase()}</span>`

                    return `<a href="${WigaRoute.url('event/'+data.slug)}" class="fw-bold mb-2">${data.title}</a><br>${type}`
                }},
                { data: 'description' },
                { data: function(data){
                    return '<strong>Tanggal:</strong> ' + data.date_format + ' <br> <strong>Pukul:</strong> ' + data.time_format
                } },
                { data: function(data){
                    return data.participants_count + (data.quota > 0 ? ' / ' + data.quota : '') + ' Peserta'
                } },
                { data: function(data){
                    if(data.status_id == 1){
                        return `<span class="badge badge-light-danger">${data.status}</span>`
                    }else if(data.status_id == 2){
                        return `<span class="badge badge-light-success">${data.status}</span>`
                    }
                    return `<span class="badge badge-light-warning">${data.status}</span>`
                } },
            ],
            pageLength: 10,
            searching: true,
            toolbarSelector: '#toolbar-content',
            onDraw: function () {
                // console.log('Tabel di-draw ulang');
                WigaImageLazyload.render()
            }
        });

        this.renderSummary()

    },
    async renderSummary()
    {

        let response = await WigaHttp.get('/portal/dashboard');
        if(response.status)
        {
            let data = response.data;
            
            // 1. Update Card Total Event
            $('#total-event-count').text(data.event.total);
            $('#total-event-month').html(`<i class="bi bi-arrow-up me-1"></i> ${data.event.total_in_month} Acara baru bulan ini`);

            // 2. Update Card Rata-rata Kehadiran
            let attendanceVal = data.average_attendance.replace('%', ''); // Ambil angka saja
            $('#average-attendance-value').text(data.average_attendance);
            $('#average-attendance-bar').css('width', data.average_attendance);

            // 3. Update Card Total Peserta
            $('#participant-total-count').text(new Intl.NumberFormat().format(data.participant_total));
        }

    }
})
