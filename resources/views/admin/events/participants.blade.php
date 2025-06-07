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
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Email</th>
                        <th>No Telp</th>
                        <th>Status Kehadiran</th>
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

    <x-forms.input type="text" name="student_id" label="NIM"></x-forms.input>
    <x-forms.input type="text" name="name" label="Nama Lengkap"></x-forms.input>
    <x-forms.input type="text" name="parallel_class" label="Kelas"></x-forms.input>
    <x-forms.input type="text" name="email" label="Email"></x-forms.input>
    <x-forms.input type="text" name="phone_number" label="Nomor Telepon"></x-forms.input>

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