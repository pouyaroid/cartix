@extends('layouts.dashboard')

@section('title', 'پیشخوان')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">پیشخوان</h4>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning-subtle text-warning">
                        <i class="bi bi-qr-code"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $stats['qr_codes'] }}</div>
                        <div class="stat-label">کد QR</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info-subtle text-info">
                        <i class="bi bi-phone"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ number_format($stats['total_scans']) }}</div>
                        <div class="stat-label">اسکن کل</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent QR Codes --}}
    <div class="card shadow-sm">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">آخرین کدهای QR</h6>
            <a href="{{ route('dashboard.qr.index') }}" class="btn btn-sm btn-outline-primary">مشاهده همه</a>
        </div>
        <div class="card-body p-0">
            @if($recentQrCodes->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-qr-code" style="font-size: 3rem;"></i>
                    <p class="mt-3">هنوز کد QR ایجاد نکرده‌اید.</p>
                    <a href="{{ route('dashboard.qr.create') }}" class="btn btn-primary btn-sm">ایجاد کد QR</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>عنوان</th>
                                <th>نوع</th>
                                <th>اسکن‌ها</th>
                                <th>تاریخ ایجاد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentQrCodes as $qr)
                            <tr>
                                <td>
                                    <a href="{{ route('dashboard.qr.show', $qr) }}" class="text-decoration-none fw-medium">
                                        {{ $qr->title }}
                                    </a>
                                </td>
                                <td><span class="badge bg-{{ $qr->type === 'dynamic' ? 'info' : 'secondary' }}">{{ $qr->type === 'dynamic' ? 'پویا' : 'ثابت' }}</span></td>
                                <td>{{ number_format($qr->scans_count) }}</td>
                                <td class="text-muted small">{{ \Morilog\Jalali\Jalalian::fromCarbon($qr->created_at)->format('Y/m/d') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
