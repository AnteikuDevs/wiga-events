@extends('layouts.main.index')
@section('content')

    <widget-login class="container">

        <div class="row justify-content-center">
            <div class="login-content">
                <div class="card login-box-container">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img src="{{ asset('logo-full.png') }}" alt="" width="300px">
                        </div>
                        <div class="text-center my-4">
                            <p class="mb-0">Silahkan Masuk</p>
                            <p class="mb-0">Untuk Melakukan Voting</p>
                        </div>
                        <div id="nexa-alert"></div>
                        <form action="" method="post">
                            {{-- <x-forms.input type="text" name="username" label="Username"></x-forms.input> --}}
                            <x-forms.input type="password" name="token" label="Token"></x-forms.input>
                            <x-button type="submit" color="gradient-primary" block>Masuk</x-button>
                        </form>
                        <div id="countdown"></div>
                    </div>
                </div>
            </div>
        </div>


    </widget-login>
@endsection