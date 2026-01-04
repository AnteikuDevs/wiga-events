@extends('layouts.panel.index')

@section('content')
<div class="card card-page bg-transparent border-0 shadow-none">
    <div class="card-body p-0">

        <div class="row g-5 mb-8 d-flex align-items-stretch">
            
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0" style="border-radius: 20px; background: linear-gradient(135deg, #0061ff 0%, #60efff 100%);">
                    <div class="card-body p-6 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-50px me-5">
                                <span class="symbol-label bg-white bg-opacity-20">
                                    <i class="bi bi-calendar-event text-white fs-2x"></i>
                                </span>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="text-white fw-bold fs-6 opacity-75">Total Acara</span>
                                <span id="total-event-count" class="text-white fw-bolder fs-2hx lh-1">0</span>
                            </div>
                        </div>
                        <div id="total-event-month" class="text-white opacity-75 small fw-bold">
                            <i class="bi bi-arrow-up me-1 text-white"></i> 0 Acara baru bulan ini
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 bg-white" style="border-radius: 20px;">
                    <div class="card-body p-6 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-50px me-5">
                                <span class="symbol-label bg-light-primary">
                                    <i class="bi bi-person-check text-primary-blue fs-2x"></i>
                                </span>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="text-gray-600 fw-bold fs-6">Rata-rata Kehadiran</span>
                                <span id="average-attendance-value" class="text-dark-blue fw-bolder fs-2hx lh-1">0%</span>
                            </div>
                        </div>
                        <div>
                            <div class="progress h-6px w-100 bg-light-primary mb-2">
                                <div id="average-attendance-bar" class="progress-bar bg-primary-blue" role="progressbar" style="width: 0%; border-radius: 10px;"></div>
                            </div>
                            <span class="text-muted small fw-bold">Persentase kehadiran real-time</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 bg-white" style="border-radius: 20px;">
                    <div class="card-body p-6 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-50px me-5">
                                <span class="symbol-label bg-light-success">
                                    <i class="bi bi-people text-success fs-2x"></i>
                                </span>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="text-gray-600 fw-bold fs-6">Total Peserta Unik</span>
                                <span id="participant-total-count" class="text-dark-blue fw-bolder fs-2hx lh-1">0</span>
                            </div>
                        </div>
                        <div class="text-muted small fw-bold">
                            Tersebar di seluruh rangkaian acara
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row gy-5 g-xl-8">
            <div class="col-xxl-12">
                <div class="card mb-4" style="border-radius: 24px;">
                    <div class="card-header border-0 py-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder text-dark-blue fs-3">Ringkasan Acara Terbaru</span>
                            <span class="text-muted mt-1 fw-bold fs-7">Kelola dan pantau statistik kehadiran secara real-time</span>
                        </h3>
                        <div class="card-toolbar">
                            <button class="btn btn-sm btn-light-primary fw-bold" style="border-radius: 10px;">
                                <i class="bi bi-download me-2"></i>Export Laporan
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm border-0" style="border-radius: 24px;"> 
                    <div class="card-body pt-2 px-9">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="table--content">
                                <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">                      
                                        <th class="min-w-100px">Banner</th>
                                        <th class="min-w-150px">Judul</th>
                                        <th class="min-w-200px">Deskripsi</th>
                                        <th class="min-w-150px">Waktu</th>
                                        <th class="min-w-100px text-center">Peserta</th>
                                        <th class="min-w-100px text-end">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700 fw-bold"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection