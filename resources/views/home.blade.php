@extends('layouts.main.home')
@section('content')
<div class="glow-sphere"></div>

<section class="hero-modern container text-center">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <span class="badge rounded-pill bg-soft-primary mb-4 animate__animated animate__fadeIn">✨ Discover & Experience</span>
            <h1 class="display-1 fw-bolder mb-4 animate__animated animate__fadeInUp">
                Temukan Event <br> <span class="text-gradient-purple">Masa Depanmu.</span>
            </h1>
            <div class="d-flex justify-content-center gap-3 animate__animated animate__fadeInUp animate__delay-1s">
                <form id="search-form" class="d-flex bg-glass p-1 rounded-pill border border-secondary shadow-sm">
                    <input type="text" name="search" class="form-control border-0 bg-transparent text-white px-4" placeholder="Cari event...">
                    <button type="submit" class="btn btn-primary-gradient rounded-pill"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <p class="text-white animate__animated animate__fadeInUp animate__delay-1s mt-3">Temukan berbagai event menarik yang sesuai dengan minatmu.</p>
        </div>
    </div>
</section>

<section id="explore" class="container py-5">
    <div class="mb-5">
        <h2 class="fw-bold mb-4">Eksplorasi Event</h2>
        
        <div class="category-swiper-outer position-relative animate__animated animate__fadeIn">
            <div class="swiper categorySwiper">
                <div class="swiper-wrapper" id="category-filter">
                    <div class="swiper-slide">
                        <button class="btn btn-outline-light btn-sm rounded-pill px-4 active" data-slug="">Semua</button>
                    </div>
                </div>
            </div>
            
            <div class="swiper-button-next btn-swiper-custom"></div>
            <div class="swiper-button-prev btn-swiper-custom"></div>
        </div>
    </div>

    <div class="row g-4" id="event-list-container">
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    </div>

    <div id="pagination-container" class="d-flex justify-content-center mt-5"></div>
</section>

<footer class="container py-5 mt-5 border-top border-secondary text-center">
    <p class="text-light small mb-0">© 2025 Wiga Events. Made with ❤️ by <span class="text-danger">AnteikuDevs</span></p>
</footer>
@endsection
@push('style')    
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<style>
.category-swiper-container {
    padding: 0 40px; /* Ruang untuk tombol nav */
}
.swiper-slide {
    width: auto !important; /* Agar lebar slide mengikuti isi tombol */
    margin-right: 10px !important;
}
.btn-swiper-custom {
    color: #7c4dff !important;
    transform: scale(0.5);
    background: rgba(255, 255, 255, 0.1);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    backdrop-filter: blur(5px);
}
/* Sembunyikan navigasi di mobile agar lebih bersih */
@media (max-width: 768px) {
    .category-swiper-container { padding: 0; }
    .swiper-button-next, .swiper-button-prev { display: none; }
}

.pagination-modern .page-item { margin: 0 5px; }
.pagination-modern .page-link {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;
    border-radius: 12px;
    padding: 10px 20px;
    transition: all 0.3s ease;
}
.pagination-modern .page-item.active .page-link {
    background: linear-gradient(135deg, #7c4dff 0%, #448aff 100%);
    border-color: transparent;
    box-shadow: 0 0 15px rgba(124, 77, 255, 0.5);
}
.pagination-modern .page-link:hover {
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
}
/* 1. Pastikan kolom memiliki tinggi yang sama */
#event-list-container {
    display: flex;
    flex-wrap: wrap;
}

/* 2. Jadikan kartu sebagai flex container */
.modern-card {
    height: 100%; /* Memaksa kartu memenuhi tinggi kolom */
    display: flex;
    flex-direction: column;
    background: #151521; /* Sesuaikan dengan tema gelap Anda */
    border-radius: 20px;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.modern-card a {
    color: #7c4dff;
    text-decoration: none;
    transition: color 0.3s ease;
}

/* 3. Atur area konten agar mengambil sisa ruang */
.modern-card .p-4 {
    flex: 1; /* Mengisi ruang kosong agar footer sejajar di bawah */
    display: flex;
    flex-direction: column;
}

/* 4. Batasi baris judul agar tidak merusak layout */
.modern-card h4.event-title-display {
    display: -webkit-box;
    -webkit-line-clamp: 2; /* Maksimal 2 baris */
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 3em; /* Menjaga ruang tetap ada meski judul cuma 1 baris */
    margin-bottom: 1rem;
}

/* 5. Paksa area harga & tombol ke posisi paling bawah */
.modern-card .mt-auto-container {
    margin-top: auto;
    padding-top: 15px;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
}

.status-badge {
    position: absolute;
    top: 15px;
    /* left: 15px; */
    padding: 6px 16px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    z-index: 2;
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

/* Variasi Akan Datang (Modern Purple Glow) */
.badge-upcoming {
    background: rgba(124, 77, 255, 0.2); /* Ungu transparan */
    color: #fff;
    border-color: rgba(179, 136, 255, 0.4);
    box-shadow: 0 0 15px rgba(124, 77, 255, 0.3);
}

/* Efek Dot Berkedip (Pulse) untuk Status Berlangsung */
.badge-live {
    background: rgba(230, 0, 0, 0.3);
    color: #fff;
    border-color: rgba(230, 0, 0, 0.3);
}

.badge-live::before {
    content: '';
    display: inline-block;
    width: 6px;
    height: 6px;
    background: #e60000;
    border-radius: 50%;
    margin-right: 8px;
    vertical-align: middle;
    box-shadow: 0 0 8px #e60000;
    animation: pulse-dot 1.5s infinite;
}

@keyframes pulse-dot {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(1.2); }
    100% { opacity: 1; transform: scale(1); }
}

/* Variasi Selesai (Muted Gray) */
.badge-finished {
    background: rgba(255, 255, 255, 0.1);
    color: #a0a0a0;
    border-color: rgba(255, 255, 255, 0.1);
}
</style>
@endpush
@push('script')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endpush