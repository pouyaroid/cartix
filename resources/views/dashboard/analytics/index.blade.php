@extends('layouts.dashboard')

@section('title', 'آمار بازدید')
@section('page-title', 'آمار بازدید')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="text-muted small">کل بازدید کارت</div>
            <div class="fw-bold fs-4">{{ number_format($stats['total_views']) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="text-muted small">کل اسکن QR</div>
            <div class="fw-bold fs-4">{{ number_format($stats['total_scans']) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="text-muted small">امروز</div>
            <div class="fw-bold fs-4">{{ number_format($stats['scans_today']) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="text-muted small">این ماه</div>
            <div class="fw-bold fs-4">{{ number_format($stats['scans_this_month']) }}</div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Charts --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between">
                <h6 class="fw-bold mb-0">نمودار اسکن‌ها</h6>
                <select class="form-select form-select-sm" style="width:auto;" id="periodSelect">
                    <option value="7">۷ روز اخیر</option>
                    <option value="30" selected>۳۰ روز اخیر</option>
                    <option value="90">۹۰ روز اخیر</option>
                </select>
            </div>
            <div class="card-body">
                <canvas id="scansChart" height="250"></canvas>
            </div>
        </div>
    </div>

    {{-- Top Cards --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h6 class="fw-bold mb-0">پربازدیدترین کارت‌ها</h6>
            </div>
            <div class="card-body p-0">
                @forelse($topCards as $card)
                    <div class="d-flex align-items-center justify-content-between px-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="text-truncate">{{ $card->title }}</div>
                        <span class="badge bg-primary">{{ number_format($card->views_count) }}</span>
                    </div>
                @empty
                    <div class="text-center py-3 text-muted small">داده‌ای موجود نیست</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Device Stats --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h6 class="fw-bold mb-0">آمار دستگاه‌ها</h6>
            </div>
            <div class="card-body">
                <canvas id="deviceChart" height="200"></canvas>
            </div>
        </div>
    </div>

    {{-- Browser Stats --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h6 class="fw-bold mb-0">آمار مرورگرها</h6>
            </div>
            <div class="card-body">
                <canvas id="browserChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
const colors = ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#0dcaf0', '#6f42c1', '#fd7e14'];

function loadCharts() {
    const period = document.getElementById('periodSelect').value;
    fetch('{{ route("dashboard.analytics.data") }}?period=' + period)
        .then(r => r.json())
        .then(data => {
            // Daily Chart
            new Chart(document.getElementById('scansChart'), {
                type: 'line',
                data: {
                    labels: data.daily.map(d => d.date),
                    datasets: [{
                        label: 'اسکن',
                        data: data.daily.map(d => d.count),
                        borderColor: '#0d6efd',
                        backgroundColor: '#0d6efd20',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
            });

            // Device Chart
            new Chart(document.getElementById('deviceChart'), {
                type: 'doughnut',
                data: {
                    labels: data.devices.map(d => d.device_type || 'نامشخص'),
                    datasets: [{ data: data.devices.map(d => d.count), backgroundColor: colors }]
                },
                options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
            });

            // Browser Chart
            new Chart(document.getElementById('browserChart'), {
                type: 'pie',
                data: {
                    labels: data.browsers.map(d => d.browser || 'نامشخص'),
                    datasets: [{ data: data.browsers.map(d => d.count), backgroundColor: colors }]
                },
                options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
            });
        });
}

document.getElementById('periodSelect').addEventListener('change', loadCharts);
loadCharts();
</script>
@endpush
