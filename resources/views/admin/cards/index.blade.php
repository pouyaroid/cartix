@extends('layouts.admin')
@section('title', 'مدیریت کارت‌ها')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">مدیریت کارت‌ها</h5>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>عنوان</th>
                    <th>کاربر</th>
                    <th>نوع</th>
                    <th>قالب</th>
                    <th>وضعیت</th>
                    <th>بازدید</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cards as $card)
                <tr>
                    <td>{{ $card->id }}</td>
                    <td>
                        @if($card->is_published)
                            <a href="/{{ $card->slug }}" target="_blank" class="text-decoration-none">{{ $card->title }}</a>
                        @else
                            {{ $card->title }}
                        @endif
                    </td>
                    <td>{{ $card->user->name }}</td>
                    <td><span class="badge bg-secondary">{{ $card->type }}</span></td>
                    <td>{{ $card->template->name ?? '-' }}</td>
                    <td>
                        @if($card->is_published)
                            <span class="badge bg-success">منتشر</span>
                        @else
                            <span class="badge bg-warning">پیش‌نویس</span>
                        @endif
                        @if($card->is_featured)
                            <span class="badge bg-info">ویژه</span>
                        @endif
                    </td>
                    <td>{{ number_format($card->views_count) }}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.cards.preview-page', $card) }}" class="btn btn-outline-primary btn-sm" title="پیش‌نمایش" target="_blank">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form action="{{ route('admin.cards.feature', $card) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-outline-info btn-sm" title="{{ $card->is_featured ? 'حذف ویژه' : 'ویژه کردن' }}">
                                    <i class="bi bi-star{{ $card->is_featured ? '-fill' : '' }}"></i>
                                </button>
                            </form>
                            <button class="btn btn-outline-danger btn-sm" onclick="confirmDelete('{{ route('admin.cards.destroy', $card) }}', '{{ $card->title }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $cards->links() }}
    </div>
</div>
@endsection
