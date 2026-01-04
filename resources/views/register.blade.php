@extends('layouts.main.index')
@section('content')

<div class="login-page-wrapper d-flex align-items-center justify-content-center py-5 p-0 p-md-3">
    <widget-login class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-7 col-xl-6">
                
                <div class="text-center mb-5 animate__animated animate__fadeInDown">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('logo-full.png') }}" alt="Logo" class="img-fluid login-logo" style="max-width: 250px;">
                    </a>
                </div>

                <div class="card login-card-modern border-0 shadow-lg animate__animated animate__fadeInUp">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-5">
                            <h3 class="fw-bold text-dark">Pendaftaran Akun Mitra</h3>
                            <p class="text-muted">Lengkapi data di bawah untuk mulai mengelola event Anda.</p>
                        </div>

                        <div id="wiga-alert"></div>

                        <form action="" method="post" id="WigaFormPage" class="modern-form">
                            
                            <h5 class="fw-bold text-primary mb-4"><i class="fa-solid fa-circle-info me-2"></i>Informasi Profil</h5>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <x-forms.input type="text" name="name" label="Nama Lengkap" placeholder="Masukkan nama asli"></x-forms.input>
                                </div>
                                <div class="col-md-6">
                                    <x-forms.input type="email" name="email" label="Alamat Email" placeholder="nama@email.com"></x-forms.input>
                                </div>
                                <div class="col-md-6">
                                    <x-forms.input type="text" name="phone_number" label="Nomor WhatsApp" placeholder="0812xxxxxxxx"></x-forms.input>
                                </div>
                                <div class="col-md-6">
                                    <x-forms.input type="text" name="agency_name" label="Nama Instansi / Organisasi" placeholder="Contoh: BEM Widya Gama"></x-forms.input>
                                </div>
                            </div>

                            <hr class="text-muted opacity-25 mb-4">
                            
                            <h5 class="fw-bold text-primary mb-4"><i class="fa-solid fa-lock me-2"></i>Keamanan Akun</h5>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <x-forms.input type="text" name="username" label="Username" autocomplete="off"/>
                                </div>
                                <div class="col-md-6">
                                    <x-forms.input type="password" name="password" label="Password" autocomplete="new-password"/>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-5">
                                <x-button type="submit" color="primary" size="lg" indicator block class="rounded-pill shadow-sm">
                                    Daftar Sekarang
                                </x-button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <p class="text-muted mb-0">Sudah memiliki akun?</p>
                            <a href="{{ route('login') }}" class="text-primary fw-bolder text-decoration-none">Masuk ke Portal</a>
                        </div>
                    </div>
                </div>

                <div class="mt-5 text-center footer-text animate__animated animate__fadeIn">
                    <p class="text-muted small">
                        Made with ❤️ by <a href="https://instagram.com/anteikudevs" target="_blank" class="text-decoration-none fw-bold text-danger">AnteikuDevs</a>
                    </p>
                </div>

            </div>
        </div>
    </widget-login>
</div>

@endsection