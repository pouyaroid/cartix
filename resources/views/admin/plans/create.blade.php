@extends('layouts.admin')
@section('title', 'ایجاد پلن')
@section('content')
<div class="row justify-content-center"><div class="col-lg-8"><div class="card shadow-sm"><div class="card-body p-4">
    <h5 class="fw-bold mb-4">ایجاد پلن جدید</h5>
    <form method="POST" action="{{ route('admin.plans.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">نام</label><input type="text" class="form-control" name="name" required></div>
            <div class="col-md-6"><label class="form-label">Slug</label><input type="text" class="form-control" name="slug" required></div>
            <div class="col-12"><label class="form-label">توضیحات</label><textarea class="form-control" name="description" rows="2"></textarea></div>
            <div class="col-md-4"><label class="form-label">قیمت ماهانه (تومان)</label><input type="number" class="form-control" name="price_monthly" value="0"></div>
            <div class="col-md-4"><label class="form-label">قیمت سالانه (تومان)</label><input type="number" class="form-control" name="price_yearly" value="0"></div>
            <div class="col-md-4"><label class="form-label">ترتیب</label><input type="number" class="form-control" name="sort_order" value="0"></div>
            <div class="col-md-4"><label class="form-label">حداکثر کارت</label><input type="number" class="form-control" name="max_cards" value="3"></div>
            <div class="col-md-4"><label class="form-label">حداکثر QR</label><input type="number" class="form-control" name="max_qr_codes" value="5"></div>
            <div class="col-md-4"><label class="form-label">فضا (مگابایت)</label><input type="number" class="form-control" name="max_storage_mb" value="50"></div>
            <div class="col-12"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="is_active" value="1" checked><label class="form-check-label">فعال</label></div></div>
        </div>
        <div class="mt-4"><button type="submit" class="btn btn-primary"><i class="bi bi-check-lg ms-1"></i> ایجاد</button> <a href="{{ route('admin.plans.index') }}" class="btn btn-outline-secondary">بازگشت</a></div>
    </form>
</div></div></div></div>
@endsection
