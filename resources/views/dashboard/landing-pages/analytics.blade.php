@extends('layouts.dashboard')

@section('title', 'آمار: ' . $page->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">آمار لندینگ پیج</h5>
        <small class="text-muted">{{ $page->title }}</small>
    </div>
    <a href="{{ route('dashboard.landing-pages.builder', $page) }}" class="btn btn-primary btn-sm">
        <i class="bi bi-pencil-square ms-1"></i> ویرایشگر
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-eye fs-3 text-primary d-block mb-2"></i>
                <h3 class="fw-bold mb-0">{{ number_format($totalViews) }}</h3>
                <small class="text-muted">بازدید کل</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-envelope fs-3 text-success d-block mb-2"></i>
                <h3 class="fw-bold mb-0">{{ number_format($totalSubmissions) }}</h3>
                <small class="text-muted">فر ارسال شده</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-envelope-exclamation fs-3 text-warning d-block mb-2"></i>
                <h3 class="fw-bold mb-0">{{ number_format($unreadSubmissions) }}</h3>
                <small class="text-muted">خوانده نشده</small>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-transparent">
        <h6 class="fw-bold mb-0">بازدید ۳۰ روز اخیر</h6>
    </div>
    <div class="card-body">
        @if($analytics->count() > 0)
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>تاریخ</th>
                        <th>بازدید</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($analytics as $day)
                        <tr>
                            <td>{{ $day->date->format('Y/m/d') }}</td>
                            <td>{{ number_format($day->views) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center text-muted py-4">
                <p>هنوز آماری ثبت نشده است</p>
            </div>
        @endif
    </div>
</div>
@endsection
