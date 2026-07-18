@extends('layouts.admin')

@section('title', 'پیش‌نمایش قالب: ' . $template->name)

@section('content')
<div class="mb-3">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.templates.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-right ms-1"></i> بازگشت
            </a>
            <h6 class="mb-0 fw-bold">{{ $template->name }}</h6>
            <span class="badge bg-info">{{ $template->category }}</span>
            @if($template->is_premium)
                <span class="badge bg-warning">پریمیوم</span>
            @endif
            @if($template->is_active)
                <span class="badge bg-success">فعال</span>
            @else
                <span class="badge bg-secondary">غیرفعال</span>
            @endif
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.templates.edit', $template) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-pencil-square ms-1"></i> ویرایش
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
        <i class="bi bi-eye" style="font-size:3rem;color:#d1d5db"></i>
        <p class="mt-3 text-muted">پیش‌نمایش قالب</p>
    </div>
</div>
@endsection
