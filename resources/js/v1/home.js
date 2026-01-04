let WigaClass = Wiga.class({
    currentCategory: '',
    currentPage: 1,
    swiper: null,

    /**
     * Inisialisasi awal halaman
     */
    async render() {
        // Memuat kategori terlebih dahulu agar UI siap
        await this.fetchCategories();
        // Memuat data event pertama kali
        this.getData();
        // Memasang semua event listener
        this.setupListeners();
    },

    /**
     * Mengambil daftar kategori yang memiliki event dari API
     */
    async fetchCategories() {
        let response = await WigaHttp.get('/category-event');
        return new Promise((resolve) => {
            WigaHttp.handle(response, null, (res) => {
                let html = `
                    <div class="swiper-slide w-auto">
                        <button class="btn btn-outline-light btn-sm rounded-pill px-4 active" data-slug="">Semua</button>
                    </div>`;
                
                if (res.data) {
                    res.data.forEach(cat => {
                        html += `
                            <div class="swiper-slide w-auto">
                                <button class="btn btn-outline-light btn-sm rounded-pill px-4" data-slug="${cat.slug}">${cat.name}</button>
                            </div>`;
                    });
                }
                
                $wiga('#category-filter').html(html);

                // Inisialisasi SwiperJS setelah elemen HTML terpasang
                this.initSwiper();
                resolve();
            });
        });
    },

    /**
     * Konfigurasi SwiperJS untuk navigasi kategori yang responsive
     */
    initSwiper() {
        this.swiper = new Swiper(".categorySwiper", {
            slidesPerView: "auto",
            spaceBetween: 12,
            freeMode: true,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                320: { spaceBetween: 3 },
                768: { spaceBetween: 12 }
            }
        });
    },

    /**
     * Mengambil data event berdasarkan filter kategori, pencarian, dan halaman
     */
    async getData(page = 1) {
        this.currentPage = page;
        let search = $wiga('#search-form [name=search]').val();
        
        // Tampilkan loading spinner ke user
        $wiga('#event-list-container').html(`
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>
                <p class="mt-3 text-light">Mencari event seru untukmu...</p>
            </div>
        `);

        // Request ke API
        let response = await WigaHttp.get(`/event?page=${page}&category=${this.currentCategory}&search=${search}`);
        
        WigaHttp.handle(response, null, (res) => {
            let html = '';
            // res.data.data karena Laravel Paginate membungkus hasil dalam 'data'
            if (res.data && res.data.data.length > 0) {
                res.data.data.forEach(event => {
                    html += this.templateEvent(event);
                });
            } else {
                html = `
                    <div class="col-12 text-center py-5 animate__animated animate__fadeIn">
                        <i class="fa-duotone fa-box-open fa-6x text-primary mb-4 opacity-50" 
                        style="--fa-primary-color: #7c4dff; --fa-secondary-color: #448aff; --fa-secondary-opacity: 0.4;">
                        </i>
                        <h4 class="text-light fw-bold">Oops! Event tidak ditemukan</h4>
                        <p class="text-secondary">Coba gunakan kata kunci lain atau pilih kategori berbeda.</p>
                    </div>`;
            }
            
            // Render ke container dengan efek transisi
            $wiga('#event-list-container').hide().html(html).fadeIn(600);
            
            // Update navigasi halaman
            this.renderPagination(res.data);
            
            // Inisialisasi ulang lazyload untuk gambar yang baru muncul
            WigaImageLazyload.render();
        });
    },

    /**
     * Template HTML untuk satu kartu event
     */
    templateEvent(data) {
        let statusBadge = '';
        
        // Logika penentuan badge berdasarkan status_id
        if(data.status_id == 0) {
            statusBadge = `<span class="status-badge badge-upcoming">
                            <i class="fa-solid fa-clock-six me-1"></i> Akan Datang
                        </span>`;
        } else if(data.status_id == 1) {
            statusBadge = `<span class="status-badge badge-live">
                            Berlangsung
                        </span>`;
        } else {
            statusBadge = `<span class="status-badge badge-finished">
                            Selesai
                        </span>`;
        }

        let fee = (data.registration_fee > 0) 
            ? `<span class="fw-bold text-white">Rp ${WigaString.toIdr(data.registration_fee)}</span>` 
            : '<span class="fw-bold text-success">Gratis</span>';

        return `
            <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp">
                <div class="modern-card">
                    <div class="card-img-container">
                        ${statusBadge}
                        <div class="card-img" style="height: 230px; background-size: cover; background-position: center;border-radius: 8px;overflow: hidden">
                            <div wigaimage-lazyload="${WigaRoute.storageUrl(data.image_id)}" class="card-img-top" style="width: 100%"></div>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="text-accent-purple small fw-bold mb-2 text-uppercase">
                            <i class="fa-regular fa-calendar-day me-1"></i> ${data.date_format}
                        </div>
                        <h4 class="fw-bold text-white h5 mb-3 text-truncate-2" title="${data.title}"><a href="${WigaRoute.url('/event/' + data.slug)}">${data.title}</a></h4>
                        <p class="text-secondary small mb-4 text-truncate">
                            <i class="fa-solid fa-map-marker-alt me-2"></i>${data.type == 'online' ? 'Online' : data.location}
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            ${fee}
                            <a href="${WigaRoute.url('/event/' + data.slug)}" class="btn btn-view-more btn-sm px-4 rounded-pill">Rincian</a>
                        </div>
                    </div>
                </div>
            </div>
        `;
    },

    /**
     * Membuat navigasi halaman dinamis
     */
    renderPagination(meta) {
        if (!meta || meta.last_page <= 1) {
            $wiga('#pagination-container').html('');
            return;
        }

        let html = '<ul class="pagination pagination-modern">';
        for (let i = 1; i <= meta.last_page; i++) {
            html += `
                <li class="page-item ${i == meta.current_page ? 'active' : ''}">
                    <button class="page-link" onclick="WigaClass.getData(${i})">${i}</button>
                </li>`;
        }
        html += '</ul>';
        $wiga('#pagination-container').html(html);
    },

    /**
     * Mengatur semua interaksi user (klik & submit)
     */
    setupListeners() {
        const _this = this;

        // Listener klik kategori (menggunakan delegasi karena tombol dibuat dinamis)
        $wiga('#category-filter button').on('click', function(e) {
            e.preventDefault();
            
            // Update UI tombol aktif
            $wiga('#category-filter button').removeClass('active');
            $wiga(this).addClass('active');

            // Set filter dan ambil data baru dari halaman 1
            _this.currentCategory = $wiga(this).attr('data-slug');
            _this.getData(1); 
            
            // Geser swiper agar tombol yang diklik tetap terlihat
            if(_this.swiper) {
                _this.swiper.slideTo($wiga(this).closest('.swiper-slide').index());
            }
        });

        // Listener form pencarian
        $wiga('#search-form').on('submit', function(e) {
            e.preventDefault();
            WigaRoute.redirect('/event?search=' + $wiga('#search-form [name=search]').val().replaceAll(' ', '+'));
        });
    }
});