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
                        <th>Banner</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th width="150px">Waktu</th>
                        <th>Total Peserta</th>
                        <th>Status</th>
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
    title="Form Acara"
    withForm="true">

    <div id="ModalFormAlert"></div>

    <x-forms.input type="upload-image" name="image" label="Banner"></x-forms.input>
    <x-forms.input type="text" name="title" label="Judul Acara"></x-forms.input>
    <x-forms.input type="select" name="type" label="Jenis Acara">
        {{-- <option value=""></option> --}}
        <option value="offline">Offline</option>
        <option value="online">Online</option>
    </x-forms.input>
    <div class="d-none content--type" data-content="online">
        <x-forms.input type="textarea" name="link" label="Link Acara"></x-forms.input>
    </div>
    <div class="d-none content--type" data-content="offline">
        <x-forms.input type="textarea" name="location" label="Lokasi Acara"></x-forms.input>
    </div>
    <x-forms.input type="textarea" name="description" label="Deskripsi"></x-forms.input>
    <x-forms.input type="datetime-local" name="start_time" label="Waktu Mulai"></x-forms.input>
    <x-forms.input type="checkbox" name="until_finish" label="Sampai Selesai" value="0"></x-forms.input>
    <x-forms.input type="datetime-local" name="end_time" label="Waktu Selesai"></x-forms.input>

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
    id="ModalConfirmPublish"
    title="Konfirmasi"
    withForm="true">

    <div id="ModalConfirmPublishAlert"></div>

    <x-alert type="warning" :dismissible="false">
        <strong>Perhatian:</strong> Apakah anda yakin ingin menyelesaikan dan mengirim sertifikat peserta?
    </x-alert>

    <x-slot name="footer">
        <x-button dismiss-modal color="secondary">Batal</x-button>
        <x-button type="submit" color="warning" indicator>Publikasikan Sekarang</x-button>
    </x-slot>

</x-modal>

<x-modal 
    id="ModalNotification"
    title="Konfirmasi"
    withForm="true">

    <div id="ModalNotificationAlert"></div>

    <x-alert type="warning" :dismissible="false">
        <strong>Perhatian:</strong> Apakah anda yakin ingin mengirim pesan pengingat kepada semua peserta?
    </x-alert>

    <x-alert type="info" :dismissible="false">
        <strong>Note:</strong> Pastikan anda tidak melakukan pengingat lebih dari 2 kali dalam 1 jam
    </x-alert>

    <x-slot name="footer">
        <x-button dismiss-modal color="secondary">Batal</x-button>
        <x-button type="submit" color="danger" indicator>Kirim Pengingat</x-button>
    </x-slot>

</x-modal>

<x-modal 
    id="ModalGenerateAttendance"
    title="Konfirmasi"
    withForm="true">

    <div id="ModalGenerateAttendanceAlert"></div>

    <x-alert type="warning" :dismissible="false">
        <strong>Perhatian:</strong> Link dibawah berlaku 1 jam kedepan
    </x-alert>

    <div class="d-flex align-items-center bg-light-primary rounded border-dashed border-primary py-2 px-4 mb-3" id="content-attendance"></div>
    <div id="alert--inline-copy"></div>

    {{-- <x-slot name="footer">
        <x-button dismiss-modal color="secondary">Batal</x-button>
        <x-button type="submit" color="danger" indicator>Hapus</x-button>
    </x-slot> --}}

</x-modal>

@endsection