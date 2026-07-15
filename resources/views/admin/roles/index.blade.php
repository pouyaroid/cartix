@extends('layouts.admin')
@section('title', 'نقش‌ها و دسترسی‌ها')
@section('content')
<h5 class="fw-bold mb-4">نقش‌ها و دسترسی‌ها</h5>
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between">
                <h6 class="fw-bold mb-0">نقش‌ها</h6>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal"><i class="bi bi-plus"></i> نقش جدید</button>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>نام</th><th>تعداد دسترسی</th><th>عملیات</th></tr></thead>
                    <tbody>
                        @foreach($roles as $role)
                        <tr>
                            <td><span class="badge bg-primary">{{ $role->name }}</span></td>
                            <td>{{ $role->permissions->count() }}</td>
                            <td>
                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف شود؟')"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent"><h6 class="fw-bold mb-0">دسترسی‌ها</h6></div>
            <div class="card-body">
                @foreach($permissions as $group => $perms)
                    <div class="mb-3">
                        <h6 class="small fw-bold text-muted">{{ $group }}</h6>
                        @foreach($perms as $perm)
                            <span class="badge bg-light text-dark border me-1 mb-1">{{ $perm->name }}</span>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addRoleModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h6 class="modal-title fw-bold">افزودن نقش</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form method="POST" action="{{ route('admin.roles.store') }}">
        @csrf
        <div class="modal-body">
            <label class="form-label">نام نقش</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        <div class="modal-footer"><button type="submit" class="btn btn-primary btn-sm">ایجاد</button></div>
    </form>
</div></div></div>
@endsection
