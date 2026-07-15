<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->title }} - رمز عبور</title>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        * { font-family: 'Vazirmatn', sans-serif; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .password-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            padding: 40px;
            max-width: 400px;
            width: 90%;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="password-card">
        <div class="mb-4">
            <i class="bi bi-lock fs-1 text-primary d-block mb-3"></i>
            <h4 class="fw-bold">{{ $page->title }}</h4>
            <p class="text-muted">این صفحه با رمز عبور محافظت می‌شود</p>
        </div>

        <form method="POST" action="{{ route('lp.password', $page->slug) }}">
            @csrf
            <div class="mb-3">
                <input type="password" name="password" class="form-control form-control-lg text-center"
                       placeholder="رمز عبور را وارد کنید" required autofocus>
                @error('password')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary btn-lg w-100">
                <i class="bi bi-box-arrow-in-left ms-1"></i> ورود
            </button>
        </form>
    </div>
</body>
</html>
