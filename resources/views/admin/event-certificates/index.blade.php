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
                        <th>Background</th>
                        <th>Acara</th>
                        <th>Peserta</th>
                        <th>Nomor Sertifikat</th>
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
    title="Form Template Sertifikat"
    withForm="true">

    <div id="ModalFormAlert"></div>

    <x-forms.input type="upload-image" name="image" label="Template Sertifikat"></x-forms.input>
    <x-alert type="info" :dismissible="false">
        <strong>Perhatian:</strong> Pastikan ukuran sertifikat adalah A4 (Lanscape)
    </x-alert>
    <x-forms.input type="select" name="event" label="Pilih Acara"></x-forms.input>
    <x-forms.input type="select" name="type" label="Sertifikat Untuk">
        <option value="participant">Peserta</option>
        <option value="committee">Panitia</option>
    </x-forms.input>
    <x-forms.input type="text" name="certificate_number" label="Nomor Sertifikat"></x-forms.input>

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

@endsection