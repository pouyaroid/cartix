@extends('layouts.admin')

@section('title', 'پیش‌نمایش: ' . $card->title)
@section('page-title', 'پیش‌نمایش کارت')

@section('content')
<div class="mb-3">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.cards.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-right ms-1"></i> بازگشت
            </a>
            <h6 class="mb-0 fw-bold">{{ $card->title }}</h6>
            @if($card->is_published)
                <span class="badge bg-success">منتشر شده</span>
            @else
                <span class="badge bg-warning">پیش‌نویس</span>
            @endif
            <span class="badge bg-secondary">{{ $card->user->name }}</span>
        </div>
        <div class="d-flex gap-2">
            @if($card->is_published && $card->slug)
                <a href="{{ url('/' . $card->slug) }}" target="_blank" class="btn btn-sm btn-success">
                    <i class="bi bi-box-arrow-up-left ms-1"></i> مشاهده عمومی
                </a>
            @endif
            <form action="{{ route('admin.cards.feature', $card) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-{{ $card->is_featured ? 'warning' : 'outline-warning' }}">
                    <i class="bi bi-star{{ $card->is_featured ? '-fill' : '' }} ms-1"></i>
                    {{ $card->is_featured ? 'حذف از ویژه' : 'ویژه کردن' }}
                </button>
            </form>
            <form action="{{ route('admin.cards.destroy', $card) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('آیا مطمئن هستید؟')">
                    <i class="bi bi-trash ms-1"></i> حذف
                </button>
            </form>
        </div>
    </div>
</div>

<x-card-render.preview-frame :card="$card" mode="desktop" title="پیش‌نمایش کارت کاربر" :previewUrl="route('admin.cards.preview', $card)" />
@endsection
