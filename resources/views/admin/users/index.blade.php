@extends('layouts.admin')
@section('title', 'مدیریت کاربران')
@section('content')
<h5 class="fw-bold mb-4">مدیریت کاربران</h5>
<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover data-table">
            <thead><tr><th>#</th><th>نام</th><th>ایمیل</th><th>نقش</th><th>وضعیت</th><th>تاریخ عضویت</th><th>عملیات</th></tr></thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge bg-primary">{{ $user->roles->pluck('name')->implode(', ') ?: 'ندارد' }}</span></td>
                    <td><span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">{{ $user->is_active ? 'فعال' : 'غیرفعال' }}</span></td>
                    <td class="small text-muted">{{ \Morilog\Jalali\Jalalian::fromCarbon($user->created_at)->format('Y/m/d') }}</td>
                    <td><a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    </div>
</div>
@endsection
