@extends('layouts.dashboard')

@section('title', 'کارت‌های من')
@section('page-title', 'کارت‌های من')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">کارت‌های من</h5>
    <a href="{{ route('dashboard.cards.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i> کارت جدید
    </a>
</div>

<div class="row g-3">
    @forelse($cards as $card)
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="card-title fw-bold mb-0">{{ $card->title }}</h6>
                </div>

                @if($card->description)
                    <p class="card-text small text-muted">{{ Str::limit($card->description, 80) }}</p>
                @endif

                <div class="text-center bg-light rounded mb-3" style="height: 150px;">
                    @if($card->getFirstMedia('final-image'))
                        <img src="{{ $card->getFirstMedia('final-image')->getUrl() }}"
                             alt="{{ $card->title }}" class="img-fluid rounded"
                             style="max-height: 150px;">
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <i class="bi bi-image display-6 text-muted"></i>
                        </div>
                    @endif
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="bi bi-clock me-1"></i>
                        {{ \Morilog\Jalali\Jalalian::fromCarbon($card->created_at)->format('Y/m/d') }}
                    </small>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('dashboard.cards.edit', $card) }}" class="btn btn-outline-primary" title="ویرایش">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="{{ route('dashboard.cards.show', $card) }}" class="btn btn-outline-info" title="مشاهده">
                            <i class="bi bi-eye"></i>
                        </a>
                        <form action="{{ route('dashboard.cards.destroy', $card) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('آیا از حذف این کارت اطمینان دارید؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger" title="حذف">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox display-1 text-muted d-block mb-3"></i>
                <h5 class="text-muted">هنوز کارتی ایجاد نکرده‌اید</h5>
                <p class="text-muted">اولین کارت خود را بسازید!</p>
                <a href="{{ route('dashboard.cards.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> ایجاد کارت جدید
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

@if($cards->hasPages())
<div class="mt-4">
    {{ $cards->links() }}
</div>
@endif
@endsection
