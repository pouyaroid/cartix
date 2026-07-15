@extends('layouts.app')

@section('title', 'ورود')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-card-heading text-primary" style="font-size: 3rem;"></i>
                    <h4 class="mt-2 fw-bold">ورود به حساب</h4>
                    <p class="text-muted small">خوش آمدید! لطفاً وارد شوید.</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">ایمیل</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">رمز عبور</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label small" for="remember">مرا به خاطر بسپار</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="small text-decoration-none">فراموشی رمز عبور</a>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-box-arrow-in-left ms-1"></i> ورود
                    </button>
                </form>

                <hr>

                <p class="text-center small text-muted mb-0">
                    حساب ندارید؟ <a href="{{ route('register') }}" class="text-decoration-none">ثبت نام کنید</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
