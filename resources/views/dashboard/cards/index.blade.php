@extends('layouts.dashboard')

@section('title', 'کارت‌های من')
@section('page-title', 'کارت‌های من')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">کارت‌های من</h5>
    <a href="{{ route('dashboard.cards.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg ms-1"></i> کارت جدید
    </a>
</div>

@if($cards->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-card-heading text-muted" style="font-size: 4rem;"></i>
        <h5 class="mt-3">هنوز کارتی ندارید</h5>
        <p class="text-muted">اولین کارت دیجیتال خود را بسازید!</p>
        <a href="{{ route('dashboard.cards.create') }}" class="btn btn-primary">ایجاد کارت</a>
    </div>
@else
    <div class="row g-3">
        @foreach($cards as $card)
        <div class="col-sm-6 col-lg-4 col-xl-3">
            <div class="card card-preview shadow-sm h-100">
                <div class="card-cover" style="background-image: url('{{ $card->cover_image ? asset('storage/' . $card->cover_image) : '' }}'); background-color: var(--bs-primary-subtle);">
                    @if(!$card->cover_image)
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <i class="bi bi-card-heading text-primary opacity-25" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                </div>
                <div class="card-body text-center">
                    <div class="mb-2">
                        @if($card->profile_image)
                            <img src="{{ asset('storage/' . $card->profile_image) }}" class="card-avatar" alt="{{ $card->title }}">
                        @else
                            <div class="card-avatar bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width:64px;height:64px;font-size:1.5rem;">
                                {{ mb_substr($card->title, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <h6 class="fw-bold mb-1">{{ $card->title }}</h6>
                    <div class="small text-muted mb-2">
                        {{ $card->type ? \App\Enums\CardTypeEnum::tryFrom($card->type)?->label() : '' }}
                        @if($card->template)
                            <span class="badge bg-secondary ms-1">{{ $card->template->name }}</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-center gap-1 mb-2">
                        @if($card->is_published)
                            <span class="badge bg-success"><i class="bi bi-check-circle ms-1"></i>منتشر شده</span>
                        @else
                            <span class="badge bg-warning text-dark"><i class="bi bi-pencil ms-1"></i>پیش‌نویس</span>
                        @endif
                        @if($card->is_featured)
                            <span class="badge bg-info"><i class="bi bi-star ms-1"></i>ویژه</span>
                        @endif
                    </div>
                    <div class="small text-muted">
                        <i class="bi bi-eye ms-1"></i> {{ number_format($card->views_count) }} بازدید
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-3">
                    <div class="d-flex gap-1">
                        <a href="{{ route('dashboard.cards.builder', $card) }}" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="bi bi-pencil-square ms-1"></i> ویرایش
                        </a>
                        <a href="{{ route('dashboard.cards.preview-page', $card) }}" class="btn btn-sm btn-outline-info" title="پیش‌نمایش" target="_blank">
                            <i class="bi bi-eye"></i>
                        </a>
                        <form action="{{ route('dashboard.cards.publish', $card) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-{{ $card->is_published ? 'warning' : 'success' }}" title="{{ $card->is_published ? 'پیش‌نویس' : 'انتشار' }}">
                                <i class="bi {{ $card->is_published ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                            </button>
                        </form>
                        <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('dashboard.cards.destroy', $card) }}', '{{ $card->title }}')" title="حذف">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-3">
        {{ $cards->links() }}
    </div>
@endif
@endsection
