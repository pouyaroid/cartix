@extends('layouts.dashboard')

@section('title', 'اشتراک')
@section('page-title', 'اشتراک و پلن‌ها')

@section('content')
@if($currentSubscription)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-1">اشتراک فعلی: {{ $currentSubscription->plan->name }}</h6>
                    <small class="text-muted">
                        تاریخ پایان: {{ \Morilog\Jalali\Jalalian::fromCarbon($currentSubscription->ends_at)->format('Y/m/d') }}
                    </small>
                </div>
                <span class="badge bg-success fs-6">فعال</span>
            </div>
        </div>
    </div>
@endif

<h5 class="fw-bold mb-4">پلن‌های اشتراک</h5>

<div class="row g-4">
    @foreach($plans as $plan)
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 {{ $currentSubscription?->plan_id === $plan->id ? 'border-primary border-2' : '' }}">
                <div class="card-body text-center">
                    @if($currentSubscription?->plan_id === $plan->id)
                        <span class="badge bg-primary mb-2">پلن فعلی</span>
                    @endif
                    <h5 class="fw-bold">{{ $plan->name }}</h5>
                    <div class="my-3">
                        <span class="fs-3 fw-bold text-primary">{{ number_format($plan->price_monthly) }}</span>
                        <span class="text-muted small">تومان/ماه</span>
                    </div>
                    @if($plan->price_yearly > 0)
                        <div class="text-muted small mb-3">
                            سالانه: {{ number_format($plan->price_yearly) }} تومان
                        </div>
                    @endif
                    <ul class="list-unstyled small text-start mb-3">
                        @foreach($plan->features ?? [] as $feature)
                            <li class="mb-1"><i class="bi bi-check-circle-fill text-success ms-1"></i> {{ $feature }}</li>
                        @endforeach
                        <li class="mb-1"><i class="bi bi-check-circle-fill text-success ms-1"></i> حداکثر {{ $plan->max_qr_codes }} کد QR</li>
                        <li><i class="bi bi-check-circle-fill text-success ms-1"></i> {{ $plan->max_media_storage }} MB فضا</li>
                    </ul>
                    @if($currentSubscription?->plan_id !== $plan->id && $plan->is_active)
                        <form method="POST" action="{{ route('dashboard.subscription.upgrade') }}">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-arrow-up-circle ms-1"></i> ارتقا
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
