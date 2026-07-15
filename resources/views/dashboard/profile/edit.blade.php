@extends('layouts.dashboard')

@section('title', 'ویرایش پروفایل')
@section('page-title', 'پروفایل')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h6 class="fw-bold mb-0">اطلاعات پروفایل</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('dashboard.profile.update') }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-medium">نام</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-medium">ایمیل</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label fw-medium">تلفن</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg ms-1"></i> ذخیره تغییرات
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Password Change --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent">
                <h6 class="fw-bold mb-0">تغییر رمز عبور</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('dashboard.profile.password') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-medium">رمز عبور فعلی</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                        @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-medium">رمز عبور جدید</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-medium">تأیید رمز عبور</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bi bi-key ms-1"></i> تغییر رمز عبور
                    </button>
                </form>
            </div>
        </div>

        {{-- Account Info --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h6 class="fw-bold mb-0">اطلاعات حساب</h6>
            </div>
            <div class="card-body small">
                <div class="mb-2"><span class="text-muted">شناسه:</span> #{{ $user->id }}</div>
                <div class="mb-2"><span class="text-muted">عضویت:</span> {{ \Morilog\Jalali\Jalalian::fromCarbon($user->created_at)->format('Y/m/d') }}</div>
                <div class="mb-2"><span class="text-muted">وضعیت:</span> <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">{{ $user->is_active ? 'فعال' : 'غیرفعال' }}</span></div>
                <div><span class="text-muted">نقش:</span> {{ $user->roles->pluck('name')->implode(', ') ?: 'ندارد' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
