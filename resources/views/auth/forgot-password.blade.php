@extends('layouts.app')

@section('title', 'بازیابی رمز عبور')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-key text-primary" style="font-size: 3rem;"></i>
                    <h4 class="mt-2 fw-bold">بازیابی رمز عبور</h4>
                    <p class="text-muted small">ایمیل خود را وارد کنید تا لینک بازیابی برایتان ارسال شود.</p>
                </div>

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">ایمیل</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-envelope ms-1"></i> ارسال لینک بازیابی
                    </button>
                </form>

                <hr>
                <p class="text-center small text-muted mb-0">
                    <a href="{{ route('login') }}" class="text-decoration-none">بازگشت به ورود</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
