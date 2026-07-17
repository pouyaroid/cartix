@php
    $content = $block->content ?? [];
    $component = $block->component;
@endphp

<div style="margin-bottom:12px;display:flex;align-items:center;justify-content:space-between">
    <span style="font-size:10px;padding:2px 8px;background:#eef2ff;color:#4f46e5;border-radius:4px;font-weight:500">{{ $this->getBlockLabel($component) }}</span>
    <button wire:click="deleteBlock({{ $block->id }})" style="font-size:10px;padding:2px 8px;background:#fef2f2;color:#dc2626;border:none;border-radius:4px;cursor:pointer">حذف</button>
</div>

@if($component === 'widget-heading')
    <div class="style-field">
        <label class="style-label">متن</label>
        <input type="text" class="style-input" value="{{ $content['text'] ?? '' }}" wire:change="updateBlockContent({{ $block->id }}, 'text', $event.target.value)">
    </div>
    <div class="style-field">
        <label class="style-label">تگ</label>
        <select class="style-select" wire:change="updateBlockContent({{ $block->id }}, 'tag', $event.target.value)">
            @foreach(['h1','h2','h3','h4','h5','h6'] as $tag)
                <option value="{{ $tag }}" {{ ($content['tag'] ?? 'h2') === $tag ? 'selected' : '' }}>{{ strtoupper($tag) }}</option>
            @endforeach
        </select>
    </div>

@elseif($component === 'widget-text')
    <div class="style-field">
        <label class="style-label">متن</label>
        <textarea class="style-input" rows="5" wire:change="updateBlockContent({{ $block->id }}, 'text', $event.target.value)">{{ $content['text'] ?? '' }}</textarea>
    </div>

@elseif($component === 'widget-button')
    <div class="style-field">
        <label class="style-label">متن دکمه</label>
        <input type="text" class="style-input" value="{{ $content['text'] ?? '' }}" wire:change="updateBlockContent({{ $block->id }}, 'text', $event.target.value)">
    </div>
    <div class="style-field">
        <label class="style-label">لینک</label>
        <input type="text" class="style-input" value="{{ $content['link'] ?? '#' }}" wire:change="updateBlockContent({{ $block->id }}, 'link', $event.target.value)">
    </div>
    <div class="style-field">
        <label class="style-label">رنگ پس‌زمینه دکمه</label>
        <div style="display:flex;gap:8px;align-items:center">
            <input type="color" value="{{ $block->styles['button']['background-color'] ?? '#4f46e5' }}" wire:change="updateBlockStylesForMode({{ $block->id }}, 'button', array_merge(@js($block->styles['button'] ?? []), ['background-color' => $event.target.value]))" class="color-picker">
            <input type="text" class="style-input" value="{{ $block->styles['button']['background-color'] ?? '' }}" placeholder="#4f46e5" wire:change="updateBlockStylesForMode({{ $block->id }}, 'button', array_merge(@js($block->styles['button'] ?? []), ['background-color' => $event.target.value]))" style="flex:1">
        </div>
    </div>
    <div class="style-field">
        <label class="style-label">رنگ متن دکمه</label>
        <div style="display:flex;gap:8px;align-items:center">
            <input type="color" value="{{ $block->styles['button']['color'] ?? '#ffffff' }}" wire:change="updateBlockStylesForMode({{ $block->id }}, 'button', array_merge(@js($block->styles['button'] ?? []), ['color' => $event.target.value]))" class="color-picker">
            <input type="text" class="style-input" value="{{ $block->styles['button']['color'] ?? '' }}" placeholder="#ffffff" wire:change="updateBlockStylesForMode({{ $block->id }}, 'button', array_merge(@js($block->styles['button'] ?? []), ['color' => $event.target.value]))" style="flex:1">
        </div>
    </div>
    <div class="style-field">
        <label class="style-label">پدینگ دکمه</label>
        <input type="text" class="style-input" value="{{ $block->styles['button']['padding'] ?? '' }}" placeholder="12px 28px" wire:change="updateBlockStylesForMode({{ $block->id }}, 'button', array_merge(@js($block->styles['button'] ?? []), ['padding' => $event.target.value]))">
    </div>
    <div class="style-field">
        <label class="style-label">گوشه‌ها</label>
        <input type="text" class="style-input" value="{{ $block->styles['button']['border-radius'] ?? '' }}" placeholder="10px" wire:change="updateBlockStylesForMode({{ $block->id }}, 'button', array_merge(@js($block->styles['button'] ?? []), ['border-radius' => $event.target.value]))">
    </div>
    <div class="style-field">
        <label class="style-label">اندازه فونت</label>
        <input type="text" class="style-input" value="{{ $block->styles['button']['font-size'] ?? '' }}" placeholder="15px" wire:change="updateBlockStylesForMode({{ $block->id }}, 'button', array_merge(@js($block->styles['button'] ?? []), ['font-size' => $event.target.value]))">
    </div>

