@extends('layouts.dashboard')

@section('title', 'لندینگ پیج‌ها')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">لندینگ پیج‌ها</h4>
    <a href="{{ route('dashboard.landing-pages.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg ms-1"></i> لندینگ پیج جدید
    </a>
</div>

@if($pages->count() > 0)
    <div class="row g-3">
        @foreach($pages as $page)
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="fw-bold mb-0">{{ $page->title }}</h6>
                            <span class="badge bg-{{ $page->isPublished() ? 'success' : 'secondary' }}">
                                {{ $page->isPublished() ? 'منتشر شده' : 'پیش‌نویس' }}
                            </span>
                        </div>
                        <p class="text-muted small mb-2">/{{ $page->slug }}</p>
                        <div class="d-flex gap-3 text-muted small mb-3">
                            <span><i class="bi bi-eye ms-1"></i> {{ number_format($page->views_count) }}</span>
                            <span><i class="bi bi-calendar ms-1"></i> {{ $page->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <div class="d-flex gap-2">
                            <a href="{{ route('dashboard.landing-pages.builder', $page) }}" class="btn btn-sm btn-primary flex-grow-1">
                                <i class="bi bi-pencil-square ms-1"></i> ویرایشگر
                            </a>
                            <a href="{{ route('dashboard.landing-pages.preview', $page) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form action="{{ route('dashboard.landing-pages.publish', $page) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-{{ $page->isPublished() ? 'warning' : 'success' }}">
                                    <i class="bi bi-{{ $page->isPublished() ? 'eye-slash' : 'send' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('dashboard.landing-pages.destroy', $page) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('آیا از حذف این لندینگ پیج مطمئنید؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $pages->links() }}
    </div>
@else
    <div class="text-center py-5">
        <i class="bi bi-window-stack fs-1 text-muted d-block mb-3"></i>
        <h5 class="text-muted">هنوز لندینگ پیجی ندارید</h5>
        <p class="text-muted">اولین لندینگ پیج خود را بسازید</p>
        <a href="{{ route('dashboard.landing-pages.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg ms-1"></i> ساخت لندینگ پیج
        </a>
    </div>
@endif
@endsection
