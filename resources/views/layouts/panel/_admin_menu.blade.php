
<div class="menu-item menu-lg-down-accordion me-lg-1">
    <a class="menu-link py-3{{ Route::is('portal.dashboard*') ? ' active' : '' }}" href="{{ route('portal.dashboard') }}">
        <span class="menu-icon">
            <span class="svg-icon svg-icon-2">
                <i class="fa-duotone fa-home text-white"></i>
            </span>
        </span>
        <span class="menu-title">Dashboard</span>
    </a>
</div>

<div class="menu-item menu-lg-down-accordion me-lg-1">
    <a class="menu-link py-3{{ Route::is('portal.events*') ? ' active' : '' }}" href="{{ route('portal.events') }}">
        <span class="menu-icon">
            <span class="svg-icon svg-icon-2">
                <i class="fa-duotone fa-calendar-days text-white"></i>
            </span>
        </span>
        <span class="menu-title">Acara</span>
    </a>
</div>

{{-- <div class="menu-item menu-lg-down-accordion me-lg-1">
    <a class="menu-link py-3{{ Route::is('portal.event-certificates*') ? ' active' : '' }}" href="{{ route('portal.event-certificates') }}">
        <span class="menu-icon">
            <span class="svg-icon svg-icon-2">
                <i class="fa-duotone fa-file-certificate text-white"></i>
            </span>
        </span>
        <span class="menu-title">Sertifikat Acara</span>
    </a>
</div> --}}

{{-- <div class="menu-item menu-lg-down-accordion me-lg-1">
    <a class="menu-link py-3{{ Route::is('portal.categories*') ? ' active' : '' }}" href="{{ route('portal.categories') }}">
        <span class="menu-icon">
            <span class="svg-icon svg-icon-2">
                <i class="fa-duotone fa-tags text-white"></i>
            </span>
        </span>
        <span class="menu-title">Kategori</span>
    </a>
</div> --}}


<div data-kt-menu-trigger="{default: 'click'}" data-kt-menu-placement="bottom-start" class="menu-item  menu-lg-down-accordion me-lg-1{{ Route::is('portal.categories*') || Route::is('portal.users*') ? ' show' : '' }}">
    <span class="menu-link py-3">
        <span class="menu-icon">
            <span class="svg-icon svg-icon-2">
                <i class="fa-duotone fa-list text-white"></i>
            </span>
        </span>
        <span class="menu-title">Master</span>
        <span class="menu-arrow d-lg-none"></span>
    </span>
    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-225px{{ Route::is('portal.categories*') || Route::is('portal.user*') ? ' show' : '' }}">
        <div class="menu-item">
            <a class="menu-link py-3{{ Route::is('portal.categories*') ? ' active' : '' }}" href="{{ route('portal.categories') }}">
                <span class="menu-icon">
                    <span class="svg-icon svg-icon-2">
                        <i class="fa-duotone fa-tags"></i>
                    </span>
                </span>
                <span class="menu-title">Kategori</span>
            </a>
        </div>

        <div class="menu-item">
            <a class="menu-link py-3{{ Route::is('portal.users*') ? ' active' : '' }}" href="{{ route('portal.users') }}">
                <span class="menu-icon">
                    <span class="svg-icon svg-icon-2">
                        <i class="fa-duotone fa-users"></i>
                    </span>
                </span>
                <span class="menu-title">Pengguna</span>
            </a>
        </div>
    </div>
</div>