@extends('layouts.panel.index')

@section('content')

<wigatable-toolbar data-table="#table--content">
    <x-button color="primary" toggle-modal="ModalForm" size="md">
        <i class="fa-solid fa-user-plus me-2"></i> Tambah Pengguna
    </x-button>
</wigatable-toolbar>

<div id="wiga-alert"></div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-row-dashed align-middle my-0" id="table--content">
            <thead>
                <tr class="text-start text-dark fw-bold fs-7 text-uppercase bg-light">
                    <th width="50px">No</th>
                    <th>Nama & Email</th>
                    <th>Username</th>
                    <th>Instansi</th>
                    <th>WhatsApp</th>
                    <th width="120px">Status Acc</th>
                    <th width="100px" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                </tbody>
        </table>
    </div>
</div>

<x-modal 
    id="ModalForm"
    title="Form Pengguna"
    withForm="true"
    size="lg">

    <div id="ModalFormAlert"></div>

    <div class="row g-3">
        <div class="col-12">
            <x-forms.input type="select" name="role_id" label="Role / Hak Akses" data-control="select2" data-placeholder="Pilih Role">
                <option value=""></option>
                <option value="1">Super Admin</option>
                <option value="2">Pengguna</option>
            </x-forms.input>
        </div>
        <div class="col-md-6">
            <x-forms.input type="text" name="name" label="Nama Lengkap" placeholder="Masukkan nama lengkap"></x-forms.input>
        </div>
        <div class="col-md-6">
            <x-forms.input type="text" name="username" label="Username" placeholder="Contoh: anteikudevs" autocomplete="off"></x-forms.input>
        </div>
        <div class="col-md-6">
            <x-forms.input type="email" name="email" label="Alamat Email" placeholder="email@contoh.com"></x-forms.input>
        </div>
        <div class="col-md-6">
            <x-forms.input type="text" name="phone_number" label="Nomor WhatsApp" placeholder="0812xxxx"></x-forms.input>
        </div>
        <div class="col-12">
            <x-forms.input type="text" name="agency_name" label="Nama Instansi/Perusahaan" placeholder="Contoh: ITB Widya Gama Lumajang"></x-forms.input>
        </div>
        <div class="col-12" id="password-container">
            <x-forms.input type="password" name="password" label="Password" placeholder="********" autocomplete="new-password"></x-forms.input>
            <small class="text-muted mt-1 d-block" id="password-note" style="display:none;">Kosongkan jika tidak ingin mengubah password</small>
        </div>
    </div>

    <x-slot name="footer">
        <x-button dismiss-modal color="secondary">Batal</x-button>
        <x-button type="submit" color="primary" indicator>Simpan Data</x-button>
    </x-slot>

</x-modal>

<x-modal 
    id="ModalVerify"
    title="Verifikasi Akun"
    withForm="true">

    <div id="ModalVerifyAlert"></div>
    
    <div class="text-center mb-4">
        <i class="fa-solid fa-user-check fs-3x text-primary mb-3"></i>
        <h5 class="fw-bold">Konfirmasi Status Akun</h5>
        <p class="text-muted">Tentukan apakah akun ini disetujui untuk mengakses portal atau ditolak.</p>
    </div>

    <div class="d-flex flex-column gap-3">
        <x-button type="button" color="success" class="w-100 py-3" id="btn--approve">
            <i class="fa-solid fa-check-circle me-2"></i> Setujui (Acc) Akun
        </x-button>
        <x-button type="button" color="danger" class="w-100 py-3" id="btn--reject">
            <i class="fa-solid fa-times-circle me-2"></i> Tolak Akun
        </x-button>
    </div>

    {{-- <x-slot name="footer">
        <x-button dismiss-modal color="secondary">Tutup</x-button>
    </x-slot> --}}

</x-modal>

<x-modal 
    id="ModalDelete"
    title="Hapus Pengguna"
    withForm="true">
    <div id="ModalDeleteAlert"></div>
    <x-alert type="warning" :dismissible="false">
        Apakah anda yakin ingin menghapus pengguna ini secara permanen?
    </x-alert>
    <x-slot name="footer">
        <x-button dismiss-modal color="secondary">Batal</x-button>
        <x-button type="submit" color="danger" indicator>Hapus</x-button>
    </x-slot>
</x-modal>

@endsection