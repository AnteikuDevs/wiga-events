@extends('layouts.panel.index')

@section('content')

<wigatable-toolbar data-table="#table--content">
<x-button color="primary" size="md" href="{{ route('portal.events.create') }}">Tambah</x-button>
</wigatable-toolbar>

<div id="wiga-alert"></div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-row-dashed align-middle my-0" id="table--content">
            <thead>
                <tr class="text-start text-dark fw-bold fs-7 text-uppercase bg-secondary">
                    <th width="40px">No</th>
                    <th>Banner</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th width="180px">Waktu & Registrasi</th> 
                    <th>Biaya</th> 
                    <th>Total Peserta</th>
                    <th>Status</th>
                    <th width="50px">#</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<x-modal 
    id="ModalForm"
    title="Form Acara"
    withForm="true"
    size="lg">

    <div id="ModalFormAlert"></div>

    <x-forms.input type="upload-image" name="image" label="Banner"></x-forms.input>
    
    <div class="row">
        <div class="col-md-6">
            <x-forms.input type="text" name="title" label="Judul Acara"></x-forms.input>
        </div>
        <div class="col-md-6">
            <x-forms.input type="select" name="type" label="Jenis Acara">
                <option value="offline">Offline</option>
                <option value="online">Online</option>
            </x-forms.input>
        </div>
    </div>

    <div class="mb-5">
        <x-forms.input type="select" name="category_ids[]" label="Kategori Event" multiple data-control="select2" data-placeholder="Pilih satu atau lebih kategori">
            <option value=""></option>
        </x-forms.input>
        {{-- <small class="text-muted">Anda dapat memilih lebih dari satu kategori yang relevan.</small> --}}
    </div>

    <div class="d-none content--type" data-content="online">
        <x-forms.input type="textarea" name="link" label="Link Acara"></x-forms.input>
    </div>
    <div class="d-none content--type" data-content="offline">
        <x-forms.input type="textarea" name="location" label="Lokasi Acara"></x-forms.input>
    </div>

    <x-forms.input type="textarea" name="description" label="Deskripsi"></x-forms.input>

    <div class="row">
        <div class="col-md-4">
            <x-forms.input type="datetime-local" name="start_time" label="Waktu Mulai"></x-forms.input>
            <x-forms.input type="checkbox" name="until_finish" label="Sampai Selesai" value="0"></x-forms.input>
        </div>
        <div class="col-md-4">
            <x-forms.input type="datetime-local" name="end_time" label="Waktu Selesai"></x-forms.input>
        </div>
        <div class="col-md-4">
            <x-forms.input type="datetime-local" name="registration_end" label="Batas Akhir Registrasi"></x-forms.input>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <x-forms.input type="checkbox" name="limited_quota" label="Kuota Terbatas?" value="0"></x-forms.input>
            <div class="d-none content--type" data-content="limited">
                <x-forms.input type="number" name="quota" label="Jumlah Kuota"></x-forms.input>
            </div>
        </div>
        <div class="col-md-6">
            <x-forms.input type="checkbox" name="is_paid" label="Berbayar?" value="0" id="check-is-paid"></x-forms.input>
            <div class="d-none content--type" data-content="paid">
                <x-forms.input type="number" name="registration_fee" label="Nominal Biaya (Rp)" placeholder="Contoh: 50000"></x-forms.input>
            </div>
        </div>
    </div>

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
    title="Link Presensi Peserta"
    withForm="true">

    <div id="ModalGenerateAttendanceAlert"></div>

    <div id="attendance-loading">
        <div class="d-flex flex-column flex-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <span class="text-gray-800 fs-6 fw-semibold mt-5">Membuat link presensi...</span>
        </div>
    </div>

    <div class="d-none" id="attendance-content">
        <x-alert type="warning" :dismissible="false">
            <strong>Perhatian:</strong> Link dibawah berlaku 1 jam kedepan
        </x-alert>

        <div class="d-flex align-items-center bg-light-primary rounded border-dashed border-primary py-2 px-4 mb-3 gap-1" id="content-attendance"></div>
        <div id="alert--inline-copy"></div>
        <button class="btn btn-primary mt-3 w-100" id="btn-qr"><i class="fa-regular fa-qrcode"></i> Tampilakn QR Code</button>
    </div>

    {{-- <x-slot name="footer">
        <x-button dismiss-modal color="secondary">Batal</x-button>
        <x-button type="submit" color="danger" indicator>Hapus</x-button>
    </x-slot> --}}

</x-modal>

@endsection