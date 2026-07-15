@props(['card', 'mode' => 'desktop', 'title' => 'پیش‌نمایش کارت', 'previewUrl' => null])

@php
    $width = '100%';
    if ($mode === 'mobile') $width = '375px';
    elseif ($mode === 'tablet') $width = '768px';

    if (!$previewUrl) {
        if (isset($card->id) && $card->id > 0) {
            $previewUrl = route('admin.cards.preview', $card);
        } else {
            $previewUrl = 'about:blank';
        }
    }
@endphp

<div class="card-preview-wrapper" style="background:#f0f0f0; border-radius:0.75rem; overflow:hidden;">
    <div class="d-flex align-items-center justify-content-between px-3 py-2 bg-white border-bottom">
        <div class="d-flex align-items-center gap-2">
            <span class="fw-medium small">{{ $title }}</span>
            @if($card->is_published ?? false)
                <span class="badge bg-success">منتشر شده</span>
            @else
                <span class="badge bg-warning">پیش‌نمایش نمونه</span>
            @endif
        </div>
        <div class="d-flex align-items-center gap-1">
            <button type="button" class="btn btn-sm btn-outline-secondary preview-mode-btn active" data-mode="desktop" title="دسکتاپ">
                <i class="bi bi-display"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary preview-mode-btn" data-mode="tablet" title="تبلت">
                <i class="bi bi-tablet"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary preview-mode-btn" data-mode="mobile" title="موبایل">
                <i class="bi bi-phone"></i>
            </button>
        </div>
    </div>

    <div class="d-flex justify-content-center p-3" style="min-height:500px; overflow:auto;">
        <div id="previewDeviceFrame" style="width:{{ $width }}; max-width:100%; transition:width 0.3s ease;">
            <div style="background:white; border-radius:0.5rem; box-shadow:0 4px 20px rgba(0,0,0,0.15); overflow:hidden; min-height:600px;">
                <iframe
                    id="previewIframe"
                    src="{{ $previewUrl }}"
                    style="width:100%; min-height:600px; border:none;"
                    loading="lazy"
                ></iframe>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modeBtns = document.querySelectorAll('.preview-mode-btn');
    var frame = document.getElementById('previewDeviceFrame');

    modeBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            modeBtns.forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');
            var mode = this.dataset.mode;
            if (mode === 'mobile') frame.style.width = '375px';
            else if (mode === 'tablet') frame.style.width = '768px';
            else frame.style.width = '100%';
        });
    });
});
</script>
@endpush
