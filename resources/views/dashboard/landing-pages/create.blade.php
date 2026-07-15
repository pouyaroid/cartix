@extends('layouts.dashboard')

@section('title', 'لندینگ پیج جدید')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent">
                <h5 class="fw-bold mb-0">ایجاد لندینگ پیج جدید</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('dashboard.landing-pages.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium">عنوان <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               name="title" value="{{ old('title') }}" required
                               placeholder="مثال: صفحه فروش محصول">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">توضیحات</label>
                        <textarea class="form-control" name="description" rows="3"
                                  placeholder="توضیحات اختیاری">{{ old('description') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-plus-lg ms-1"></i> ایجاد و شروع ویرایش
                        </button>
                        <a href="{{ route('dashboard.landing-pages.index') }}" class="btn btn-outline-secondary">
                            لغو
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
