@extends('layouts.main.index')
@section('content')

    <widget-login class="container">

        <div class="row justify-content-center gap-3">
            <div class="login-content">
                <div class="card login-box-container">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img src="{{ asset('logo-full.png') }}" alt="" width="300px">
                        </div>
                        <div class="text-center my-4">
                            <h5 class="mb-0">Selamat Datang di Wiga Events</h5>
                            <p class="mb-0">Masuk untuk melanjutkan aktivitas Anda.</p>
                        </div>
                        <div id="wiga-alert"></div>
                        <form action="" method="post" id="WigaFormPage">
                            <x-forms.input type="text" name="username" label="Username"></x-forms.input>
                            <x-forms.input type="password" name="password" label="Password"></x-forms.input>
                            <x-button type="submit" color="gradient-primary" size="" indicator block>Masuk</x-button>
                        </form>
                        {{-- <p class="text-center mt-4">
                            Belum punya akun? <a href="{{ route('register') }}" class="text-primary">Daftar Sekarang</a>
                        </p> --}}
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-center justify-content-md-between flex-column flex-md-row py-3 py-lg-6 container-fluid">
                {{-- <div class="text-dark mx-auto">
                    &copy; <a href="https://instagram.com/teguhdevs" target="_blank" class="text-danger"><strong>AnteikuDevs</strong></a>. All rights reserved.
                </div> --}}
                <div class="text-dark dark:text-light mx-auto">
                    Made by ❤️ <a href="https://instagram.com/anteikudevs" target="_blank" class="text-danger">AnteikuDevs</a>
                </div>
            </div>
        </div>

    </widget-login>
@endsection