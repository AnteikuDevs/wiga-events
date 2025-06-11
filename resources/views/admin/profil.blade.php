@extends('layouts.panel.index')

@section('content')

<div class="card mb-5 mb-xl-10">
    <div class="card-header border-0 cursor-pointer">
        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Profil</h3>
        </div>
    </div>
    
    <div class="card-body border-top p-9">
        <div class="row mb-6">
            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Nama Lengkap</label>
            <div class="col-lg-8">
                <x-forms.input type="text" name="name" label="Nama Lengkap" value="{{ auth()->user()->name }}"></x-forms.input>
            </div>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-end py-6 px-9">
        <x-button type="submit" size="" indicator>Simpan</x-button>
    </div>
</div>

<div class="card  mb-5 mb-xl-10">
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
        data-bs-target="#kt_account_signin_method">
        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Metode Masuk</h3>
        </div>
    </div>
    <div class="card-body border-top p-9">
        <div class="d-flex flex-wrap align-items-center mb-6">
            <div id="kt_signin_email" class="d-none">
                <div class="fs-6 fw-bold mb-1">Email</div>
                <div class="fw-semibold text-gray-600">support@keenthemes.com</div>
            </div>
            <div id="profile--email-edit" class="flex-row-fluid">
                <form id="" class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <div class="row mb-6">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label for="emailaddress" class="form-label fs-6 fw-bold mb-3">Enter New Email
                                    Address</label>
                                <input type="email" class="form-control form-control-lg form-control-solid"
                                    id="emailaddress" placeholder="Email Address" name="emailaddress"
                                    value="support@keenthemes.com">
                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label for="confirmemailpassword" class="form-label fs-6 fw-bold mb-3">Confirm
                                    Password</label>
                                <input type="password" class="form-control form-control-lg form-control-solid"
                                    name="confirmemailpassword" id="confirmemailpassword">
                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <button id="kt_signin_submit" type="button" class="btn btn-primary  me-2 px-6">Update
                            Email</button>
                        <button id="kt_signin_cancel" type="button"
                            class="btn btn-color-gray-500 btn-active-light-primary px-6">Cancel</button>
                    </div>
                </form>
            </div>
            <div id="kt_signin_email_button" class="ms-auto d-none">
                <button class="btn btn-light btn-active-light-primary">Change Email</button>
            </div>
        </div>
        <div class="d-flex flex-wrap align-items-center">
            <div id="kt_signin_email">
                <div class="fs-6 fw-bold mb-1">Username</div>
                <div class="fw-semibold text-gray-600">support@keenthemes.com</div>
            </div>
            <div id="profile--email-edit" class="flex-row-fluid">
                <form id="" class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <div class="row mb-6">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label for="emailaddress" class="form-label fs-6 fw-bold mb-3">Enter New Email
                                    Address</label>
                                <input type="email" class="form-control form-control-lg form-control-solid"
                                    id="emailaddress" placeholder="Email Address" name="emailaddress"
                                    value="support@keenthemes.com">
                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label for="confirmemailpassword" class="form-label fs-6 fw-bold mb-3">Confirm
                                    Password</label>
                                <input type="password" class="form-control form-control-lg form-control-solid"
                                    name="confirmemailpassword" id="confirmemailpassword">
                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <button id="kt_signin_submit" type="button" class="btn btn-primary  me-2 px-6">Update
                            Email</button>
                        <button id="kt_signin_cancel" type="button"
                            class="btn btn-color-gray-500 btn-active-light-primary px-6">Cancel</button>
                    </div>
                </form>
            </div>
            <div id="kt_signin_email_button" class="ms-auto d-none">
                <button class="btn btn-light btn-active-light-primary">Change Username</button>
            </div>
        </div>
        <div class="separator separator-dashed my-6"></div>
        <div class="d-flex flex-wrap align-items-center mb-10">
            <div id="kt_signin_password">
                <div class="fs-6 fw-bold mb-1">Password</div>
                <div class="fw-semibold text-gray-600">************</div>
            </div>
            <div id="kt_signin_password_edit" class="flex-row-fluid">
                <form id="kt_signin_change_password" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                    novalidate="novalidate">
                    <div class="row mb-1">
                        <div class="col-lg-4">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label for="currentpassword" class="form-label fs-6 fw-bold mb-3">Current
                                    Password</label>
                                <input type="password" class="form-control form-control-lg form-control-solid "
                                    name="currentpassword" id="currentpassword">
                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label for="newpassword" class="form-label fs-6 fw-bold mb-3">New Password</label>
                                <input type="password" class="form-control form-control-lg form-control-solid "
                                    name="newpassword" id="newpassword">
                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="fv-row mb-0 fv-plugins-icon-container">
                                <label for="confirmpassword" class="form-label fs-6 fw-bold mb-3">Confirm New
                                    Password</label>
                                <input type="password" class="form-control form-control-lg form-control-solid "
                                    name="confirmpassword" id="confirmpassword">
                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-text mb-5">Password must be at least 8 character and contain symbols</div>

                    <div class="d-flex">
                        <button id="kt_password_submit" type="button" class="btn btn-primary me-2 px-6">Update
                            Password</button>
                        <button id="kt_password_cancel" type="button"
                            class="btn btn-color-gray-500 btn-active-light-primary px-6">Cancel</button>
                    </div>
                </form>
            </div>
            <div id="kt_signin_password_button" class="ms-auto d-none">
                <button class="btn btn-light btn-active-light-primary">Reset Password</button>
            </div>
        </div>
        {{-- <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed  p-6">
            <i class="ki-duotone ki-shield-tick fs-2tx text-primary me-4"><span class="path1"></span><span
                    class="path2"></span></i>
            <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                <div class="mb-3 mb-md-0 fw-semibold">
                    <h4 class="text-gray-900 fw-bold">Amankan Akun Anda</h4>

                    <div class="fs-6 text-gray-700 pe-7">Otentikasi dua faktor menambahkan lapisan keamanan ekstra ke akun Anda. Untuk masuk, Anda juga perlu memberikan kode 6 digit.</div>
                </div>
                <a href="#" class="btn btn-primary px-6 align-self-center text-nowrap" data-bs-toggle="modal"
                    data-bs-target="#kt_modal_two_factor_authentication">
                    Enable </a>
            </div>
        </div> --}}
    </div>
</div>
@endsection
