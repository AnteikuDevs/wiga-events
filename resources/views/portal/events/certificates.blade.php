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
                        <th>Template</th>
                        <th>Nomor Sertifikat</th>
                        <th>Status Default</th>
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

    <x-forms.input type="text" name="name" label="Nama Template" placeholder="Contoh: Sertifikat Webinar Nasional"></x-forms.input>

    <div class="mb-10">
        <label class="form-label fw-bold">Pilih Model Layout</label>
        <div class="alert alert-dismissible bg-light-primary d-flex flex-column flex-sm-row p-5 mb-5">
            <i class="ki-duotone ki-information fs-2hx text-primary me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            <div class="d-flex flex-column pe-0 pe-sm-10">
                <h5 class="mb-1">Informasi Layout</h5>
                <span>Teks yang berada di dalam kurung kurawal <b class="text-danger">{ }</b> pada gambar model adalah <strong>data dinamis</strong> yang akan terisi otomatis oleh sistem.</span>
            </div>
        </div>
        <div class="row g-9" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button='true']">
            <div class="col-6">
                <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6" data-kt-button="true">
                    <div class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                        <input class="form-check-input" type="radio" name="model" value="1" checked="checked" />
                    </div>
                    <div class="ms-5">
                        <span class="fs-6 fw-bold text-gray-800 d-block">Model 1</span>
                        <div class="mt-2 border rounded">
                             <img src="{{ asset('images/cert-model/1.png') }}" class="w-100 rounded" alt="Preview Model 1">
                        </div>
                    </div>
                </label>
                <a href="{{ asset('images/cert-model/1.png') }}" target="_blank">Perbesar</a>
            </div>
            <div class="col-6">
                <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6" data-kt-button="true">
                    <div class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                        <input class="form-check-input" type="radio" name="model" value="2" />
                    </div>
                    <div class="ms-5">
                        <span class="fs-6 fw-bold text-gray-800 d-block">Model 2</span>
                        <div class="mt-2 border rounded">
                            <img src="{{ asset('images/cert-model/2.png') }}" class="w-100 rounded" alt="Preview Model 2">
                        </div>
                    </div>
                </label>
                <a href="{{ asset('images/cert-model/2.png') }}" target="_blank">Perbesar</a>
            </div>
        </div>
    </div>

    <x-forms.input type="upload-image" name="image" label="Upload Background Sertifikat"></x-forms.input>
    <x-alert type="info" :dismissible="false">
        <strong>Perhatian:</strong> Pastikan ukuran sertifikat adalah A5 (Landscape)
    </x-alert>

    <x-forms.input type="text" name="certificate_number" label="Nomor Sertifikat (Opsional)" placeholder=""></x-forms.input>
    <x-forms.input type="text" name="certificate_as" label="Sertifikat Sebagai (Opsional)" placeholder="Contoh: Peserta, Panitia, Juara 1, Juara 2, Juara 3, dll" value="Peserta"></x-forms.input>

    <x-slot name="footer">
        <div class="d-flex justify-content-between w-100">
            <x-button type="button" color="info" id="btn-cert-preview">
                <i class="fa fa-eye"></i> Preview Hasil
            </x-button>
            
            <div>
                <x-button dismiss-modal color="secondary">Batal</x-button>
                <x-button type="submit" color="primary" indicator>Simpan</x-button>
            </div>
        </div>
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
    id="ModalSetDefault"
    title="Konfirmasi"
    withForm="true">

    <div id="ModalSetDefaultAlert"></div>

    <x-alert type="primary" :dismissible="false">
        <strong>Perhatian:</strong> Apakah anda yakin ingin mengubah sertifikat ini menjadi sertifikat default?
    </x-alert>

    <x-slot name="footer">
        <x-button dismiss-modal color="secondary">Batal</x-button>
        <x-button type="submit" color="primary" indicator>Jadikan Default</x-button>
    </x-slot>

</x-modal>

@endsection