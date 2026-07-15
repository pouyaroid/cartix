@extends('layouts.dashboard')

@section('title', 'کدهای QR')
@section('page-title', 'کدهای QR')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">کدهای QR</h5>
    <a href="{{ route('dashboard.qr.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg ms-1"></i> کد QR جدید
    </a>
</div>

@if($qrCodes->count())
    <div class="row g-3">
        @foreach($qrCodes as $qr)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $qr->title }}</h6>
                                <small class="text-muted">{{ \Morilog\Jalali\Jalalian::fromCarbon($qr->created_at)->format('Y/m/d') }}</small>
                            </div>
                            <span class="badge bg-{{ $qr->type === 'dynamic' ? 'info' : 'secondary' }}">
                                {{ $qr->type === 'dynamic' ? 'پویا' : 'ثابت' }}
                            </span>
                        </div>

                        <div class="text-center mb-3">
                            @if(!empty($qrThumbnails[$qr->id]))
                                <img src="{{ $qrThumbnails[$qr->id] }}" alt="QR Code" style="max-width:120px;">
                            @else
                                <div class="text-muted small">پیش‌نمایش در دسترس نیست</div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between text-muted small mb-3">
                            <span><i class="bi bi-eye ms-1"></i> {{ $qr->scans_count }} اسکن</span>
                            @if($qr->card)
                                <span>{{ $qr->card->title }}</span>
                            @endif
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('dashboard.qr.show', $qr) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                <i class="bi bi-eye ms-1"></i> جزئیات
                            </a>
                            <a href="{{ route('dashboard.qr.download', [$qr, 'png']) }}" class="btn btn-sm btn-outline-success" title="دانلود PNG">
                                <i class="bi bi-download"></i>
                            </a>
                            <form action="{{ route('dashboard.qr.destroy', $qr) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف شود؟')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{ $qrCodes->links() }}
@else
    <x-empty-state icon="fa-qr-code" title="هنوز کد QR ندارید" description="اولین کد QR خود را بسازید." action-url="{{ route('dashboard.qr.create') }}" action-label="ساخت کد QR" />
@endif
@endsection