@elseif($component === 'widget-image')
    <div class="style-field">
        <label class="style-label">آدرس تصویر</label>
        <input type="text" class="style-input" value="{{ $content['src'] ?? '' }}" wire:change="updateBlockContent({{ $block->id }}, 'src', $event.target.value)" placeholder="https://...">
    </div>
    <div class="style-field">
        <label class="style-label">متن جایگزین</label>
        <input type="text" class="style-input" value="{{ $content['alt'] ?? '' }}" wire:change="updateBlockContent({{ $block->id }}, 'alt', $event.target.value)">
    </div>

@elseif($component === 'widget-divider')
    <div class="style-field">
        <label class="style-label">رنگ</label>
        <div style="display:flex;gap:8px;align-items:center">
            <input type="color" value="{{ $content['color'] ?? '#e5e7eb' }}" wire:change="updateBlockContent({{ $block->id }}, 'color', $event.target.value)" class="color-picker">
            <input type="text" class="style-input" value="{{ $content['color'] ?? '#e5e7eb' }}" style="flex:1">
        </div>
    </div>

@elseif($component === 'widget-spacer')
    <div class="style-field">
        <label class="style-label">ارتفاع</label>
        <input type="text" class="style-input" value="{{ $content['height'] ?? '48px' }}" wire:change="updateBlockContent({{ $block->id }}, 'height', $event.target.value)" placeholder="48px">
    </div>
    <div style="display:flex;gap:4px;margin-top:4px">
        @foreach(['16px', '24px', '32px', '48px', '64px', '80px'] as $h)
            <button wire:click="updateBlockContent({{ $block->id }}, 'height', '{{ $h }}')" style="flex:1;padding:4px;font-size:10px;background:{{ ($content['height'] ?? '48px') === $h ? '#eef2ff' : '#f9fafb' }};border:1px solid {{ ($content['height'] ?? '48px') === $h ? '#c7d2fe' : '#e5e7eb' }};border-radius:4px;cursor:pointer;color:#6b7280">{{ $h }}</button>
        @endforeach
    </div>

@elseif($component === 'widget-icon')
    <div class="style-field">
        <label class="style-label">آیکون</label>
        <input type="text" class="style-input" value="{{ $content['name'] ?? 'bi-star' }}" wire:change="updateBlockContent({{ $block->id }}, 'name', $event.target.value)" placeholder="bi-star">
    </div>
    <div class="style-field">
        <label class="style-label">اندازه</label>
        <input type="text" class="style-input" value="{{ $content['size'] ?? '24px' }}" wire:change="updateBlockContent({{ $block->id }}, 'size', $event.target.value)">
    </div>
    <div class="style-field">
        <label class="style-label">رنگ</label>
        <div style="display:flex;gap:8px;align-items:center">
            <input type="color" value="{{ $content['color'] ?? '#4f46e5' }}" wire:change="updateBlockContent({{ $block->id }}, 'color', $event.target.value)" class="color-picker">
            <input type="text" class="style-input" value="{{ $content['color'] ?? '#4f46e5' }}" style="flex:1">
        </div>
    </div>

@elseif($component === 'widget-logo')
    <div class="style-field">
        <label class="style-label">نحوه نمایش</label>
        <div style="display:flex;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden">
            <button wire:click="updateBlockContent({{ $block->id }}, 'mode', 'icon')" style="flex:1;padding:8px;text-align:center;font-size:11px;cursor:pointer;border:none;background:{{ ($content['mode'] ?? 'icon') === 'icon' ? '#4f46e5' : '#f9fafb' }};color:{{ ($content['mode'] ?? 'icon') === 'icon' ? '#fff' : '#6b7280' }}">آیکون</button>
            <button wire:click="updateBlockContent({{ $block->id }}, 'mode', 'image')" style="flex:1;padding:8px;text-align:center;font-size:11px;cursor:pointer;border:none;background:{{ ($content['mode'] ?? 'icon') === 'image' ? '#4f46e5' : '#f9fafb' }};color:{{ ($content['mode'] ?? 'icon') === 'image' ? '#fff' : '#6b7280' }}">تصویر</button>
        </div>
    </div>
    @if(($content['mode'] ?? 'icon') === 'image')
        <div class="style-field">
            <label class="style-label">آدرس تصویر</label>
            <input type="text" class="style-input" value="{{ $content['src'] ?? '' }}" wire:change="updateBlockContent({{ $block->id }}, 'src', $event.target.value)">
        </div>
    @else
        <div class="style-field">
            <label class="style-label">کلاس آیکون</label>
            <input type="text" class="style-input" value="{{ $content['icon'] ?? 'bi-badge-ad' }}" wire:change="updateBlockContent({{ $block->id }}, 'icon', $event.target.value)">
        </div>
    @endif
    <div class="style-field">
        <label class="style-label">لینک</label>
        <input type="text" class="style-input" value="{{ $content['link'] ?? '' }}" wire:change="updateBlockContent({{ $block->id }}, 'link', $event.target.value)">
    </div>

