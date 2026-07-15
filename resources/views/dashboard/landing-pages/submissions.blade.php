@extends('layouts.dashboard')

@section('title', 'فرهای ارسال شده: ' . $page->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">فرهای ارسال شده</h5>
        <small class="text-muted">{{ $page->title }}</small>
    </div>
    <a href="{{ route('dashboard.landing-pages.builder', $page) }}" class="btn btn-primary btn-sm">
        <i class="bi bi-pencil-square ms-1"></i> ویرایشگر
    </a>
</div>

@if($submissions->count() > 0)
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>فرم</th>
                        <th>داده‌ها</th>
                        <th>تاریخ</th>
                        <th>وضعیت</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submissions as $submission)
                        <tr class="{{ $submission->is_read ? '' : 'table-warning' }}">
                            <td>{{ $submission->id }}</td>
                            <td>{{ $submission->form_id }}</td>
                            <td>
                                @foreach($submission->data as $key => $value)
                                    <span class="badge bg-light text-dark me-1">{{ $key }}: {{ $value }}</span>
                                @endforeach
                            </td>
                            <td>{{ $submission->created_at->diffForHumans() }}</td>
                            <td>
                                @if($submission->is_read)
                                    <span class="badge bg-success">خوانده شده</span>
                                @else
                                    <span class="badge bg-warning">جدید</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $submissions->links() }}
    </div>
@else
    <div class="text-center py-5">
        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
        <p class="text-muted">هنوز فرمی ارسال نشده است</p>
    </div>
@endif
@endsection
