@props(['block', 'depth' => 0])

@php
    $content = $block['content'] ?? [];
    $component = $block['component'] ?? 'widget-text';
    $children = $block['children'] ?? [];
    $blockId = $block['id'];
    $isLayout = in_array($component, ['layout-section', 'layout-column', 'layout-container', 'layout-tabs', 'layout-accordion']);
@endphp

<div class="block-wrapper" data-block-id="{{ $blockId }}" data-component="{{ $component }}" data-depth="{{ $depth }}">
    <div class="block-actions">
        <button class="btn btn-outline-secondary drag-handle" title="جابجایی">
            <i class="bi bi-grip-vertical"></i>
        </button>
        <button class="btn btn-outline-primary" onclick="duplicateBlock({{ $blockId }})" title="کپی">
            <i class="bi bi-copy"></i>
        </button>
        <button class="btn btn-outline-danger" onclick="deleteBlock({{ $blockId }})" title="حذف">
            <i class="bi bi-trash"></i>
        </button>
    </div>

    @if($component === 'widget-heading')
        @php $tag = $content['tag'] ?? 'h2'; @endphp
        <{{ $tag }} style="padding:10px 0;text-align:{{ $content['align'] ?? 'left' }}">
            {{ $content['text'] ?? 'عنوان' }}
        </{{ $tag }}>

    @elseif($component === 'widget-text')
        <p style="padding:5px 0">{{ $content['text'] ?? 'متن' }}</p>

    @elseif($component === 'widget-button')
        <div style="padding:10px 0;text-align:{{ $content['align'] ?? 'center' }}">
            <a href="{{ $content['link'] ?? '#' }}" class="btn btn-{{ $content['variant'] ?? 'primary' }}">
                {{ $content['text'] ?? 'دکمه' }}
            </a>
        </div>

    @elseif($component === 'widget-image')
        @if(!empty($content['src']))
            <div style="padding:10px 0">
                <img src="{{ $content['src'] }}" alt="{{ $content['alt'] ?? '' }}" style="max-width:100%;border-radius:8px">
                @if(!empty($content['caption']))
                    <p class="text-muted small text-center mt-1">{{ $content['caption'] }}</p>
                @endif
            </div>
        @else
            <div style="padding:20px;background:#f0f2f5;border-radius:8px;text-align:center">
                <i class="bi bi-image fs-1 text-muted"></i>
                <p class="text-muted small mb-0 mt-2">تصویر</p>
            </div>
        @endif

    @elseif($component === 'widget-video')
        @if(!empty($content['url']))
            <div style="padding:10px 0">
                <iframe src="{{ $content['url'] }}" style="width:100%;height:315px;border:none;border-radius:8px" allowfullscreen></iframe>
            </div>
        @else
            <div style="padding:20px;background:#f0f2f5;border-radius:8px;text-align:center">
                <i class="bi bi-play-circle fs-1 text-muted"></i>
                <p class="text-muted small mb-0 mt-2">ویدیو</p>
            </div>
        @endif

    @elseif($component === 'widget-divider')
        <hr style="border:1px solid {{ $content['color'] ?? '#e0e0e0' }};width:{{ $content['width'] ?? '100%' }}">

    @elseif($component === 'widget-spacer')
        <div style="height:{{ $content['height'] ?? '50px' }}"></div>

    @elseif($component === 'widget-icon')
        <div style="text-align:center;padding:10px 0">
            <i class="bi {{ $content['name'] ?? 'bi-star' }}" style="font-size:{{ $content['size'] ?? '2rem' }};color:{{ $content['color'] ?? '#4f46e5' }}"></i>
        </div>

    @elseif($component === 'widget-social')
        <div style="text-align:center;padding:10px 0">
            @foreach($content['links'] ?? [] as $link)
                <a href="{{ $link['url'] ?? '#' }}" class="btn btn-outline-secondary btn-sm m-1" target="_blank">
                    <i class="bi bi-{{ $link['platform'] ?? 'link' }}"></i>
                </a>
            @endforeach
        </div>

    @elseif($component === 'widget-map')
        <div style="height:{{ $content['height'] ?? '300px' }};background:#e0e0e0;border-radius:8px;display:flex;align-items:center;justify-content:center">
            <div class="text-center">
                <i class="bi bi-geo-alt fs-1 text-muted"></i>
                <p class="text-muted small mb-0 mt-2">نقشه</p>
            </div>
        </div>

    @elseif($component === 'widget-stats')
        <div style="padding:20px 0">
            <div class="row text-center">
                @foreach($content['items'] ?? [] as $item)
                    <div class="col">
                        <i class="bi {{ $item['icon'] ?? 'bi-graph-up' }} fs-3 text-primary d-block mb-2"></i>
                        <div class="fw-bold fs-4">{{ $item['value'] ?? '0' }}</div>
                        <div class="text-muted small">{{ $item['label'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        </div>

    @elseif($component === 'widget-logo')
        <div style="text-align:center;padding:10px 0">
            @if(!empty($content['src']))
                <img src="{{ $content['src'] }}" alt="{{ $content['alt'] ?? 'Logo' }}" style="width:{{ $content['width'] ?? '150px' }}">
            @else
                <div class="fw-bold fs-4 text-primary">LOGO</div>
            @endif
        </div>

    @elseif($component === 'content-list')
        <div style="padding:10px 0">
            <ul class="{{ ($content['style'] ?? '') === 'number' ? 'list-decimal' : 'list-disc' }}" style="padding-right:20px">
                @foreach($content['items'] ?? [] as $item)
                    <li style="padding:4px 0">{{ $item }}</li>
                @endforeach
            </ul>
        </div>

    @elseif($component === 'content-counter')
        <div style="text-align:center;padding:20px 0">
            <div class="fw-bold fs-1">{{ $content['value'] ?? 0 }}{{ $content['suffix'] ?? '' }}</div>
            <div class="text-muted">{{ $content['label'] ?? '' }}</div>
        </div>

    @elseif($component === 'content-progress')
        <div style="padding:10px 0">
            <div class="text-muted small mb-1">{{ $content['label'] ?? '' }}</div>
            <div class="progress" style="height:{{ $content['height'] ?? '8px' }}">
                <div class="progress-bar" style="width:{{ $content['value'] ?? 0 }}%;background:{{ $content['color'] ?? '#4f46e5' }}"></div>
            </div>
        </div>

    @elseif($component === 'advanced-countdown')
        <div style="text-align:center;padding:20px 0">
            <div class="fs-1 fw-bold" id="countdown-{{ $blockId }}">--:--:--:--</div>
            <div class="text-muted">{{ $content['label'] ?? '' }}</div>
        </div>

    @elseif($component === 'advanced-qrcode')
        <div style="text-align:center;padding:20px 0">
            <div style="width:{{ $content['size'] ?? 200 }}px;height:{{ $content['size'] ?? 200 }}px;background:#f0f2f5;border-radius:8px;display:inline-flex;align-items:center;justify-content:center">
                <i class="bi bi-qr-code fs-1 text-muted"></i>
            </div>
        </div>

    @elseif($component === 'deco-gradient')
        <div style="height:{{ $content['height'] ?? '200px' }};background:linear-gradient({{ $content['direction'] ?? '135deg' }}, {{ $content['from'] ?? '#667eea' }}, {{ $content['to'] ?? '#764ba2' }})"></div>

    @elseif($component === 'deco-shape')
        <div style="height:{{ $content['height'] ?? '80px' }};overflow:hidden">
            <svg viewBox="0 0 1440 100" style="width:100%;height:100%">
                <path fill="{{ $content['color'] ?? '#ffffff' }}" d="M0,50 C480,100 960,0 1440,50 L1440,100 L0,100 Z"></path>
            </svg>
        </div>

    @elseif($isLayout)
        <div style="padding:10px;border:1px dashed #ccc;border-radius:4px;min-height:60px">
            <div class="text-muted small mb-1" style="font-size:0.7rem">
                <i class="bi bi-box"></i> {{ $component }}
            </div>
            @if(!empty($children))
                @foreach($children as $child)
                    @include('dashboard.landing-pages._block', ['block' => $child, 'depth' => $depth + 1])
                @endforeach
            @else
                <div class="text-center text-muted py-2" style="font-size:0.8rem">
                    <i class="bi bi-plus"></i> بلوک‌ها را اینجا رها کنید
                </div>
            @endif
        </div>

    @else
        <div style="padding:10px;background:#f8f9fa;border-radius:4px">
            <small class="text-muted"><i class="bi bi-puzzle ms-1"></i> {{ $component }}</small>
        </div>
    @endif
</div>
