@extends('layouts.dashboard')

@section('title', 'تنظیمات: ' . $page->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">تنظیمات: {{ $page->title }}</h5>
            <a href="{{ route('dashboard.landing-pages.builder', $page) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-pencil-square ms-1"></i> بازگشت به ویرایشگر
            </a>
        </div>

        <form method="POST" action="{{ route('dashboard.landing-pages.update', $page) }}">
            @csrf @method('PUT')

            {{-- Basic Settings --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-transparent">
                    <h6 class="fw-bold mb-0">تنظیمات پایه</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">عنوان</label>
                        <input type="text" class="form-control" name="title" value="{{ $page->title }}">
                    </div>
                </div>
            </div>

            {{-- SEO Settings --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-transparent">
                    <h6 class="fw-bold mb-0">SEO</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">عنوان SEO</label>
                        <input type="text" class="form-control" name="seo_title" value="{{ $page->seo_title }}" maxlength="255">
                        <small class="text-muted">عنوانی که در نتایج جستجو نمایش داده می‌شود</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">توضیحات SEO</label>
                        <textarea class="form-control" name="seo_description" rows="3" maxlength="500">{{ $page->seo_description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">تصویر OG</label>
                        <input type="url" class="form-control" name="og_image" value="{{ $page->og_image }}" placeholder="https://example.com/image.jpg">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Favicon</label>
                        <input type="url" class="form-control" name="favicon" value="{{ $page->favicon }}">
                    </div>
                </div>
            </div>

            {{-- Security --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-transparent">
                    <h6 class="fw-bold mb-0">امنیت</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">رمز عبور</label>
                        <input type="password" class="form-control" name="password" placeholder="برای محافظت با رمز">
                        @if($page->password)
                            <small class="text-success"><i class="bi bi-lock"></i> رمز عبور فعال است</small>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Schedule --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-transparent">
                    <h6 class="fw-bold mb-0">زمان‌بندی انتشار</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">انتشار در تاریخ</label>
                        <input type="datetime-local" class="form-control" name="scheduled_publish_at"
                               value="{{ $page->scheduled_publish_at?->format('Y-m-d\TH:i') }}">
                        <small class="text-muted">خالی بگذارید برای انتشار دستی</small>
                    </div>
                </div>
            </div>

            {{-- Custom Code --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-transparent">
                    <h6 class="fw-bold mb-0">کد سفارشی</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">CSS سفارشی</label>
                        <textarea class="form-control font-monospace" name="custom_css" rows="5" placeholder="/* Custom CSS */">{{ $page->custom_css }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">JavaScript سفارشی</label>
                        <textarea class="form-control font-monospace" name="custom_js" rows="5" placeholder="// Custom JS">{{ $page->custom_js }}</textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">ذخیره تنظیمات</button>
                <a href="{{ route('dashboard.landing-pages.builder', $page) }}" class="btn btn-outline-secondary">لغو</a>
            </div>
        </form>
    </div>
</div>
@endsection
