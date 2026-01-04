@extends('layouts.panel.index')

@section('content')

<wigatable-toolbar data-table="#table--content">
    <x-button color="primary" toggle-modal="ModalForm" size="md">
        <i class="fa-solid fa-plus me-2"></i> Tambah Kategori
    </x-button>
</wigatable-toolbar>

<div id="wiga-alert"></div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-row-dashed align-middle my-0" id="table--content">
            <thead>
                <tr class="text-start text-dark fw-bold fs-7 text-uppercase bg-light">
                    <th width="70px">No</th>
                    <th>Nama Kategori</th>
                    <th>Slug</th>
                    <th width="150px">Dibuat Pada</th>
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
    title="Form Kategori"
    withForm="true"
    size="md">

    <div id="ModalFormAlert"></div>

    <div class="mb-3">
        <x-forms.input type="text" name="name" label="Nama Kategori" placeholder="Contoh: Teknologi, Musik, Workshop..."></x-forms.input>
    </div>

    <x-slot name="footer">
        <x-button dismiss-modal color="secondary">Batal</x-button>
        <x-button type="submit" color="primary" indicator>Simpan Kategori</x-button>
    </x-slot>

</x-modal>

<x-modal 
    id="ModalDelete"
    title="Konfirmasi Hapus"
    withForm="true">

    <div id="ModalDeleteAlert"></div>

    <x-alert type="danger" :dismissible="false">
        <div class="d-flex align-items-center">
            <i class="fa-solid fa-trash-can fs-2x me-4 text-danger"></i>
            <div>
                <strong>Konfirmasi:</strong> Apakah anda yakin ingin menghapus kategori ini? 
                <br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
            </div>
        </div>
    </x-alert>

    <x-slot name="footer">
        <x-button dismiss-modal color="secondary">Batal</x-button>
        <x-button type="submit" color="danger" indicator>Hapus Sekarang</x-button>
    </x-slot>

</x-modal>

@endsection
