@extends('layouts.admin')

@section('title', 'ویرایش: ' . $page->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">ویرایش لندینگ پیج</h5>
            <a href="{{ route('admin.landing-pages.index') }}" class="btn btn-outline-secondary btn-sm">بازگشت</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.landing-pages.update', $page) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-medium">عنوان</label>
                        <input type="text" class="form-control" name="title" value="{{ $page->title }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">وضعیت</label>
                        <select class="form-select" name="status">
                            <option value="draft" {{ $page->status === 'draft' ? 'selected' : '' }}>پیش‌نویس</option>
                            <option value="published" {{ $page->status === 'published' ? 'selected' : '' }}>منتشر شده</option>
                            <option value="archived" {{ $page->status === 'archived' ? 'selected' : '' }}>آرشیو شده</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">کاربر</label>
                        <input type="text" class="form-control" value="{{ $page->user->name ?? '-' }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">بازدید</label>
                        <input type="text" class="form-control" value="{{ number_format($page->views_count) }}" disabled>
                    </div>
                    <button type="submit" class="btn btn-primary">ذخیره</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
