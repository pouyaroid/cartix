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
    @php
        $origW = max($card->canvas_width, 1);
        $origH = max($card->canvas_height, 1);
        $maxW = 340;
        $scale = min(1, $maxW / $origW);
        $dispW = (int) round($origW * $scale);
        $dispH = (int) round($origH * $scale);
    @endphp
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="card-title fw-bold mb-0">{{ $card->title }}</h6>
                </div>

                @if($card->description)
                    <p class="card-text small text-muted">{{ Str::limit($card->description, 80) }}</p>
                @endif

                <a href="{{ route('dashboard.cards.edit', $card) }}" class="text-decoration-none">
                    <div class="rounded mb-3 overflow-hidden border shadow-sm bg-white" style="line-height: 0;">
                        <canvas id="cp{{ $card->id }}" width="{{ $dispW }}" height="{{ $dispH }}"></canvas>
                    </div>
                </a>

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

@php
    $cardDesigns = $cards->mapWithKeys(fn($c) => [$c->id => $c->design_data ?? ['objects' => []]]);
    $cardSizes = $cards->mapWithKeys(fn($c) => [
        $c->id => [
            'w' => $c->canvas_width,
            'h' => $c->canvas_height,
            'bg' => $c->settings['background_color'] ?? '#ffffff',
        ]
    ]);
@endphp

<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script>
(function() {
    var designs = {!! json_encode($cardDesigns) !!};
    var sizes = {!! json_encode($cardSizes) !!};

    function render() {
        if (typeof fabric === 'undefined') { setTimeout(render, 200); return; }
        Object.keys(designs).forEach(function(id) {
            var el = document.getElementById('cp' + id);
            if (!el || el.dataset.done) return;
            el.dataset.done = '1';
            var s = sizes[id];
            var d = designs[id];
            var origW = s.w, origH = s.h;
            var maxW = 340;
            var scale = Math.min(1, maxW / origW);
            var dispW = Math.round(origW * scale);
            var dispH = Math.round(origH * scale);
            el.width = dispW;
            el.height = dispH;
            var c = new fabric.Canvas(el, {
                width: dispW,
                height: dispH,
                backgroundColor: s.bg,
                selection: false,
                readOnly: true,
            });
            if (d && d.objects && d.objects.length) {
                c.loadFromJSON(d, function() {
                    c.setZoom(scale);
                    c.setWidth(dispW);
                    c.setHeight(dispH);
                    c.forEachObject(function(o) { o.selectable = false; o.evented = false; });
                    c.renderAll();
                });
            }
        });
    }
    render();
})();
</script>
@endsection
