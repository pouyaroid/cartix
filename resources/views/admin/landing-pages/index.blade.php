@extends('layouts.admin')

@section('title', 'لندینگ پیج‌ها')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">لندینگ پیج‌ها</h5>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-transparent">
        <form class="row g-2" method="GET">
            <div class="col-md-4">
                <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="جستجو...">
            </div>
            <div class="col-md-3">
                <select class="form-select form-select-sm" name="status">
                    <option value="">همه وضعیت‌ها</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>منتشر شده</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>پیش‌نویس</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">فیلتر</button>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>عنوان</th>
                    <th>کاربر</th>
                    <th>وضعیت</th>
                    <th>بازدید</th>
                    <th>تاریخ</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                    <tr>
                        <td>{{ $page->id }}</td>
                        <td>
                            <a href="{{ route('admin.landing-pages.edit', $page) }}" class="fw-medium">
                                {{ $page->title }}
                            </a>
                            <br><small class="text-muted">/{{ $page->slug }}</small>
                        </td>
                        <td>{{ $page->user->name ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $page->isPublished() ? 'success' : 'secondary' }}">
                                {{ $page->isPublished() ? 'منتشر' : 'پیش‌نویس' }}
                            </span>
                        </td>
                        <td>{{ number_format($page->views_count) }}</td>
                        <td>{{ $page->created_at->format('Y/m/d') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.landing-pages.edit', $page) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil" style="font-size:0.7rem"></i>
                                </a>
                                <form action="{{ route('admin.landing-pages.destroy', $page) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('آیا از حذف مطمئنید؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash" style="font-size:0.7rem"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">لندینگ پیجی یافت نشد</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $pages->links() }}</div>
@endsection
