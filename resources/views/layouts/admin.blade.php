<!DOCTYPE html>
<html lang="fa" dir="rtl" data-bs-theme="{{ session('theme', 'light') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'پنل مدیریت') | CardX Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="admin-body">
    <div class="d-flex">
        {{-- Sidebar --}}
        <aside class="admin-sidebar border-end bg-body" id="adminSidebar">
            <div class="p-3 border-bottom">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-flex align-items-center">
                    <i class="bi bi-card-heading text-primary fs-4 ms-2"></i>
                    <span class="fw-bold text-primary fs-5">مدیریت</span>
                </a>
            </div>
            <nav class="p-2">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 ms-2"></i> پیشخوان
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="bi bi-people ms-2"></i> کاربران
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                            <i class="bi bi-shield-lock ms-2"></i> نقش‌ها و دسترسی‌ها
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.cards.*') ? 'active' : '' }}" href="{{ route('admin.cards.index') }}">
                            <i class="bi bi-card-heading ms-2"></i> کارت‌ها
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.templates.*') ? 'active' : '' }}" href="{{ route('admin.templates.index') }}">
                            <i class="bi bi-layout-text-window ms-2"></i> قالب‌ها
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.fonts.*') ? 'active' : '' }}" href="{{ route('admin.fonts.index') }}">
                            <i class="bi bi-fonts ms-2"></i> فونت‌ها
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}" href="{{ route('admin.plans.index') }}">
                            <i class="bi bi-credit-card ms-2"></i> پلن‌ها
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.landing-pages.*') ? 'active' : '' }}" href="{{ route('admin.landing-pages.index') }}">
                            <i class="bi bi-window-stack ms-2"></i> لندینگ پیج‌ها
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.landing-page-templates.*') ? 'active' : '' }}" href="{{ route('admin.landing-page-templates.index') }}">
                            <i class="bi bi-layout-text-window ms-2"></i> قالب لندینگ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.landing-page-widgets.*') ? 'active' : '' }}" href="{{ route('admin.landing-page-widgets.index') }}">
                            <i class="bi bi-puzzle ms-2"></i> ویجت‌ها
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                            <i class="bi bi-gear ms-2"></i> تنظیمات
                        </a>
                    </li>
                </ul>
                <hr>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard.index') }}">
                            <i class="bi bi-arrow-right ms-2"></i> بازگشت به پیشخوان
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        {{-- Main Content --}}
        <div class="flex-grow-1 admin-content">
            {{-- Top Bar --}}
            <nav class="navbar navbar-expand-lg border-bottom bg-body px-3">
                <button class="btn btn-sm btn-outline-secondary" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="me-auto d-flex align-items-center">
                    <button class="btn btn-link nav-link" id="themeToggle" title="تغییر تم">
                        <i class="bi {{ session('theme') === 'dark' ? 'bi-sun' : 'bi-moon' }}"></i>
                    </button>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-muted ms-3">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger" type="submit">
                            <i class="bi bi-box-arrow-left"></i>
                        </button>
                    </form>
                </div>
            </nav>

            {{-- Page Content --}}
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
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
