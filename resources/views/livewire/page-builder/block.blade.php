@php
    $component = $block['component'] ?? '';
    $content = $block['content'] ?? [];
    $allStyles = $block['styles'] ?? [];
    $ds = $allStyles['desktop'] ?? [];
    $bs = $allStyles['button'] ?? [];
    $isSelected = $selectedBlockId == $block['id'];
    $isVisible = $block['is_visible'] ?? true;
    $blockId = $block['id'] ?? 0;

    $wrapperStyles = '';
    if (!empty($ds['padding'])) $wrapperStyles .= 'padding:' . e($ds['padding']) . ';';
    if (!empty($ds['margin'])) $wrapperStyles .= 'margin:' . e($ds['margin']) . ';';
    if (!empty($ds['background-color'])) $wrapperStyles .= 'background-color:' . e($ds['background-color']) . ';';
    if (!empty($ds['border-radius'])) $wrapperStyles .= 'border-radius:' . e($ds['border-radius']) . ';';
    if (!empty($ds['box-shadow'])) $wrapperStyles .= 'box-shadow:' . e($ds['box-shadow']) . ';';
    if (!empty($ds['max-width'])) $wrapperStyles .= 'max-width:' . e($ds['max-width']) . ';margin-left:auto;margin-right:auto;';
    if (!empty($ds['width']) && $ds['width'] !== '100%') $wrapperStyles .= 'width:' . e($ds['width']) . ';';
@endphp

<div
    class="block-wrapper {{ $isSelected ? 'selected' : '' }}"
    data-block-id="{{ $blockId }}"
    wire:click="selectBlock({{ $blockId }})"
    style="position:relative;border:2px solid {{ $isSelected ? '#6366f1' : 'transparent' }};min-height:20px;border-radius:4px;{{ $wrapperStyles }}{{ !$isVisible ? 'opacity:.4;' : '' }}"
