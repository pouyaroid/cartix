@extends('layouts.admin')

@section('title', 'پیشخوان مدیریت')

@section('content')
<div class="fade-in">
    <h5 class="fw-bold mb-4">پیشخوان مدیریت</h5>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-people"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($stats['users']) }}</div>
                        <div class="stat-label">کاربران</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-card-heading"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($stats['cards']) }}</div>
                        <div class="stat-label">کارت‌ها</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning-subtle text-warning"><i class="bi bi-qr-code"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($stats['qr_codes']) }}</div>
                        <div class="stat-label">کدهای QR</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info-subtle text-info"><i class="bi bi-currency-dollar"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($stats['revenue']) }}</div>
                        <div class="stat-label">درآمد کل</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Chart --}}
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-transparent">
                    <h6 class="fw-bold mb-0">ثبت نام کاربران (۱۲ ماه اخیر)</h6>
                </div>
                <div class="card-body">
                    <canvas id="userChart" height="250"></canvas>
                </div>
            </div>
        </div>

        {{-- Recent Users --}}
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-transparent">
                    <h6 class="fw-bold mb-0">آخرین کاربران</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($recentUsers as $user)
                        <div class="list-group-item d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:36px;height:36px;font-size:0.8rem;">
                                {{ mb_substr($user->name, 0, 1) }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="small fw-medium">{{ $user->name }}</div>
                                <div class="text-muted" style="font-size:0.75rem;">{{ $user->email }}</div>
                            </div>
                            <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">{{ $user->is_active ? 'فعال' : 'غیرفعال' }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
const ctx = document.getElementById('userChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($userRegistrations->pluck('month')) !!},
        datasets: [{
            label: 'ثبت نام',
            data: {!! json_encode($userRegistrations->pluck('count')) !!},
            backgroundColor: 'rgba(79, 70, 229, 0.7)',
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});
</script>
@endpush
@endsection
