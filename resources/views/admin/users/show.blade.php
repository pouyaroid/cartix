@extends('layouts.admin')
@section('title', 'کاربر: ' . $user->name)
@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent"><h6 class="fw-bold mb-0">اطلاعات کاربر</h6></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">نام</label><input type="text" class="form-control" name="name" value="{{ $user->name }}" required></div>
                        <div class="col-md-6"><label class="form-label">ایمیل</label><input type="email" class="form-control" name="email" value="{{ $user->email }}" required></div>
                        <div class="col-md-6"><label class="form-label">نقش</label>
                            <select class="form-select" name="role">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6"><label class="form-label">وضعیت</label>
                            <div class="form-check form-switch mt-2"><input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }}><label class="form-check-label">فعال</label></div>
                        </div>
                    </div>
                    <div class="mt-3"><button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check-lg ms-1"></i> بروزرسانی</button></div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent"><h6 class="fw-bold mb-0">خلاصه</h6></div>
            <div class="card-body">
                <div class="mb-2"><small class="text-muted">کارت‌ها:</small> <strong>{{ $user->cards->count() }}</strong></div>
                <div class="mb-2"><small class="text-muted">اشتراک:</small> <strong>{{ $user->subscriptions->first()->plan->name ?? 'ندارد' }}</strong></div>
                <div class="mb-2"><small class="text-muted">عضویت:</small> <span class="small">{{ \Morilog\Jalali\Jalalian::fromCarbon($user->created_at)->format('Y/m/d') }}</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
