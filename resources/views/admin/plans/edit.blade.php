@extends('layouts.admin')
@section('title', 'ویرایش پلن: ' . $plan->name)
@section('content')
<div class="row justify-content-center"><div class="col-lg-8"><div class="card shadow-sm"><div class="card-body p-4">
    <h5 class="fw-bold mb-4">ویرایش پلن: {{ $plan->name }}</h5>
    <form method="POST" action="{{ route('admin.plans.update', $plan) }}">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">نام</label><input type="text" class="form-control" name="name" value="{{ $plan->name }}" required></div>
            <div class="col-md-6"><label class="form-label">Slug</label><input type="text" class="form-control" name="slug" value="{{ $plan->slug }}" required></div>
            <div class="col-12"><label class="form-label">توضیحات</label><textarea class="form-control" name="description" rows="2">{{ $plan->description }}</textarea></div>
            <div class="col-md-4"><label class="form-label">قیمت ماهانه</label><input type="number" class="form-control" name="price_monthly" value="{{ $plan->price_monthly }}"></div>
            <div class="col-md-4"><label class="form-label">قیمت سالانه</label><input type="number" class="form-control" name="price_yearly" value="{{ $plan->price_yearly }}"></div>
            <div class="col-md-4"><label class="form-label">ترتیب</label><input type="number" class="form-control" name="sort_order" value="{{ $plan->sort_order }}"></div>
            <div class="col-md-4"><label class="form-label">حداکثر کارت</label><input type="number" class="form-control" name="max_cards" value="{{ $plan->max_cards }}"></div>
            <div class="col-md-4"><label class="form-label">حداکثر QR</label><input type="number" class="form-control" name="max_qr_codes" value="{{ $plan->max_qr_codes }}"></div>
            <div class="col-md-4"><label class="form-label">فضا (MB)</label><input type="number" class="form-control" name="max_storage_mb" value="{{ $plan->max_storage_mb }}"></div>
            <div class="col-12"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }}><label class="form-check-label">فعال</label></div></div>
        </div>
        <div class="mt-4"><button type="submit" class="btn btn-primary"><i class="bi bi-check-lg ms-1"></i> بروزرسانی</button> <a href="{{ route('admin.plans.index') }}" class="btn btn-outline-secondary">بازگشت</a></div>
    </form>
</div></div></div></div>
@endsection
