let WigaClass = Wiga.class({
    async submit() {
        // Mengambil semua data dari form (name, email, phone_number, agency_name, username, password)
        let response = await WigaHttp.post('/register', WigaForm.data('#WigaFormPage'));
        
        WigaHttp.handle(response, '#WigaFormPage', function(res) {
            // Tampilkan pesan sukses yang informatif
            WigaNotify.show({
                type: 'success',
                content: 'Pendaftaran berhasil! Akun Anda sedang dalam tahap verifikasi oleh Admin. Mohon tunggu informasi selanjutnya.',
            });

            // Kosongkan form
            WigaForm.reset('#WigaFormPage');

            // Opsional: Redirect ke halaman login setelah beberapa detik
            setTimeout(() => {
                window.location.href = WigaRoute.url('/login');
            }, 3000);

        }, function(res) {
            // Tampilkan error validasi (email sudah ada, password kurang panjang, dll)
            WigaNotify.showInline('#wiga-alert', {
                type: 'danger',
                content: res.message,
            });
        });
    }
});

$wiga('#WigaFormPage').on('submit', function(e) {
    e.preventDefault();
    $wiga('#WigaFormPage [type="submit"]').indicator(WigaClass.submit());
});