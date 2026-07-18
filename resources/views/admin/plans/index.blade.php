@extends('layouts.admin')
@section('title', 'مدیریت پلن‌ها')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">مدیریت پلن‌ها</h5>
    <a href="{{ route('admin.plans.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg ms-1"></i> پلن جدید</a>
</div>
<div class="row g-3">
    @foreach($plans as $plan)
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm h-100 {{ $plan->is_active ? 'border-primary' : '' }}">
            <div class="card-body text-center">
                <h5 class="fw-bold">{{ $plan->name }}</h5>
                <div class="display-6 fw-bold text-primary my-3">{{ number_format($plan->price_monthly) }}<small class="fs-6"> تومان/ماه</small></div>
                <ul class="list-unstyled small text-muted">
                    <li>{{ $plan->max_qr_codes == -1 ? 'نامحدود' : $plan->max_qr_codes }} کد QR</li>
                    <li>{{ $plan->max_storage_mb >= 1024 ? ($plan->max_storage_mb / 1024) . ' گیگابایت' : $plan->max_storage_mb . ' مگابایت' }} فضا</li>
                </ul>
            </div>
            <div class="card-footer bg-transparent d-flex gap-1">
                <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-sm btn-outline-primary flex-grow-1">ویرایش</a>
                <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف شود؟')"><i class="bi bi-trash"></i></button></form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
