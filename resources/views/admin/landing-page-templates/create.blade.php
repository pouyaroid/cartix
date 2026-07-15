@extends('layouts.admin')

@section('title', 'قالب جدید')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">ایجاد قالب جدید</h5>
            <a href="{{ route('admin.landing-page-templates.index') }}" class="btn btn-outline-secondary btn-sm">بازگشت</a>
        </div>

        <form method="POST" action="{{ route('admin.landing-page-templates.store') }}">
            @csrf
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">نام <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">توضیحات</label>
                        <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">دسته‌بندی <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="category" value="{{ old('category') }}" required
                               placeholder="مثال: business, restaurant, wedding">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">داده قالب (JSON)</label>
                        <textarea class="form-control font-monospace" name="data" rows="10" placeholder='{"blocks": []}'>{{ old('data') }}</textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                                <label class="form-check-label">فعال</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_premium" value="1">
                                <label class="form-check-label">پریمیوم</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">ذخیره قالب</button>
        </form>
    </div>
</div>
@endsection