@elseif($component === 'widget-social')
    <div class="style-field">
        <label class="style-label">لینک‌های شبکه اجتماعی</label>
        @php $links = $content['links'] ?? []; @endphp
        @foreach($links as $idx => $link)
            <div style="display:flex;gap:4px;margin-bottom:4px;align-items:center">
                <select wire:change="updateArrayItemField({{ $block->id }}, 'links', {{ $idx }}, 'platform', $event.target.value)" style="width:80px;padding:5px;font-size:11px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:4px">
                    @foreach(['instagram','telegram','whatsapp','twitter','linkedin','youtube','facebook'] as $platform)
                        <option value="{{ $platform }}" {{ ($link['platform'] ?? '') === $platform ? 'selected' : '' }}>{{ $platform }}</option>
                    @endforeach
                </select>
                <input type="text" class="style-input" value="{{ $link['url'] ?? '' }}" wire:change="updateArrayItemField({{ $block->id }}, 'links', {{ $idx }}, 'url', $event.target.value)" placeholder="https://..." style="flex:1;font-size:11px">
                <button wire:click="removeArrayItem({{ $block->id }}, 'links', {{ $idx }})" style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;background:#fef2f2;color:#dc2626;border:none;border-radius:4px;cursor:pointer;font-size:10px"><i class="bi bi-x"></i></button>
            </div>
        @endforeach
        <button wire:click="addArrayItem({{ $block->id }}, 'links', @js(['platform' => 'instagram', 'url' => '#']))" style="width:100%;padding:6px;background:#f9fafb;border:1px dashed #d1d5db;border-radius:6px;cursor:pointer;font-size:11px;color:#6b7280;margin-top:4px"><i class="bi bi-plus"></i> اضافه کردن</button>
    </div>

@elseif($component === 'widget-stats')
    <div class="style-field">
        <label class="style-label">آیتم‌های آمار</label>
        @php $items = $content['items'] ?? []; @endphp
        @foreach($items as $idx => $item)
            <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;padding:8px;margin-bottom:6px">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                    <span style="font-size:10px;color:#9ca3af">آیتم {{ $idx + 1 }}</span>
                    <button wire:click="removeArrayItem({{ $block->id }}, 'items', {{ $idx }})" style="font-size:10px;color:#dc2626;background:none;border:none;cursor:pointer"><i class="bi bi-trash3"></i></button>
                </div>
                <div style="display:flex;gap:4px">
                    <input type="text" class="style-input" value="{{ $item['value'] ?? '' }}" wire:change="updateArrayItemField({{ $block->id }}, 'items', {{ $idx }}, 'value', $event.target.value)" placeholder="مقدار" style="flex:1;font-size:11px">
                    <input type="text" class="style-input" value="{{ $item['label'] ?? '' }}" wire:change="updateArrayItemField({{ $block->id }}, 'items', {{ $idx }}, 'label', $event.target.value)" placeholder="برچسب" style="flex:1;font-size:11px">
                </div>
            </div>
        @endforeach
        <button wire:click="addArrayItem({{ $block->id }}, 'items', @js(['value' => '0', 'label' => '']))" style="width:100%;padding:6px;background:#f9fafb;border:1px dashed #d1d5db;border-radius:6px;cursor:pointer;font-size:11px;color:#6b7280"><i class="bi bi-plus"></i> اضافه کردن</button>
    </div>

@elseif($component === 'widget-map')
    <div class="style-field">
        <label class="style-label">ارتفاع نقشه</label>
        <input type="text" class="style-input" value="{{ $content['height'] ?? '300px' }}" wire:change="updateBlockContent({{ $block->id }}, 'height', $event.target.value)">
    </div>

@elseif($component === 'content-list')
    <div class="style-field">
        <label class="style-label">آیتم‌های لیست</label>
        @php $items = $content['items'] ?? []; @endphp
        @foreach($items as $idx => $item)
            <div style="display:flex;gap:4px;margin-bottom:4px;align-items:center">
                <span style="width:6px;height:6px;background:#4f46e5;border-radius:50%;flex-shrink:0"></span>
                <input type="text" class="style-input" value="{{ $item }}" wire:change="updateListItem({{ $block->id }}, 'items', {{ $idx }}, $event.target.value)" style="flex:1;font-size:11px">
                <button wire:click="removeItem({{ $block->id }}, 'items', {{ $idx }})" style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;background:#fef2f2;color:#dc2626;border:none;border-radius:4px;cursor:pointer;font-size:10px"><i class="bi bi-x"></i></button>
            </div>
        @endforeach
        <button wire:click="addItem({{ $block->id }}, 'items', '')" style="width:100%;padding:6px;background:#f9fafb;border:1px dashed #d1d5db;border-radius:6px;cursor:pointer;font-size:11px;color:#6b7280"><i class="bi bi-plus"></i> اضافه کردن</button>
    </div>

