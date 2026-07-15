@extends('layouts.admin')
@section('title', 'ویرایش قالب: ' . $template->name)
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">ویرایش قالب: {{ $template->name }}</h5>
                <form method="POST" action="{{ route('admin.templates.update', $template) }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">نام</label><input type="text" class="form-control" name="name" value="{{ $template->name }}" required></div>
                        <div class="col-md-6"><label class="form-label">Slug</label><input type="text" class="form-control" name="slug" value="{{ $template->slug }}" required></div>
                        <div class="col-md-6"><label class="form-label">دسته‌بندی</label><input type="text" class="form-control" name="category" value="{{ $template->category }}"></div>
                        <div class="col-md-6"><label class="form-label">View Blade</label><input type="text" class="form-control" name="blade_view" value="{{ $template->blade_view }}" required></div>
                        <div class="col-12"><label class="form-label">توضیحات</label><textarea class="form-control" name="description" rows="3">{{ $template->description }}</textarea></div>
                        <div class="col-md-6"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $template->is_active ? 'checked' : '' }}><label class="form-check-label">فعال</label></div></div>
                        <div class="col-md-6"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="is_premium" value="1" {{ $template->is_premium ? 'checked' : '' }}><label class="form-check-label">پریمیوم</label></div></div>
                    </div>
                    <div class="mt-4"><button type="submit" class="btn btn-primary"><i class="bi bi-check-lg ms-1"></i> بروزرسانی</button> <a href="{{ route('admin.templates.index') }}" class="btn btn-outline-secondary">بازگشت</a></div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
