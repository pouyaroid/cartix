@extends('layouts.dashboard')

@section('title', 'پیش‌نمایش: ' . $card->title)
@section('page-title', 'پیش‌نمایش کارت')

@section('content')
<div class="mb-3">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('dashboard.cards.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-right ms-1"></i> بازگشت
            </a>
            <h6 class="mb-0 fw-bold">{{ $card->title }}</h6>
            @if($card->is_published)
                <span class="badge bg-success">منتشر شده</span>
            @else
                <span class="badge bg-warning">پیش‌نویس</span>
            @endif
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard.cards.builder', $card) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-pencil-square ms-1"></i> ویرایش
            </a>
            @if($card->is_published && $card->slug)
                <a href="{{ url('/' . $card->slug) }}" target="_blank" class="btn btn-sm btn-success">
                    <i class="bi bi-box-arrow-up-left ms-1"></i> مشاهده عمومی
                </a>
            @endif
        </div>
    </div>
</div>

<x-card-render.preview-frame :card="$card" mode="desktop" title="پیش‌نمایش کارت" :previewUrl="route('dashboard.cards.preview', $card)" />
@endsection
