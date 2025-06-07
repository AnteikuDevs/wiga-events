
<div class="menu-item menu-lg-down-accordion me-lg-1">
    <a class="menu-link py-3{{ Route::is('admin.dashboard*') ? ' active' : '' }}" href="{{ route('admin.dashboard') }}">
        <span class="menu-icon">
            <span class="svg-icon svg-icon-2">
                <i class="fa-duotone fa-home text-white"></i>
            </span>
        </span>
        <span class="menu-title">Dashboard</span>
    </a>
</div>

<div class="menu-item menu-lg-down-accordion me-lg-1">
    <a class="menu-link py-3{{ Route::is('admin.events*') ? ' active' : '' }}" href="{{ route('admin.events') }}">
        <span class="menu-icon">
            <span class="svg-icon svg-icon-2">
                <i class="fa-duotone fa-calendar-days text-white"></i>
            </span>
        </span>
        <span class="menu-title">Acara</span>
    </a>
</div>

<div class="menu-item menu-lg-down-accordion me-lg-1">
    <a class="menu-link py-3{{ Route::is('admin.event-certificates*') ? ' active' : '' }}" href="{{ route('admin.event-certificates') }}">
        <span class="menu-icon">
            <span class="svg-icon svg-icon-2">
                <i class="fa-duotone fa-file-certificate text-white"></i>
            </span>
        </span>
        <span class="menu-title">Sertifikat Acara</span>
    </a>
</div>


{{-- <div data-kt-menu-trigger="{default: 'click'}" data-kt-menu-placement="bottom-start" class="menu-item  menu-lg-down-accordion me-lg-1">
    <span class="menu-link py-3">
        <span class="menu-title"></span>
        <span class="menu-arrow d-lg-none"></span>
    </span>
    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-225px">
        <div class="menu-item">
            <a class="menu-link active py-3" href="../dist/index.html">
                <span class="menu-icon">
                    <span class="svg-icon svg-icon-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 25" fill="none">
                            <path opacity="0.3" d="M8.9 21L7.19999 22.6999C6.79999 23.0999 6.2 23.0999 5.8 22.6999L4.1 21H8.9ZM4 16.0999L2.3 17.8C1.9 18.2 1.9 18.7999 2.3 19.1999L4 20.9V16.0999ZM19.3 9.1999L15.8 5.6999C15.4 5.2999 14.8 5.2999 14.4 5.6999L9 11.0999V21L19.3 10.6999C19.7 10.2999 19.7 9.5999 19.3 9.1999Z" fill="black" />
                            <path d="M21 15V20C21 20.6 20.6 21 20 21H11.8L18.8 14H20C20.6 14 21 14.4 21 15ZM10 21V4C10 3.4 9.6 3 9 3H4C3.4 3 3 3.4 3 4V21C3 21.6 3.4 22 4 22H9C9.6 22 10 21.6 10 21ZM7.5 18.5C7.5 19.1 7.1 19.5 6.5 19.5C5.9 19.5 5.5 19.1 5.5 18.5C5.5 17.9 5.9 17.5 6.5 17.5C7.1 17.5 7.5 17.9 7.5 18.5Z" fill="black" />
                        </svg>
                    </span>
                </span>
                <span class="menu-title">Default</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link py-3" href="../dist/admin.dashboards/aside.html">
                <span class="menu-icon">
                    <span class="svg-icon svg-icon-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3" d="M21 22H14C13.4 22 13 21.6 13 21V3C13 2.4 13.4 2 14 2H21C21.6 2 22 2.4 22 3V21C22 21.6 21.6 22 21 22Z" fill="black" />
                            <path d="M10 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H10C10.6 2 11 2.4 11 3V21C11 21.6 10.6 22 10 22Z" fill="black" />
                        </svg>
                    </span>
                </span>
                <span class="menu-title">Aside</span>
            </a>
        </div>
    </div>
</div> --}}