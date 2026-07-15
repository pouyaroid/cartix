@extends('layouts.dashboard')

@section('title', 'ایجاد کارت جدید')
@section('page-title', 'ایجاد کارت جدید')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">ایجاد کارت جدید</h5>

                <form method="POST" action="{{ route('dashboard.cards.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-medium">نوع کارت</label>
                        <div class="row g-2">
                            @foreach($types as $type)
                            <div class="col-6 col-md-4">
                                <label class="card card-body text-center cursor-pointer border {{ old('type') === $type->value ? 'border-primary bg-primary-subtle' : '' }}" style="cursor:pointer;">
                                    <input type="radio" name="type" value="{{ $type->value }}" class="d-none" {{ old('type', 'business') === $type->value ? 'checked' : '' }}>
                                    <div class="small fw-medium">{{ $type->label() }}</div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label fw-medium">عنوان کارت</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required placeholder="مثال: کارت ویزیت شرکت">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="template-section">
                        <label for="template_id" class="form-label fw-medium">قالب (اختیاری)</label>
                        <select class="form-select select2" id="template_id" name="template_id">
                            <option value="">بدون قالب - پیش‌فرض</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}" data-category="{{ $template->category }}" {{ old('template_id') == $template->id ? 'selected' : '' }}>
                                    {{ $template->name }} {{ $template->is_premium ? '(پریمیوم)' : '' }} - {{ $template->category }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">قالب‌های عروسی با تم «wedding» نمایش داده می‌شوند</div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-medium">توضیحات (اختیاری)</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg ms-1"></i> ایجاد کارت
                        </button>
                        <a href="{{ route('dashboard.cards.index') }}" class="btn btn-outline-secondary">بازگشت</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('input[name="type"]').forEach(radio => {
    radio.closest('label').addEventListener('click', function() {
        this.querySelector('input').checked = true;
        document.querySelectorAll('input[name="type"]').forEach(r => {
            r.closest('label').classList.remove('border-primary', 'bg-primary-subtle');
        });
        this.classList.add('border-primary', 'bg-primary-subtle');
    });
});

// Filter templates by category when card type changes
document.querySelectorAll('input[name="type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const type = this.value;
        const templateSelect = document.getElementById('template_id');
        const options = templateSelect.querySelectorAll('option[data-category]');

        // Define category mapping
        const categoryMap = {
            'wedding': 'wedding',
            'birthday': 'wedding',
            'event': 'invitation',
            'conference': 'business',
            'business': 'business',
            'corporate': 'business',
            'real_estate': 'business',
            'restaurant': 'business',
            'doctor': 'business',
            'lawyer': 'business',
            'portfolio': 'general',
            'resume': 'general',
        };

        const targetCategory = categoryMap[type] || 'general';

        options.forEach(option => {
            const category = option.getAttribute('data-category');
            if (category === targetCategory || category === 'general') {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });

        // Reset selection
        templateSelect.value = '';
    });
});
</script>
@endpush
@endsection