@elseif($component === 'content-card')
    <div class="style-field">
        <label class="style-label">چیدمان</label>
        <select class="style-select" wire:change="updateBlockContent({{ $block->id }}, 'layout', $event.target.value)">
            @foreach(['vertical' => 'عمودی', 'horizontal' => 'افقی', 'overlay' => 'پوششی'] as $val => $lbl)
                <option value="{{ $val }}" {{ ($content['layout'] ?? 'vertical') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
            @endforeach
        </select>
    </div>
    <div class="style-field">
        <label class="style-label">تعداد ستون (دسکتاپ)</label>
        <div style="display:flex;gap:4px">
            @foreach([1,2,3,4] as $col)
                <button wire:click="updateBlockContentArray({{ $block->id }}, 'grid', @js(array_merge($content['grid'] ?? [], ['desktop' => $col])))" style="flex:1;padding:6px;font-size:11px;background:{{ ($content['grid']['desktop'] ?? 3) == $col ? '#eef2ff' : '#f9fafb' }};border:1px solid {{ ($content['grid']['desktop'] ?? 3) == $col ? '#c7d2fe' : '#e5e7eb' }};border-radius:4px;cursor:pointer">{{ $col }}</button>
            @endforeach
        </div>
    </div>
    <div class="style-field">
        <label class="style-label">کارت‌ها</label>
        @php $cards = $content['cards'] ?? []; @endphp
        @forelse($cards as $idx => $card)
            <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;padding:8px;margin-bottom:6px">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                    <span style="font-size:10px;color:#9ca3af">کارت {{ $idx + 1 }}</span>
                    <button wire:click="removeArrayItem({{ $block->id }}, 'cards', {{ $idx }})" style="font-size:10px;color:#dc2626;background:none;border:none;cursor:pointer"><i class="bi bi-trash3"></i></button>
                </div>
                <input type="text" class="style-input" value="{{ $card['title'] ?? '' }}" wire:change="updateArrayItemField({{ $block->id }}, 'cards', {{ $idx }}, 'title', $event.target.value)" placeholder="عنوان" style="font-size:11px;margin-bottom:4px">
                <input type="text" class="style-input" value="{{ $card['description'] ?? '' }}" wire:change="updateArrayItemField({{ $block->id }}, 'cards', {{ $idx }}, 'description', $event.target.value)" placeholder="توضیحات" style="font-size:11px;margin-bottom:4px">
                <input type="text" class="style-input" value="{{ $card['image'] ?? '' }}" wire:change="updateArrayItemField({{ $block->id }}, 'cards', {{ $idx }}, 'image', $event.target.value)" placeholder="آدرس تصویر" style="font-size:11px;margin-bottom:4px">
                <div style="display:flex;gap:4px">
                    <input type="text" class="style-input" value="{{ $card['buttonText'] ?? '' }}" wire:change="updateArrayItemField({{ $block->id }}, 'cards', {{ $idx }}, 'buttonText', $event.target.value)" placeholder="متن دکمه" style="flex:1;font-size:11px">
                    <input type="text" class="style-input" value="{{ $card['buttonLink'] ?? '' }}" wire:change="updateArrayItemField({{ $block->id }}, 'cards', {{ $idx }}, 'buttonLink', $event.target.value)" placeholder="لینک" style="flex:1;font-size:11px">
                </div>
            </div>
        @empty
            <p style="font-size:11px;color:#9ca3af;text-align:center;padding:12px">هنوز کارتی اضافه نشده</p>
        @endforelse
        <button wire:click="addArrayItem({{ $block->id }}, 'cards', @js(['title' => '', 'description' => '', 'image' => '', 'buttonText' => '', 'buttonLink' => '#', 'showImage' => true, 'showTitle' => true, 'showDescription' => true, 'showButton' => true]))" style="width:100%;padding:6px;background:#f9fafb;border:1px dashed #d1d5db;border-radius:6px;cursor:pointer;font-size:11px;color:#6b7280"><i class="bi bi-plus"></i> اضافه کردن کارت</button>
    </div>

@else
    <div style="text-align:center;padding:24px;color:#9ca3af">
        <p style="font-size:12px">ویرایش محتوا برای {{ $component }} در دسترس نیست</p>
    </div>
@endif
