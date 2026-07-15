@extends('layouts.admin')

@section('title', 'ویرایش قالب: ' . $template->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">ویرایش قالب: {{ $template->name }}</h5>
            <a href="{{ route('admin.landing-page-templates.index') }}" class="btn btn-outline-secondary btn-sm">بازگشت</a>
        </div>

        <form method="POST" action="{{ route('admin.landing-page-templates.update', $template) }}">
            @csrf @method('PUT')
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">نام <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ $template->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">توضیحات</label>
                        <textarea class="form-control" name="description" rows="3">{{ $template->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">دسته‌بندی</label>
                        <input type="text" class="form-control" name="category" value="{{ $template->category }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">داده قالب (JSON)</label>
                        <textarea class="form-control font-monospace" name="data" rows="15">{{ json_encode($template->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $template->is_active ? 'checked' : '' }}>
                                <label class="form-check-label">فعال</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_premium" value="1" {{ $template->is_premium ? 'checked' : '' }}>
                                <label class="form-check-label">پریمیوم</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
        </form>
    </div>
</div>
@endsection
