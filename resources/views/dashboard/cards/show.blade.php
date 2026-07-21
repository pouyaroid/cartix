@extends('layouts.dashboard')

@section('title', $card->title)
@section('page-title', $card->title)

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">{{ $card->title }}</h5>
                <div>
                    <a href="{{ route('dashboard.cards.edit', $card) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i> ویرایش
                    </a>
                    <a href="{{ route('dashboard.cards.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-right me-1"></i> بازگشت
                    </a>
                </div>
            </div>
            <div class="card-body text-center bg-light">
                @if($card->getFirstMedia('final-image'))
                    <img src="{{ $card->getFirstMedia('final-image')->getUrl() }}"
                         alt="{{ $card->title }}" class="img-fluid rounded shadow-sm"
                         style="max-height: 500px;">
                @else
                    <div class="d-flex align-items-center justify-content-center bg-white rounded border"
                         style="min-height: 400px;">
                        <div class="text-center text-muted">
                            <i class="bi bi-image display-1 d-block mb-2"></i>
                            <p>تصویر نهایی تولید نشده است</p>
                            <a href="{{ route('dashboard.cards.edit', $card) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil me-1"></i> ویرایش و تولید تصویر
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-header bg-transparent">
                <h6 class="mb-0 fw-bold">پیش‌نمایش طراحی</h6>
            </div>
            <div class="card-body text-center bg-light">
                <canvas id="previewCanvas" width="{{ $card->canvas_width }}" height="{{ $card->canvas_height }}"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-transparent">
                <h6 class="mb-0 fw-bold">اطلاعات</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">اندازه:</td>
                        <td>{{ $card->canvas_width }} × {{ $card->canvas_height }} px</td>
                    </tr>
                    <tr>
                        <td class="text-muted">تاریخ ایجاد:</td>
                        <td>{{ \Morilog\Jalali\Jalalian::fromCarbon($card->created_at)->format('Y/m/d H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">آخرین بروزرسانی:</td>
                        <td>{{ \Morilog\Jalali\Jalalian::fromCarbon($card->updated_at)->format('Y/m/d H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body d-grid gap-2">
                <a href="{{ route('dashboard.cards.edit', $card) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i> ویرایش
                </a>
                <form action="{{ route('dashboard.cards.destroy', $card) }}" method="POST"
                      onsubmit="return confirm('آیا از حذف این کارت اطمینان دارید؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-trash me-1"></i> حذف
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script>
(function() {
    const previewCanvas = new fabric.Canvas('previewCanvas', {
        width: {{ $card->canvas_width }},
        height: {{ $card->canvas_height }},
        backgroundColor: '{{ $card->settings["background_color"] ?? "#ffffff" }}',
        selection: false,
        readOnly: true,
    });
    @if($card->design_data && isset($card->design_data['objects']))
        previewCanvas.loadFromJSON(@json($card->design_data), function() {
            previewCanvas.forEachObject(function(obj) { obj.selectable = false; obj.evented = false; });
            previewCanvas.renderAll();
        });
    @endif
})();
</script>
@endpush
@endsection
