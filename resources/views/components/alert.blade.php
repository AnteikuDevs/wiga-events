@props([
    'type' => 'success',    // Tipe alert: success, danger, warning, info
    'dismissible' => true,      // Apakah tombol close (x) ditampilkan
])

@php
    // Memetakan 'type' ke kelas ikon Font Awesome dan warna alert Bootstrap
    $iconClass = match($type) {
        'success'   => 'fa-solid fa-circle-check',
        'danger'    => 'fa-solid fa-circle-xmark',
        'warning'   => 'fa-solid fa-triangle-exclamation',
        'info'      => 'fa-solid fa-circle-info',
        default     => 'fa-solid fa-circle-info',
    };

    // Membangun daftar kelas CSS untuk div utama
    $alertClasses = "alert alert-{$type}";
    if ($dismissible) {
        $alertClasses .= ' alert-dismissible fade show';
    }
@endphp

{{-- 
  $attributes->merge(...) akan menggabungkan kelas CSS yang kita buat
  dengan kelas lain yang mungkin Anda tambahkan saat memanggil komponen.
  Ini juga akan meneruskan semua atribut lain seperti id, style, dll.
--}}
<div {{ $attributes->merge(['class' => $alertClasses, 'role' => 'alert']) }}>
    <div class="d-flex align-items-center">
        {{-- Ikon ditampilkan secara dinamis --}}
        <i class="{{ $iconClass }}" style="flex-shrink: 0; font-size: 1.5rem; margin-right: 10px;"></i>
        
        {{-- Di sinilah pesan Anda akan ditampilkan --}}
        <div style="flex-grow: 1;">
            {!! $slot !!}
        </div>
        
        {{-- Tombol close hanya akan ditampilkan jika dismissible bernilai true --}}
        @if($dismissible)
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        @endif
    </div>
</div>