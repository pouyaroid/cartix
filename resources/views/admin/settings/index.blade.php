@extends('layouts.admin')
@section('title', 'تنظیمات')
@section('content')
<h5 class="fw-bold mb-4">تنظیمات</h5>
<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    <div class="row g-3">
        @foreach($settings as $group => $groupSettings)
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-transparent"><h6 class="fw-bold mb-0">{{ $group }}</h6></div>
                <div class="card-body">
                    @foreach($groupSettings as $setting)
                    <div class="mb-3">
                        <label class="form-label small">{{ $setting->key }}</label>
                        @if($setting->type === 'textarea')
                            <textarea class="form-control" name="settings[{{ $group }}][{{ $setting->key }}]" rows="2">{{ $setting->value }}</textarea>
                        @else
                            <input type="text" class="form-control" name="settings[{{ $group }}][{{ $setting->key }}]" value="{{ $setting->value }}">
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-3"><button type="submit" class="btn btn-primary"><i class="bi bi-check-lg ms-1"></i> ذخیره تنظیمات</button></div>
</form>
@endsection
