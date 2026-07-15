@extends('layouts.admin')

@section('title', 'قالب لندینگ پیج')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">قالب‌های لندینگ پیج</h5>
    <a href="{{ route('admin.landing-page-templates.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg ms-1"></i> قالب جدید
    </a>
</div>

<div class="row g-3">
    @forelse($templates as $template)
        <div class="col-md-4 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="fw-bold mb-0">{{ $template->name }}</h6>
                        <span class="badge bg-{{ $template->is_active ? 'success' : 'secondary' }}" style="font-size:0.65rem">
                            {{ $template->is_active ? 'فعال' : 'غیرفعال' }}
                        </span>
                    </div>
                    <p class="text-muted small mb-2">{{ $template->description ?: 'بدون توضیح' }}</p>
                    <span class="badge bg-light text-dark">{{ $template->category }}</span>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex gap-1">
                        <a href="{{ route('admin.landing-page-templates.edit', $template) }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                            <i class="bi bi-pencil ms-1"></i> ویرایش
                        </a>
                        <form action="{{ route('admin.landing-page-templates.destroy', $template) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('آیا از حذف مطمئنید؟')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-layout-text-window fs-1 text-muted d-block mb-2"></i>
                <p class="text-muted">قالبی وجود ندارد</p>
            </div>
        </div>
    @endforelse
</div>
@endsection
