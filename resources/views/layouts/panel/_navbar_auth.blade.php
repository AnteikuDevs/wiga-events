<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px" data-kt-menu="true">
    <div class="menu-item px-3">
        <div class="menu-content d-flex align-items-center px-3">
            <div class="symbol symbol-50px me-5">
                <img alt="Logo" src="{{ asset('icon.png') }}" />
            </div>
            <div class="d-flex flex-column">
                <div class="fw-bolder d-flex align-items-center fs-5" wiga-auth="name">
                    {{-- <span class="badge badge-light-success fw-bolder fs-8 px-2 py-1 ms-2">Pro</span> --}}
                </div>
                <a href="javascript:;" class="fw-bold text-muted text-hover-primary fs-7" wiga-auth="email"></a>
            </div>
        </div>
    </div>
    <div class="separator my-2"></div>
    <div class="menu-item px-5">
        <a href="{{ route('admin.profil') }}" class="menu-link px-5">Profil</a>
    </div>
    {{-- <div class="menu-item px-5">
        <a href="#" class="menu-link px-5">
            <span class="menu-text">My Audit Logs</span>
            <span class="menu-badge">
                <span class="badge badge-light-danger badge-circle fw-bolder fs-7">3</span>
            </span>
        </a>
    </div> --}}
    <div class="separator my-2"></div>
    <div class="menu-item px-5">
        <a href="{{ route('logout') }}" class="menu-link px-5">Logout</a>
    </div>
</div>