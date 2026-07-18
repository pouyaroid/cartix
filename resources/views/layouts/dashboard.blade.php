<!DOCTYPE html>
<html lang="fa" dir="rtl" data-bs-theme="{{ session('theme', 'light') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'پیشخوان') | CardX</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    @livewireStyles
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        {{-- Sidebar --}}
        <aside class="dashboard-sidebar border-end bg-body" id="dashboardSidebar">
            <div class="p-3 border-bottom">
                <a href="{{ route('dashboard.index') }}" class="text-decoration-none d-flex align-items-center">
                    <i class="bi bi-card-heading text-primary fs-4 ms-2"></i>
                    <span class="fw-bold text-primary fs-5">کارت ایکس</span>
                </a>
            </div>
            <nav class="p-2">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}" href="{{ route('dashboard.index') }}">
                            <i class="bi bi-speedometer2 ms-2"></i> پیشخوان
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.qr.*') ? 'active' : '' }}" href="{{ route('dashboard.qr.index') }}">
                            <i class="bi bi-qr-code ms-2"></i> کدهای QR
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.media.*') ? 'active' : '' }}" href="{{ route('dashboard.media.index') }}">
                            <i class="bi bi-folder2-open ms-2"></i> مدیر فایل
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.analytics.*') ? 'active' : '' }}" href="{{ route('dashboard.analytics.index') }}">
                            <i class="bi bi-bar-chart-line ms-2"></i> آمار بازدید
                        </a>
                    </li>
                </ul>
                <hr>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.profile.*') ? 'active' : '' }}" href="{{ route('dashboard.profile.edit') }}">
                            <i class="bi bi-person-gear ms-2"></i> پروفایل
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.subscription.*') ? 'active' : '' }}" href="{{ route('dashboard.subscription.index') }}">
                            <i class="bi bi-credit-card ms-2"></i> اشتراک
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        {{-- Main --}}
        <div class="flex-grow-1 dashboard-content">
            <nav class="navbar navbar-expand-lg border-bottom bg-body px-3">
                <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="me-auto">
                    <h6 class="mb-0 text-muted d-none d-md-block">@yield('page-title', '')</h6>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-link nav-link p-1" id="themeToggle" title="تغییر تم">
                        <i class="bi {{ session('theme') === 'dark' ? 'bi-sun' : 'bi-moon' }}"></i>
                    </button>
                </div>
            </nav>

            <div class="p-3">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/fa.js"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
