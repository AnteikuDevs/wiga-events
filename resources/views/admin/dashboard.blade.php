@extends('layouts.panel.index')

@section('content')
<div class="card card-page bg-light">
    <div class="card-body">

        <div class="row gy-5 g-xl-8">

            <div class="col-xxl-12">
                <div class="card card-xxl-stretch"> 
                    <div class="card-header border-0 pt-5 pb-3">
                        <h3 class="card-title fw-bold text-gray-800 fs-2">
                            Acara Terbaru
                        </h3>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-bordered table-row-dashed gy-5">
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase">                       
                                        <th class="min-w-200px px-0">Acara</th>                        
                                        <th class="min-w-125px">Total</th>
                                        <th class="text-end pe-2 min-w-70px">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($events as $event)
    
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-50px me-2">
                                                    <span class="symbol-label">
                                                        <div class="w-100" wigaimage-lazyload="{{ route('file.show', [$event->image_id]) }}"></div>
                                                    </span>
                                                </div>
                                                <div class="ps-3">
                                                    <a href="{{ route('event', [$event->slug]) }}" target="_blank" class="text-gray-800 fw-bolder fs-5 text-hover-primary mb-1">{{ $event->title }}</a>
                                                    <span class="text-gray-400 fw-semibold d-block">{{ $event->date_format }} {{ $event->time_format }} WIB</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-gray-400 me-2 fw-bolder mb-2">{{ $event->participants()->count() }} Peserta</span>
                                        </td>
                                        <td class="text-end">
                                            @if($event->status_id == 0)
                                                <span class="badge badge-light-warning fw-bold">Belum Dimulai</span>
                                            @elseif($event->status_id == 1)
                                                <span class="badge badge-light-success fw-bold">Sedang Berlangsung</span>
                                            @else
                                                <span class="badge badge-light-danger fw-bold">Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                    
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>
</div>
@endsection