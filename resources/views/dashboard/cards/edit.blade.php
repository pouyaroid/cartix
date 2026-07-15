@extends('layouts.dashboard')

@section('title', 'ویرایش: ' . $card->title)
@section('page-title', 'ویرایش کارت')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">ویرایش کارت: {{ $card->title }}</h5>

                <form method="POST" action="{{ route('dashboard.cards.update', $card) }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label fw-medium">عنوان کارت</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $card->title) }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="template_id" class="form-label fw-medium">قالب</label>
                        <select class="form-select select2" id="template_id" name="template_id">
                            <option value="">بدون قالب</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}" {{ old('template_id', $card->template_id) == $template->id ? 'selected' : '' }}>
                                    {{ $template->name }} {{ $template->is_premium ? '(پریمیوم)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-medium">توضیحات</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $card->description) }}</textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-medium">تلفن</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $card->phone) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-medium">ایمیل</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $card->email) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="website" class="form-label fw-medium">وبسایت</label>
                        <input type="url" class="form-control" id="website" name="website" value="{{ old('website', $card->website) }}">
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label fw-medium">آدرس</label>
                        <textarea class="form-control" id="address" name="address" rows="2">{{ old('address', $card->address) }}</textarea>
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3">SEO</h6>

                    <div class="mb-3">
                        <label for="seo_title" class="form-label fw-medium">عنوان SEO</label>
                        <input type="text" class="form-control" id="seo_title" name="seo_title" value="{{ old('seo_title', $card->seo_title) }}">
                    </div>

                    <div class="mb-3">
                        <label for="seo_description" class="form-label fw-medium">توضیحات SEO</label>
                        <textarea class="form-control" id="seo_description" name="seo_description" rows="2">{{ old('seo_description', $card->seo_description) }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg ms-1"></i> ذخیره تغییرات
                        </button>
                        <a href="{{ route('dashboard.cards.builder', $card) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil-square ms-1"></i> ویرایشگر
                        </a>
                        <a href="{{ route('dashboard.cards.index') }}" class="btn btn-outline-secondary">بازگشت</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
