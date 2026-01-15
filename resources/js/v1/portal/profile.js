/**
 * Lanjutan WigaClass untuk handle Profil dan Keamanan
 */
let WigaClass = Wiga.class({
    async updateProfileBasic() {
        let formSelector = '#form-profile-basic';
        let data = WigaForm.data(formSelector);
        
        // Menggunakan endpoint API Profile yang sudah dibuat di controller
        let response = await WigaHttp.post('/portal/profile', data);
        
        WigaHttp.handle(response, formSelector, function(res) {
            WigaNotify.success(res.message);
            
            // Opsional: Update teks nama di sidebar/header secara real-time tanpa reload
            $wiga('.profile-name-display').text($wiga(`${formSelector} [name=name]`).val());
            
            // Refresh halaman setelah beberapa detik untuk memperbarui state auth laravel (opsional)
            setTimeout(() => window.location.reload(), 1500);
        }, function(res) {
            WigaNotify.showInline('#alert-profile', { type: 'danger', content: res.message });
        });
    },

    // Fungsi Update Password
    async updateProfilePassword() {
        let formSelector = '#form-profile-password';
        let data = WigaForm.data(formSelector);
        
        let response = await WigaHttp.post('/portal/profile/password', data);
        
        WigaHttp.handle(response, formSelector, function(res) {
            WigaNotify.success(res.message);
            WigaForm.reset(formSelector); // Reset form setelah password berhasil diubah
        }, function(res) {
            // Tampilkan pesan error jika password lama salah atau konfirmasi tidak cocok
            WigaNotify.showInline('#alert-password', { type: 'danger', content: res.message });
        });
    }
})

/**
 * Event Listeners untuk Form Profil
 */

// Listener Form Profil Dasar
$wiga('#form-profile-basic').on('submit', function(e) {
    e.preventDefault();
    // Menggunakan animasi indicator pada tombol submit
    $wiga('#btn-save-profile').indicator(WigaClass.updateProfileBasic());
});

// Listener Form Password
$wiga('#form-profile-password').on('submit', function(e) {
    e.preventDefault();
    $wiga('#btn-update-password').indicator(WigaClass.updateProfilePassword());
});