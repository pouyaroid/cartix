@extends('layouts.admin')
@section('title', 'مدیریت قالب‌ها')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">مدیریت قالب‌ها</h5>
    <a href="{{ route('admin.templates.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg ms-1"></i> قالب جدید</a>
</div>
<div class="row g-3">
    @foreach($templates as $template)
    <div class="col-sm-6 col-lg-4 col-xl-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <h6 class="fw-bold mb-0">{{ $template->name }}</h6>
                    <div>
                        @if($template->is_active)
                            <span class="badge bg-success">فعال</span>
                        @else
                            <span class="badge bg-secondary">غیرفعال</span>
                        @endif
                        @if($template->is_premium)
                            <span class="badge bg-warning">پریمیوم</span>
                        @endif
                    </div>
                </div>
                <p class="text-muted small mb-2">{{ $template->description ?: 'بدون توضیح' }}</p>
                <div class="small text-muted mb-2"><i class="bi bi-tag ms-1"></i> {{ $template->category ?: 'عمومی' }}</div>
                <div class="small text-muted mb-2"><i class="bi bi-file-code ms-1"></i> {{ $template->blade_view }}</div>
            </div>
            <div class="card-footer bg-transparent d-flex gap-1">
                <a href="{{ route('admin.templates.preview-page', $template) }}" class="btn btn-sm btn-outline-info" title="پیش‌نمایش" target="_blank"><i class="bi bi-eye"></i></a>
                <a href="{{ route('admin.templates.edit', $template) }}" class="btn btn-sm btn-outline-primary flex-grow-1"><i class="bi bi-pencil ms-1"></i> ویرایش</a>
                <form action="{{ route('admin.templates.destroy', $template) }}" method="POST">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف شود؟')"><i class="bi bi-trash"></i></button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
