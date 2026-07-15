@extends('layouts.dashboard')

@section('title', 'پیشخوان')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">پیشخوان</h4>
        <a href="{{ route('dashboard.cards.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg ms-1"></i> کارت جدید
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="bi bi-card-heading"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $stats['cards'] }}</div>
                        <div class="stat-label">کل کارت‌ها</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success-subtle text-success">
                        <i class="bi bi-eye"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ number_format($stats['total_views']) }}</div>
                        <div class="stat-label">بازدید کل</div>
                    </div>
                </div>
            </div>
        </div>
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

    {{-- Recent Cards --}}
    <div class="card shadow-sm">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">آخرین کارت‌ها</h6>
            <a href="{{ route('dashboard.cards.index') }}" class="btn btn-sm btn-outline-primary">مشاهده همه</a>
        </div>
        <div class="card-body p-0">
            @if($recentCards->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-card-heading" style="font-size: 3rem;"></i>
                    <p class="mt-3">هنوز کارتی ایجاد نکرده‌اید.</p>
                    <a href="{{ route('dashboard.cards.create') }}" class="btn btn-primary btn-sm">ایجاد اولین کارت</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>عنوان</th>
                                <th>قالب</th>
                                <th>وضعیت</th>
                                <th>بازدید</th>
                                <th>تاریخ ایجاد</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentCards as $card)
                            <tr>
                                <td>
                                    <a href="{{ route('dashboard.cards.edit', $card) }}" class="text-decoration-none fw-medium">
                                        {{ $card->title }}
                                    </a>
                                </td>
                                <td><span class="badge bg-secondary">{{ $card->template->name ?? 'پیش‌فرض' }}</span></td>
                                <td>
                                    @if($card->is_published)
                                        <span class="badge bg-success">منتشر شده</span>
                                    @else
                                        <span class="badge bg-warning">پیش‌نویس</span>
                                    @endif
                                </td>
                                <td>{{ number_format($card->views_count) }}</td>
                                <td class="text-muted small">{{ \Morilog\Jalali\Jalalian::fromCarbon($card->created_at)->format('Y/m/d') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('dashboard.cards.builder', $card) }}" class="btn btn-outline-primary" title="ویرایشگر">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        @if($card->is_published)
                                            <a href="/{{ $card->slug }}" class="btn btn-outline-success" target="_blank" title="مشاهده">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
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
