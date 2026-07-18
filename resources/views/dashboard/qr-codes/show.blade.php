@extends('layouts.dashboard')

@section('title', $qrCode->title ?: 'جزئیات کد QR')
@section('page-title', 'جزئیات کد QR')

@section('content')
<div class="mb-3">
    <a href="{{ route('dashboard.qr.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-right ms-1"></i> بازگشت
    </a>
</div>

<div class="row g-4">
    {{-- QR Code Card --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h5 class="fw-bold mb-3">{{ $qrCode->title }}</h5>

                @if($qrImage)
                    <img src="{{ $qrImage }}" alt="QR Code" class="mb-3" style="max-width:200px;">
                @else
                    <div class="text-muted mb-3">پیش‌نمایش در دسترس نیست</div>
                @endif

                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('dashboard.qr.download', [$qrCode, 'png']) }}" class="btn btn-sm btn-success">
                        <i class="bi bi-download ms-1"></i> PNG
                    </a>
                    <a href="{{ route('dashboard.qr.download', [$qrCode, 'svg']) }}" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-download ms-1"></i> SVG
                    </a>
                </div>

                <hr>

                <div class="text-start small">
                    <div class="mb-2"><span class="text-muted">نوع:</span> <span class="badge bg-{{ $qrCode->type === 'dynamic' ? 'info' : 'secondary' }}">{{ $qrCode->type === 'dynamic' ? 'پویا' : 'ثابت' }}</span></div>
                    <div class="mb-2"><span class="text-muted">لینک:</span> <span class="text-break">{{ $qrCode->content }}</span></div>
                    <div class="mb-2"><span class="text-muted">رنگ پیش‌زمینه:</span> <span class="d-inline-block rounded" style="width:16px;height:16px;background:{{ $qrCode->foreground_color }};vertical-align:middle;"></span> {{ $qrCode->foreground_color }}</div>
                    <div class="mb-2"><span class="text-muted">رنگ پس‌زمینه:</span> <span class="d-inline-block rounded" style="width:16px;height:16px;background:{{ $qrCode->background_color }};border:1px solid #ddd;vertical-align:middle;"></span> {{ $qrCode->background_color }}</div>
                    <div class="mb-2"><span class="text-muted">اندازه:</span> {{ $qrCode->size }}px</div>
                    <div><span class="text-muted">تاریخ ساخت:</span> {{ \Morilog\Jalali\Jalalian::fromCarbon($qrCode->created_at)->format('Y/m/d') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="col-lg-8">
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <div class="text-muted small">کل اسکن‌ها</div>
                    <div class="fw-bold fs-4">{{ number_format($scanStats['total']) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <div class="text-muted small">امروز</div>
                    <div class="fw-bold fs-4">{{ number_format($scanStats['today']) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <div class="text-muted small">این هفته</div>
                    <div class="fw-bold fs-4">{{ number_format($scanStats['this_week']) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <div class="text-muted small">این ماه</div>
                    <div class="fw-bold fs-4">{{ number_format($scanStats['this_month']) }}</div>
                </div>
            </div>
        </div>

        {{-- Recent Scans --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h6 class="fw-bold mb-0">اسکن‌های اخیر</h6>
            </div>
            <div class="card-body p-0">
                @if($recentScans->count())
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>تاریخ</th>
                                    <th>دستگاه</th>
                                    <th>مرورگر</th>
                                    <th>سیستم‌عامل</th>
                                    <th>آی‌پی</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentScans as $scan)
                                    <tr>
                                        <td class="small">{{ \Morilog\Jalali\Jalalian::fromCarbon($scan->created_at)->format('Y/m/d H:i') }}</td>
                                        <td>
                                            <span class="badge bg-{{ match($scan->device_type) { 'mobile' => 'info', 'tablet' => 'warning', default => 'secondary' } }}">
                                                {{ $scan->device_type === 'mobile' ? 'موبایل' : ($scan->device_type === 'tablet' ? 'تبلت' : 'دسکتاپ') }}
                                            </span>
                                        </td>
                                        <td class="small">{{ $scan->browser }}</td>
                                        <td class="small">{{ $scan->os }}</td>
                                        <td class="small text-muted">{{ $scan->ip_address }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-3"></i>
                        <p class="mt-2 small">هنوز اسکنی ثبت نشده</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
