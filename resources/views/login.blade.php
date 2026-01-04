@extends('layouts.main.index')
@section('content')

<div class="login-page-wrapper d-flex align-items-center justify-content-center p-0 p-md-3">
    <widget-login class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-5 col-xl-4">
                
                <div class="text-center mb-5 animate__animated animate__fadeInDown">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('logo-full.png') }}" alt="Logo" class="img-fluid login-logo">
                    </a>
                </div>

                <div class="card login-card-modern border-0 shadow-lg animate__animated animate__fadeInUp">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-5">
                            <h3 class="fw-bold text-dark">Selamat Datang</h3>
                            <p class="text-muted">Silakan masuk ke akun Wiga Events Anda</p>
                        </div>

                        <div id="wiga-alert"></div>

                        <form action="" method="post" id="WigaFormPage" class="modern-form">
                            <div class="mb-4">
                                <x-forms.input type="text" name="username" label="Username" class="form-control-lg"></x-forms.input>
                            </div>
                            <div class="mb-4">
                                <x-forms.input type="password" name="password" label="Password" class="form-control-lg"></x-forms.input>
                            </div>
                            
                            <div class="d-grid gap-2 mt-5">
                                <x-button type="submit" color="primary" size="lg" indicator block class="rounded-pill shadow-sm">
                                    Masuk
                                </x-button>
                            </div>

                            <div class="text-center mt-4">
                                <span class="text-muted small">Belum punya akun?</span>
                                <a href="{{ url('/register') }}" class="btn btn-link btn-sm text-primary fw-bold p-0 ms-1">Daftar Sekarang</a>
                            </div>
                        </form>
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