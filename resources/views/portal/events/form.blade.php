@extends('layouts.panel.index')

@section('content')
<div id="wiga-alert"></div>
<div class="card shadow-sm">
    <form action="" id="form-event-action">
        <div class="card-body p-lg-10">

            <x-forms.input type="upload-image" name="image" label="Banner"></x-forms.input>
            
            <div class="row g-9 mb-8">
                <div class="col-md-6">
                    <x-forms.input type="text" name="title" label="Judul Acara" placeholder="Masukkan judul acara"></x-forms.input>
                </div>
                <div class="col-md-6">
                    <x-forms.input type="select" name="type" label="Jenis Acara" data-placeholder="Pilih jenis acara">
                        <option value=""></option>
                        <option value="offline">Offline</option>
                        <option value="online">Online</option>
                    </x-forms.input>
                </div>
            </div>

            <div class="mb-8">
                <x-forms.input type="select" name="category_ids[]" label="Kategori Event" multiple data-control="select2" data-placeholder="Pilih satu atau lebih kategori">
                    <option value=""></option>
                </x-forms.input>
            </div>

            <div class="d-none content--type mb-8" data-content="online">
                <x-forms.input type="textarea" name="link" label="Link Acara (URL)" placeholder="https://zoom.us/j/..."></x-forms.input>
            </div>
            <div class="d-none content--type mb-8" data-content="offline">
                <x-forms.input type="textarea" name="location" label="Lokasi Fisik Acara" placeholder="Nama Gedung, Alamat Lengkap..."></x-forms.input>
            </div>

            <div class="mb-8">
                <x-forms.input type="textarea" name="description" label="Deskripsi Lengkap" rows="5"></x-forms.input>
            </div>

            <div class="row g-9 mb-8">
                <div class="col-md-4">
                    <x-forms.input type="date" name="registration_end" label="Batas Akhir Registrasi"></x-forms.input>
                </div>
                <div class="col-md-4">
                    <x-forms.input type="datetime-local" name="start_time" label="Waktu Mulai"></x-forms.input>
                    <div class="mt-2">
                        <x-forms.input type="checkbox" name="until_finish" label="Sampai Selesai" value="1"></x-forms.input>
                    </div>
                </div>
                <div class="col-md-4">
                    <x-forms.input type="datetime-local" name="end_time" label="Waktu Selesai"></x-forms.input>
                </div>
            </div>
            
            <div class="row g-9 mb-8">
                <div class="col-md-6">
                    <x-forms.input type="checkbox" name="limited_quota" label="Kuota Terbatas?" value="1"></x-forms.input>
                    <div class="d-none content--type mt-3" data-content="limited">
                        <x-forms.input type="number" name="quota" label="Jumlah Kuota Maksimal" placeholder="0"></x-forms.input>
                    </div>
                </div>
            </div>

            <div class="col-md-6">   
                <x-forms.input type="checkbox" name="is_paid" label="Event Berbayar?" value="1" id="check-is-paid"></x-forms.input>
                <div class="d-none content--type mt-3" data-content="paid">
                    <x-forms.input type="number" name="registration_fee" label="Biaya Pendaftaran (Rp)" placeholder="Contoh: 50000 (tanpa tanda rupiah)"></x-forms.input>
                </div>             
                <div class="d-none content--type mt-3" data-content="paid">
                    <h3>Informasi Pembayaran</h3>

                    <div class="row g-5">
                        <div class="col-md-12">
                            <x-forms.input type="text" name="bank_name" label="Nama Bank" placeholder="Contoh: BCA, Mandiri, dll"></x-forms.input>
                        </div>
                        <div class="col-md-12">
                            <x-forms.input type="text" name="bank_account_number" label="Nomor Rekening" placeholder="Masukkan nomor rekening"></x-forms.input>
                        </div>
                        <div class="col-md-12">
                            <x-forms.input type="text" name="bank_account_name" label="Nama Pemilik Rekening" placeholder="Nama sesuai buku tabungan"></x-forms.input>
                        </div>
                    </div>

                    <x-alert type="info" :dismissible="false" class="mb-5">
                        <div class="d-flex flex-column">
                            <h4 class="mb-1 text-info">Informasi Pembayaran</h4>
                            <span>Mohon pastikan data bank dan nomor rekening yang Anda masukkan sudah benar dan sesuai. Data ini akan digunakan oleh peserta untuk melakukan transfer pembayaran pendaftaran.</span>
                        </div>
                    </x-alert>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end py-6 px-9 gap-3 bg-light">
            <a href="{{ url()->previous() }}" class="btn btn-secondary rounded-pill px-8">Batal</a>
            <x-button type="submit" color="primary" indicator class="rounded-pill px-10">Simpan</x-button>
        </div>
    </form>
</div>

@endsection