>
    {{-- Block Toolbar --}}
    <div class="block-toolbar" style="{{ $isSelected ? 'display:flex' : '' }}">
        <span style="font-size:9px;padding:1px 6px;border-radius:3px;background:#6366f1;color:#fff;font-weight:500;margin-right:4px;display:flex;align-items:center">{{ $this->getBlockLabel($component) }}</span>
        <button class="sortable-handle" title="جابجایی" style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;background:transparent;border:none;border-radius:4px;color:#9ca3af;cursor:grab;font-size:12px"><i class="bi bi-grip-vertical"></i></button>
        <button class="block-action-btn" wire:click.stop="duplicateBlock({{ $blockId }})" title="تکثیر"><i class="bi bi-copy"></i></button>
        <button class="block-action-btn" wire:click.stop="toggleVisibility({{ $blockId }})" title="نمایش/مخفی" style="color:{{ $isVisible ? '#22c55e' : '#dc2626' }}"><i class="bi bi-{{ $isVisible ? 'eye' : 'eye-slash' }}"></i></button>
        <button class="block-action-btn danger" wire:click.stop="deleteBlock({{ $blockId }})" title="حذف"><i class="bi bi-trash3"></i></button>
    </div>

    {{-- Block Type Badge --}}
    <div class="block-badge">{{ $this->getBlockLabel($component) }}</div>

    {{-- Block Content --}}
    @if($component === 'widget-heading')
        @php
            $tag = $content['tag'] ?? 'h2';
            $headingSizes = ['h1'=>'48px','h2'=>'36px','h3'=>'28px','h4'=>'22px','h5'=>'18px','h6'=>'16px'];
            $fontSize = $headingSizes[$tag] ?? '36px';
            $textColor = $ds['color'] ?? '#111827';
            $textAlign = $ds['text-align'] ?? 'center';
        @endphp
        <{{ $tag }} style="font-size:{{ $fontSize }};font-weight:700;line-height:1.2;color:{{ $textColor }};margin:0;text-align:{{ $textAlign }}">{{ $content['text'] ?? 'عنوان' }}</{{ $tag }}>

    @elseif($component === 'widget-text')
        @php
            $textColor = $ds['color'] ?? '#4b5563';
            $textAlign = $ds['text-align'] ?? 'right';
        @endphp
        <p style="font-size:16px;line-height:1.7;color:{{ $textColor }};margin:0;text-align:{{ $textAlign }}">{{ $content['text'] ?? 'متن خود را اینجا بنویسید.' }}</p>

    @elseif($component === 'widget-button')
        @php
            $btnBg = $bs['background-color'] ?? '#4f46e5';
            $btnColor = $bs['color'] ?? '#fff';
            $btnPadding = $bs['padding'] ?? '12px 28px';
            $btnRadius = $bs['border-radius'] ?? '10px';
            $btnFontSize = $bs['font-size'] ?? '15px';
            $btnFontWeight = $bs['font-weight'] ?? '600';
            $textAlign = $ds['text-align'] ?? 'center';
        @endphp
        <div style="text-align:{{ $textAlign }}">
            <a href="{{ $content['link'] ?? '#' }}" class="lp-btn" style="display:inline-block;background:{{ $btnBg }};color:{{ $btnColor }};padding:{{ $btnPadding }};border-radius:{{ $btnRadius }};font-size:{{ $btnFontSize }};font-weight:{{ $btnFontWeight }};text-decoration:none;transition:all .2s;cursor:pointer">{{ $content['text'] ?? 'کلیک کنید' }}</a>
        </div>

    @elseif($component === 'widget-image')
        @if(!empty($content['src']))
            <div style="border-radius:{{ $ds['border-radius'] ?? '12px' }};overflow:hidden">
                <img src="{{ $content['src'] }}" alt="{{ $content['alt'] ?? '' }}" style="width:100%;display:block">
            </div>
        @else
            <div style="padding:40px;background:#f3f4f6;border-radius:12px;text-align:center">
                <i class="bi bi-image" style="font-size:28px;color:#d1d5db"></i>
            </div>
        @endif

    @elseif($component === 'widget-divider')
        <hr style="border:none;border-top:1px solid {{ $content['color'] ?? '#e5e7eb' }};margin:0">

    @elseif($component === 'widget-spacer')
        <div style="height:{{ $content['height'] ?? '48px' }}"></div>

    @elseif($component === 'widget-icon')
        @php
            $iconName = $content['name'] ?? 'bi-star';
            $iconSize = $content['size'] ?? '24px';
            $iconColor = $content['color'] ?? '#4f46e5';
            $textAlign = $ds['text-align'] ?? 'center';
        @endphp
        <div style="text-align:{{ $textAlign }}">
            <div style="width:56px;height:56px;display:inline-flex;align-items:center;justify-content:center;background:#eef2ff;border-radius:14px">
                <i class="bi {{ $iconName }}" style="font-size:{{ $iconSize }};color:{{ $iconColor }}"></i>
            </div>
        </div>

    @elseif($component === 'widget-logo')
        @php
            $mode = $content['mode'] ?? 'icon';
            $iconColor = $ds['color'] ?? '#4f46e5';
            $iconSize = $ds['font-size'] ?? '48px';
            $textAlign = $ds['text-align'] ?? 'center';
        @endphp
        <div style="text-align:{{ $textAlign }}">
            @if($mode === 'image' && !empty($content['src']))
                <img src="{{ $content['src'] }}" alt="{{ $content['alt'] ?? '' }}" style="max-width:{{ $ds['width'] ?? '100%' }};{{ !empty($ds['height']) ? 'height:'.$ds['height'].';' : '' }}object-fit:contain;display:inline-block">
            @else
                <i class="bi {{ $content['icon'] ?? 'bi-badge-ad' }}" style="font-size:{{ $iconSize }};color:{{ $iconColor }}"></i>
            @endif
        </div>

    @elseif($component === 'widget-social')
        @php
            $iconMap = ['instagram'=>'bi-instagram','telegram'=>'bi-telegram','whatsapp'=>'bi-whatsapp','twitter'=>'bi-twitter-x','linkedin'=>'bi-linkedin','youtube'=>'bi-youtube','facebook'=>'bi-facebook'];
        @endphp
        <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap">
            @foreach($content['links'] ?? [] as $link)
                <a href="{{ $link['url'] ?? '#' }}" style="width:40px;height:40px;display:inline-flex;align-items:center;justify-content:center;background:#f3f4f6;border-radius:10px;color:#6b7280;text-decoration:none;transition:all .2s" onmouseover="this.style.background='#4f46e5';this.style.color='#fff'" onmouseout="this.style.background='#f3f4f6';this.style.color='#6b7280'">
                    <i class="bi {{ $iconMap[$link['platform'] ?? 'link'] ?? 'bi-link' }}"></i>
                </a>
            @endforeach
        </div>

    @elseif($component === 'widget-stats')
        <div style="display:flex;gap:24px;flex-wrap:wrap;justify-content:center">
            @foreach($content['items'] ?? [] as $item)
                <div style="flex:1;min-width:120px;text-align:center;padding:16px">
                    <div style="font-size:36px;font-weight:800;color:#4f46e5;line-height:1">{{ $item['value'] ?? '0' }}</div>
                    <div style="font-size:13px;color:#6b7280;margin-top:6px">{{ $item['label'] ?? '' }}</div>
                </div>
            @endforeach
        </div>

    @elseif($component === 'widget-map')
        <div style="height:{{ $content['height'] ?? '300px' }};background:#e0e7ff;border-radius:12px;display:flex;align-items:center;justify-content:center">
            <i class="bi bi-geo-alt" style="font-size:36px;color:#6366f1"></i>
        </div>

    @elseif($component === 'content-list')
        <ul style="list-style:none;padding:0;margin:0">
            @foreach($content['items'] ?? [] as $item)
                <li style="padding:8px 0;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;gap:10px">
                    <span style="width:6px;height:6px;background:#4f46e5;border-radius:50%;flex-shrink:0"></span>
                    <span style="font-size:15px;color:#374151">{{ $item }}</span>
                </li>
            @endforeach
        </ul>

    @elseif($component === 'content-card')
        @php
            $cards = $content['cards'] ?? [];
            $grid = $content['grid'] ?? ['desktop' => 3, 'tablet' => 2, 'mobile' => 1];
        @endphp
        @if(empty($cards))
            <div style="padding:40px;text-align:center;color:#9ca3af;border:2px dashed #e5e7eb;border-radius:12px">
                <i class="bi bi-plus-circle" style="font-size:24px;display:block;margin-bottom:8px"></i>
                <p style="font-size:12px">کارتی اضافه نشده</p>
            </div>
        @else
            <style>.lp-cards-grid{display:grid;grid-template-columns:repeat({{ $grid['desktop'] ?? 3 }},1fr);gap:{{ $ds['gap'] ?? '24px' }};@media(max-width:991px){grid-template-columns:repeat({{ $grid['tablet'] ?? 2 }},1fr)}@media(max-width:767px){grid-template-columns:repeat({{ $grid['mobile'] ?? 1 }},1fr)}}</style>
            <div class="lp-cards-grid">
                @foreach($cards as $card)
                    <div style="background:{{ $ds['background'] ?? '#fff' }};border:{{ $ds['border'] ?? '1px solid #e5e7eb' }};border-radius:{{ $ds['border-radius'] ?? '16px' }};box-shadow:{{ $ds['box-shadow'] ?? '0 4px 6px -1px rgba(0,0,0,0.1)' }};overflow:hidden">
                        @if($card['showImage'] ?? true)
                            @if(!empty($card['image']))
                                <img src="{{ $card['image'] }}" alt="" style="width:100%;aspect-ratio:16/9;object-fit:cover;display:block">
                            @else
                                <div style="width:100%;aspect-ratio:16/9;background:#f3f4f6;display:flex;align-items:center;justify-content:center"><i class="bi bi-image" style="font-size:24px;color:#d1d5db"></i></div>
                            @endif
                        @endif
                        <div style="padding:20px 24px 24px">
                            @if(!empty($card['category']))
                                <div style="color:#6b7280;font-size:12px;text-transform:uppercase;letter-spacing:.05em;font-weight:500;margin-bottom:6px">{{ $card['category'] }}</div>
                            @endif
                            @if(!empty($card['badge']))
                                <span style="background:{{ $card['badgeColor'] ?? '#4f46e5' }};color:#fff;font-size:11px;font-weight:600;padding:4px 12px;border-radius:20px">{{ $card['badge'] }}</span>
                            @endif
                            @if(!empty($card['title']))
                                <h3 style="color:#111827;font-size:18px;font-weight:600;line-height:1.4;margin:8px 0">{{ $card['title'] }}</h3>
                            @endif
                            @if(!empty($card['description']))
                                <p style="color:#6b7280;font-size:14px;line-height:1.6;margin:0 0 16px">{{ $card['description'] }}</p>
                            @endif
                            @if(!empty($card['buttonText']))
                                <div><a href="{{ $card['buttonLink'] ?? '#' }}" style="display:inline-block;background:{{ $ds['btn-bg'] ?? '#4f46e5' }};color:{{ $ds['btn-color'] ?? '#fff' }};padding:10px 20px;border-radius:10px;font-size:14px;font-weight:600;text-decoration:none">{{ $card['buttonText'] }}</a></div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    @else
        <div style="padding:24px;background:#f9fafb;border-radius:10px;text-align:center;border:1px dashed #d1d5db">
            <i class="bi bi-puzzle" style="font-size:20px;color:#d1d5db"></i>
            <p style="font-size:11px;color:#9ca3af;margin-top:6px">{{ $component }}</p>
        </div>
    @endif

    {{-- Children (for containers) --}}
    @if(in_array($component, ['layout-section', 'layout-column', 'layout-container']) && !empty($block['children']))
        <div style="border:2px dashed #c7d2fe;border-radius:8px;padding:12px;margin:4px 0;min-height:80px">
            @foreach($block['children'] as $child)
                @include('livewire.page-builder.block', ['block' => $child, 'depth' => $depth + 1])
            @endforeach
        </div>
    @endif
</div>
