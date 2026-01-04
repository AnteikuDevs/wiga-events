@extends('layouts.panel.index')

@section('content')

<wigatable-toolbar data-table="#table--content">
    <x-button color="primary" toggle-modal="ModalForm" size="md">Tambah</x-button>
</wigatable-toolbar>

<div id="wiga-alert"></div>

<div class="card">
    <div class="card-body">
        {{-- <div class="table-responsive"> --}}
            <table class="table table-row-dashed align-middle my-0" id="table--content">
                <thead>
                    <tr class="text-start text-dark fw-bold fs-7 text-uppercase bg-secondary">
                        <th width="40px">No</th>
                        <th>Nama</th>
                        <th>Instansi</th>
                        <th>No Whatsapp</th>
                        <th>No Registrasi</th>
                        <th>Status Kehadiran</th>
                        <th>Sertifikat Sebagai</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        {{-- </div> --}}
    </div>
</div>

<x-modal 
    id="ModalForm"
    title="Form Panitia"
    withForm="true">

    <div id="ModalFormAlert"></div>

    <x-forms.input type="text" name="name" label="Nama Lengkap"></x-forms.input>
    <x-forms.input type="text" name="agency" label="Instansi"></x-forms.input>
    <x-forms.input type="email" name="email" label="Email"></x-forms.input>
    <x-forms.input type="text" name="phone_number" label="Nomor Whatsapp" placeholder="Contoh: 08123456789"></x-forms.input>
    <x-forms.input type="select" name="type" label="Jenis Peserta">
        <option value="participant" selected>Peserta</option>
        <option value="committee">Panitia</option>
    </x-forms.input>

    <x-forms.input type="text" name="certificate_as" label="Sertifikat Sebagai" value="Peserta" description="Contoh: Peserta, Panitia, Juara 1, Juara 2, Juara 3, dll"></x-forms.input>

    <x-slot name="footer">
        <x-button dismiss-modal color="secondary">Batal</x-button>
        <x-button type="submit" color="primary" indicator>Simpan</x-button>
    </x-slot>

</x-modal>

<x-modal 
    id="ModalDelete"
    title="Konfirmasi"
    withForm="true">

    <div id="ModalDeleteAlert"></div>

    <x-alert type="warning" :dismissible="false">
        <strong>Perhatian:</strong> Apakah anda yakin menghapus data ini?
    </x-alert>

    <x-slot name="footer">
        <x-button dismiss-modal color="secondary">Batal</x-button>
        <x-button type="submit" color="danger" indicator>Hapus</x-button>
    </x-slot>

</x-modal>

<x-modal 
    id="ModalVerify"
    title="Konfirmasi"
    withForm="true">

    <div id="ModalVerifyAlert"></div>

    <div id="content--proof-of-payment" class="d-none rounded overflow-hidden"></div>

    <x-alert type="warning" :dismissible="false">
        <strong>Perhatian:</strong> Apakah anda yakin memverifikasi data ini?
    </x-alert>

    <div class="d-flex justify-content-between mt-6">
        <x-button dismiss-modal color="secondary">Batal</x-button>
        <div class="text-end">
            <x-button type="submit" color="danger" indicator id="btn--reject">Tolak</x-button>
            <x-button type="submit" color="success" indicator id="btn--confirm">Terima</x-button>
        </div>
    </div>

</x-modal>

<x-modal 
    id="ModalCert"
    title="Form Panitia"
    withForm="true">

    <div id="ModalCertAlert"></div>

    <x-forms.input type="select" name="certificate_template" label="Template Sertifikat">
        <option value=""></option>
    </x-forms.input>
    <x-forms.input type="text" name="certificate_as" label="Sertifikat Sebagai" value="Peserta" description="Contoh: Peserta, Panitia, Juara 1, Juara 2, Juara 3, dll"></x-forms.input>

    <x-slot name="footer">
        <x-button dismiss-modal color="secondary">Batal</x-button>
        <x-button type="submit" color="primary" indicator>Simpan</x-button>
    </x-slot>

</x-modal>

@endsection