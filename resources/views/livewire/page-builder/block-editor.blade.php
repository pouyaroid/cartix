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
    {{-- Image Picker --}}
    <div class="style-field">
        @if(!empty($content['src']))
            <div style="position:relative;border-radius:6px;overflow:hidden;border:1px solid #e5e7eb;margin-bottom:6px">
                <img src="{{ $content['src'] }}" style="width:100%;height:100px;object-fit:cover;display:block">
                <div style="position:absolute;top:4px;right:4px;display:flex;gap:4px">
                    <button wire:click="openMediaPickerFor('widget-image', {{ $block->id }})" style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.9);border:none;border-radius:4px;cursor:pointer;font-size:10px;color:#4f46e5"><i class="bi bi-pencil"></i></button>
                    <button wire:click="updateBlockContent({{ $block->id }}, 'src', '')" style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.9);border:none;border-radius:4px;cursor:pointer;font-size:10px;color:#dc2626"><i class="bi bi-trash3"></i></button>
                </div>
            </div>
        @endif
        <button wire:click="openMediaPickerFor('widget-image', {{ $block->id }})" style="width:100%;padding:8px;display:flex;align-items:center;justify-content:center;gap:6px;background:#f9fafb;border:1px dashed #d1d5db;border-radius:6px;cursor:pointer;font-size:12px;color:#6b7280">
            <i class="bi bi-image" style="font-size:14px"></i>
            {{ !empty($content['src']) ? 'تغییر تصویر' : 'انتخاب تصویر' }}
        </button>
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
    @php
        $currentIcon = $content['name'] ?? 'bi-star';
    @endphp

    {{-- Selected Icon Preview --}}
    <div style="display:flex;align-items:center;gap:12px;padding:12px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;margin-bottom:12px">
        <div style="width:48px;height:48px;display:flex;align-items:center;justify-content:center;background:#eef2ff;border-radius:10px;flex-shrink:0">
            <i class="bi {{ $currentIcon }}" style="font-size:24px;color:{{ $content['color'] ?? '#4f46e5' }}"></i>
        </div>
        <div style="flex:1;min-width:0">
            <div style="font-size:11px;color:#9ca3af;margin-bottom:2px">آیکون انتخاب شده</div>
            <div style="font-size:12px;color:#374151;font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $currentIcon }}</div>
        </div>
        <button wire:click="toggleFavoriteIcon('{{ $currentIcon }}')" style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;background:transparent;border:none;border-radius:4px;cursor:pointer;font-size:14px;color:{{ in_array($currentIcon, $favoriteIcons) ? '#f59e0b' : '#d1d5db' }}">
            <i class="bi bi-{{ in_array($currentIcon, $favoriteIcons) ? 'star-fill' : 'star' }}"></i>
        </button>
    </div>

    {{-- Search --}}
    <div class="style-field">
        <div style="position:relative">
            <i class="bi bi-search" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);font-size:12px;color:#9ca3af"></i>
            <input type="text" class="style-input" placeholder="جستجوی آیکون..." wire:model.live.debounce.200ms="iconSearch" style="padding-right:30px">
        </div>
    </div>

    {{-- Categories --}}
    <div class="style-field">
        <div style="display:flex;gap:4px;flex-wrap:wrap">
            <button wire:click="$set('iconCategory', 'all')" style="padding:4px 8px;font-size:10px;border:1px solid {{ $iconCategory === 'all' ? '#c7d2fe' : '#e5e7eb' }};border-radius:12px;cursor:pointer;background:{{ $iconCategory === 'all' ? '#eef2ff' : '#f9fafb' }};color:{{ $iconCategory === 'all' ? '#4f46e5' : '#6b7280' }}">همه</button>
            @foreach($this->getIconCategories() as $catKey => $cat)
                <button wire:click="$set('iconCategory', '{{ $catKey }}')" style="padding:4px 8px;font-size:10px;border:1px solid {{ $iconCategory === $catKey ? '#c7d2fe' : '#e5e7eb' }};border-radius:12px;cursor:pointer;background:{{ $iconCategory === $catKey ? '#eef2ff' : '#f9fafb' }};color:{{ $iconCategory === $catKey ? '#4f46e5' : '#6b7280' }}">{{ $cat['label'] }}</button>
            @endforeach
        </div>
    </div>

    {{-- Recently Used --}}
    @if(!empty($recentIcons) && empty($iconSearch) && $iconCategory === 'all')
        <div class="style-field">
            <div style="font-size:10px;font-weight:600;color:#9ca3af;margin-bottom:6px">اخیراً استفاده شده</div>
            <div style="display:grid;grid-template-columns:repeat(6,1fr);gap:4px">
                @foreach($recentIcons as $icon)
                    <button wire:click="selectIcon({{ $block->id }}, '{{ $icon }}')" style="width:100%;aspect-ratio:1;display:flex;align-items:center;justify-content:center;background:{{ $currentIcon === $icon ? '#eef2ff' : '#f9fafb' }};border:1px solid {{ $currentIcon === $icon ? '#c7d2fe' : '#e5e7eb' }};border-radius:6px;cursor:pointer;color:{{ $currentIcon === $icon ? '#4f46e5' : '#6b7280' }};font-size:16px" title="{{ $icon }}">
                        <i class="bi {{ $icon }}"></i>
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Favorites --}}
    @if(!empty($favoriteIcons) && empty($iconSearch) && $iconCategory === 'all')
        <div class="style-field">
            <div style="font-size:10px;font-weight:600;color:#9ca3af;margin-bottom:6px">علاقه‌مندی‌ها</div>
            <div style="display:grid;grid-template-columns:repeat(6,1fr);gap:4px">
                @foreach($favoriteIcons as $icon)
                    <button wire:click="selectIcon({{ $block->id }}, '{{ $icon }}')" style="width:100%;aspect-ratio:1;display:flex;align-items:center;justify-content:center;background:{{ $currentIcon === $icon ? '#eef2ff' : '#f9fafb' }};border:1px solid {{ $currentIcon === $icon ? '#c7d2fe' : '#e5e7eb' }};border-radius:6px;cursor:pointer;color:{{ $currentIcon === $icon ? '#4f46e5' : '#6b7280' }};font-size:16px" title="{{ $icon }}">
                        <i class="bi {{ $icon }}"></i>
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Icon Grid --}}
    <div class="style-field">
        <div style="font-size:10px;font-weight:600;color:#9ca3af;margin-bottom:6px">
            @if(!empty($iconSearch))
                نتایج جستجو
            @elseif($iconCategory !== 'all')
                {{ $this->getIconCategories()[$iconCategory]['label'] ?? '' }}
            @else
                همه آیکون‌ها
            @endif
        </div>
        <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:4px;max-height:300px;overflow-y:auto;padding:2px">
            @php $icons = $this->getFilteredIcons(); @endphp
            @forelse($icons as $iconClass => $iconMeta)
                <button wire:click="selectIcon({{ $block->id }}, '{{ $iconClass }}')" style="width:100%;aspect-ratio:1;display:flex;flex-direction:column;align-items:center;justify-content:center;background:{{ $currentIcon === $iconClass ? '#eef2ff' : '#f9fafb' }};border:1px solid {{ $currentIcon === $iconClass ? '#c7d2fe' : '#e5e7eb' }};border-radius:6px;cursor:pointer;color:{{ $currentIcon === $iconClass ? '#4f46e5' : '#6b7280' }};font-size:16px;gap:2px;padding:4px" title="{{ $iconMeta['name'] }}">
                    <i class="bi {{ $iconClass }}"></i>
                </button>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:20px;color:#9ca3af">
                    <i class="bi bi-search" style="font-size:20px;display:block;margin-bottom:6px"></i>
                    <p style="font-size:11px">آیکونی یافت نشد</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Size --}}
    <div style="border-top:1px solid #e5e7eb;margin:12px 0;padding-top:12px">
        <div class="style-field">
            <label class="style-label">اندازه</label>
            <input type="text" class="style-input" value="{{ $content['size'] ?? '24px' }}" wire:change="updateBlockContent({{ $block->id }}, 'size', $event.target.value)" placeholder="24px">
        </div>
        <div style="display:flex;gap:4px;margin-top:4px">
            @foreach(['16px', '20px', '24px', '32px', '40px', '48px', '64px'] as $s)
                <button wire:click="updateBlockContent({{ $block->id }}, 'size', '{{ $s }}')" style="flex:1;padding:4px;font-size:10px;background:{{ ($content['size'] ?? '24px') === $s ? '#eef2ff' : '#f9fafb' }};border:1px solid {{ ($content['size'] ?? '24px') === $s ? '#c7d2fe' : '#e5e7eb' }};border-radius:4px;cursor:pointer;color:#6b7280">{{ $s }}</button>
            @endforeach
        </div>
    </div>

    {{-- Color --}}
    <div class="style-field">
        <label class="style-label">رنگ آیکون</label>
        <div style="display:flex;gap:8px;align-items:center">
            <input type="color" value="{{ $content['color'] ?? '#4f46e5' }}" wire:change="updateBlockContent({{ $block->id }}, 'color', $event.target.value)" class="color-picker">
            <input type="text" class="style-input" value="{{ $content['color'] ?? '#4f46e5' }}" style="flex:1">
        </div>
    </div>

    {{-- Background --}}
    <div class="style-field">
        <label class="style-label">پس‌زمینه آیکون</label>
        <div style="display:flex;gap:8px;align-items:center">
            <input type="color" value="{{ $ds['icon-bg'] ?? '#eef2ff' }}" wire:change="updateBlockStyle({{ $block->id }}, 'icon-bg', $event.target.value)" class="color-picker">
            <input type="text" class="style-input" value="{{ $ds['icon-bg'] ?? '#eef2ff' }}" style="flex:1">
        </div>
    </div>

    {{-- Border Radius --}}
    <div class="style-field">
        <label class="style-label">گوشه‌های پس‌زمینه</label>
        <input type="text" class="style-input" value="{{ $ds['icon-radius'] ?? '14px' }}" wire:change="updateBlockStyle({{ $block->id }}, 'icon-radius', $event.target.value)" placeholder="14px">
    </div>

    {{-- Opacity --}}
    <div class="style-field">
        <label class="style-label">شفافیت</label>
        <input type="range" min="0" max="1" step="0.05" value="{{ $ds['opacity'] ?? '1' }}" wire:change="updateBlockStyle({{ $block->id }}, 'opacity', $event.target.value)" style="width:100%">
    </div>

    {{-- Hover State --}}
    <div style="border-top:1px solid #e5e7eb;margin:12px 0;padding-top:12px">
        <div style="font-size:10px;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px">حالت Hover</div>
    </div>
    <div class="style-field">
        <label class="style-label">رنگ آیکون</label>
        <div style="display:flex;gap:8px;align-items:center">
            <input type="color" value="{{ ($block->styles['hover']['color'] ?? '') }}" wire:change="updateBlockStylesForMode({{ $block->id }}, 'hover', array_merge(@js($block->styles['hover'] ?? []), ['color' => $event.target.value]))" class="color-picker">
            <input type="text" class="style-input" value="{{ $block->styles['hover']['color'] ?? '' }}" placeholder="#ffffff" style="flex:1">
        </div>
    </div>
    <div class="style-field">
        <label class="style-label">رنگ پس‌زمینه</label>
        <div style="display:flex;gap:8px;align-items:center">
            <input type="color" value="{{ ($block->styles['hover']['icon-bg'] ?? '') }}" wire:change="updateBlockStylesForMode({{ $block->id }}, 'hover', array_merge(@js($block->styles['hover'] ?? []), ['icon-bg' => $event.target.value]))" class="color-picker">
            <input type="text" class="style-input" value="{{ $block->styles['hover']['icon-bg'] ?? '' }}" placeholder="#4f46e5" style="flex:1">
        </div>
    </div>
    <div class="style-field">
        <label class="style-label">مقیاس (Scale)</label>
        <input type="range" min="0.5" max="1.5" step="0.05" value="{{ $block->styles['hover']['icon-scale'] ?? '1' }}" wire:change="updateBlockStylesForMode({{ $block->id }}, 'hover', array_merge(@js($block->styles['hover'] ?? []), ['icon-scale' => $event.target.value]))" style="width:100%">
    </div>
    <div class="style-field">
        <label class="style-label">شفافیت</label>
        <input type="range" min="0" max="1" step="0.05" value="{{ $block->styles['hover']['opacity'] ?? '1' }}" wire:change="updateBlockStylesForMode({{ $block->id }}, 'hover', array_merge(@js($block->styles['hover'] ?? []), ['opacity' => $event.target.value]))" style="width:100%">
    </div>
    <div class="style-field">
        <label class="style-label">سرعت انتقال</label>
        <select class="style-select" wire:change="updateBlockStylesForMode({{ $block->id }}, 'hover', array_merge(@js($block->styles['hover'] ?? []), ['transition' => $event.target.value]))">
            @foreach(['all 0.1s ease' => 'خیلی سریع', 'all 0.2s ease' => 'سریع', 'all 0.3s ease' => 'نرمال', 'all 0.5s ease' => 'کند', 'all 0.8s ease' => 'خیلی کند'] as $val => $lbl)
                <option value="{{ $val }}" {{ ($block->styles['hover']['transition'] ?? 'all 0.2s ease') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
            @endforeach
        </select>
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
            @if(!empty($content['src']))
                <div style="position:relative;border-radius:6px;overflow:hidden;border:1px solid #e5e7eb;margin-bottom:6px">
                    <img src="{{ $content['src'] }}" style="width:100%;height:80px;object-fit:contain;display:block;background:#f9fafb">
                    <div style="position:absolute;top:4px;right:4px;display:flex;gap:4px">
                        <button wire:click="openMediaPickerFor('widget-logo', {{ $block->id }})" style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.9);border:none;border-radius:4px;cursor:pointer;font-size:10px;color:#4f46e5"><i class="bi bi-pencil"></i></button>
                        <button wire:click="updateBlockContent({{ $block->id }}, 'src', '')" style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.9);border:none;border-radius:4px;cursor:pointer;font-size:10px;color:#dc2626"><i class="bi bi-trash3"></i></button>
                    </div>
                </div>
            @endif
            <button wire:click="openMediaPickerFor('widget-logo', {{ $block->id }})" style="width:100%;padding:8px;display:flex;align-items:center;justify-content:center;gap:6px;background:#f9fafb;border:1px dashed #d1d5db;border-radius:6px;cursor:pointer;font-size:12px;color:#6b7280">
                <i class="bi bi-image" style="font-size:14px"></i>
                {{ !empty($content['src']) ? 'تغییر تصویر' : 'انتخاب تصویر' }}
            </button>
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
    @php
        $mapProvider = \App\Services\Maps\MapProviderFactory::make($content['provider'] ?? 'google');
        $providers = \App\Services\Maps\MapProviderFactory::getChoices();
    @endphp

    {{-- Provider --}}
    <div class="style-field">
        <label class="style-label">ارائه‌دهنده نقشه</label>
        <select class="style-select" wire:change="updateBlockContent({{ $block->id }}, 'provider', $event.target.value)">
            @foreach($providers as $key => $name)
                <option value="{{ $key }}" {{ ($content['provider'] ?? 'google') === $key ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Source Type --}}
    <div class="style-field">
        <label class="style-label">نحوه تعیین موقعیت</label>
        <div style="display:flex;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden">
            <button wire:click="updateBlockContent({{ $block->id }}, '_sourceType', 'coords')" style="flex:1;padding:8px;text-align:center;font-size:11px;cursor:pointer;border:none;background:{{ ($content['_sourceType'] ?? 'coords') === 'coords' ? '#4f46e5' : '#f9fafb' }};color:{{ ($content['_sourceType'] ?? 'coords') === 'coords' ? '#fff' : '#6b7280' }}>مختصات</button>
            <button wire:click="updateBlockContent({{ $block->id }}, '_sourceType', 'address')" style="flex:1;padding:8px;text-align:center;font-size:11px;cursor:pointer;border:none;background:{{ ($content['_sourceType'] ?? 'coords') === 'address' ? '#4f46e5' : '#f9fafb' }};color:{{ ($content['_sourceType'] ?? 'coords') === 'address' ? '#fff' : '#6b7280' }}>آدرس</button>
            <button wire:click="updateBlockContent({{ $block->id }}, '_sourceType', 'embed')" style="flex:1;padding:8px;text-align:center;font-size:11px;cursor:pointer;border:none;background:{{ ($content['_sourceType'] ?? 'coords') === 'embed' ? '#4f46e5' : '#f9fafb' }};color:{{ ($content['_sourceType'] ?? 'coords') === 'embed' ? '#fff' : '#6b7280' }}>لینک Embed</button>
        </div>
    </div>

    @if(($content['_sourceType'] ?? 'coords') === 'coords')
        <div class="style-field">
            <label class="style-label">عرض جغرافیایی (Latitude)</label>
            <input type="text" class="style-input" value="{{ $content['lat'] ?? '35.699739' }}" wire:change="updateBlockContent({{ $block->id }}, 'lat', $event.target.value)" placeholder="35.699739">
        </div>
        <div class="style-field">
            <label class="style-label">طول جغرافیایی (Longitude)</label>
            <input type="text" class="style-input" value="{{ $content['lng'] ?? '51.338097' }}" wire:change="updateBlockContent({{ $block->id }}, 'lng', $event.target.value)" placeholder="51.338097">
        </div>
    @elseif(($content['_sourceType'] ?? 'coords') === 'address')
        <div class="style-field">
            <label class="style-label">آدرس</label>
            <input type="text" class="style-input" value="{{ $content['address'] ?? '' }}" wire:change="updateBlockContent({{ $block->id }}, 'address', $event.target.value)" placeholder="تهران، میدان آزادی">
        </div>
        <p style="font-size:10px;color:#9ca3af;margin-top:2px">مختصات به صورت خودکار از آدرس استخراج می‌شوند</p>
    @else
        <div class="style-field">
            <label class="style-label">لینک Embed</label>
            <input type="text" class="style-input" value="{{ $content['embedUrl'] ?? '' }}" wire:change="updateBlockContent({{ $block->id }}, 'embedUrl', $event.target.value)" placeholder="https://...">
        </div>
    @endif

    {{-- Zoom --}}
    <div class="style-field">
        <label class="style-label">بزرگنمایی (Zoom: {{ $content['zoom'] ?? 12 }})</label>
        <input type="range" min="{{ $mapProvider->getMinZoom() }}" max="{{ $mapProvider->getMaxZoom() }}" value="{{ $content['zoom'] ?? 12 }}" wire:change="updateBlockContent({{ $block->id }}, 'zoom', $event.target.value)" style="width:100%">
    </div>

    {{-- Map Type --}}
    @if(count($mapProvider->getSupportedMapTypes()) > 1)
        <div class="style-field">
            <label class="style-label">نوع نقشه</label>
            <select class="style-select" wire:change="updateBlockContent({{ $block->id }}, 'mapType', $event.target.value)">
                @foreach($mapProvider->getSupportedMapTypes() as $typeKey => $typeName)
                    <option value="{{ $typeKey }}" {{ ($content['mapType'] ?? 'roadmap') === $typeKey ? 'selected' : '' }}>{{ $typeName }}</option>
                @endforeach
            </select>
        </div>
    @endif

    {{-- Marker --}}
    <div style="border-top:1px solid #e5e7eb;margin:12px 0;padding-top:12px">
        <div style="font-size:10px;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px">نشانگر</div>
    </div>
    <div class="style-field">
        <label style="display:flex;align-items:center;gap:6px;font-size:11px;color:#374151;cursor:pointer">
            <input type="checkbox" {{ ($content['showMarker'] ?? true) ? 'checked' : '' }} wire:change="updateBlockContent({{ $block->id }}, 'showMarker', $event.target.checked)" style="width:14px;height:14px;accent-color:#4f46e5">
            نمایش نشانگر
        </label>
    </div>
    @if($content['showMarker'] ?? true)
        <div class="style-field">
            <label class="style-label">عنوان نشانگر</label>
            <input type="text" class="style-input" value="{{ $content['markerTitle'] ?? '' }}" wire:change="updateBlockContent({{ $block->id }}, 'markerTitle', $event.target.value)" placeholder="دفتر مرکزی">
        </div>
        <div class="style-field">
            <label class="style-label">توضیحات نشانگر</label>
            <input type="text" class="style-input" value="{{ $content['markerDescription'] ?? '' }}" wire:change="updateBlockContent({{ $block->id }}, 'markerDescription', $event.target.value)" placeholder="تهران، خیابان ولیعصر">
        </div>
    @endif

    {{-- Controls --}}
    <div style="border-top:1px solid #e5e7eb;margin:12px 0;padding-top:12px">
        <div style="font-size:10px;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px">کنترل‌ها</div>
    </div>
    @if($mapProvider->supportsFullscreen())
        <div class="style-field">
            <label style="display:flex;align-items:center;gap:6px;font-size:11px;color:#374151;cursor:pointer">
                <input type="checkbox" {{ ($content['fullscreen'] ?? false) ? 'checked' : '' }} wire:change="updateBlockContent({{ $block->id }}, 'fullscreen', $event.target.checked)" style="width:14px;height:14px;accent-color:#4f46e5">
                دکمه تمام صفحه
            </label>
        </div>
    @endif
    <div class="style-field">
        <label style="display:flex;align-items:center;gap:6px;font-size:11px;color:#374151;cursor:pointer">
            <input type="checkbox" {{ ($content['zoomControl'] ?? true) ? 'checked' : '' }} wire:change="updateBlockContent({{ $block->id }}, 'zoomControl', $event.target.checked)" style="width:14px;height:14px;accent-color:#4f46e5">
            دکمه زوم
        </label>
    </div>
    @if($mapProvider->supportsStreetView())
        <div class="style-field">
            <label style="display:flex;align-items:center;gap:6px;font-size:11px;color:#374151;cursor:pointer">
                <input type="checkbox" {{ ($content['streetView'] ?? false) ? 'checked' : '' }} wire:change="updateBlockContent({{ $block->id }}, 'streetView', $event.target.checked)" style="width:14px;height:14px;accent-color:#4f46e5">
                نمای خیابان
            </label>
        </div>
    @endif
    <div class="style-field">
        <label style="display:flex;align-items:center;gap:6px;font-size:11px;color:#374151;cursor:pointer">
            <input type="checkbox" {{ ($content['scrollWheel'] ?? true) ? 'checked' : '' }} wire:change="updateBlockContent({{ $block->id }}, 'scrollWheel', $event.target.checked)" style="width:14px;height:14px;accent-color:#4f46e5">
            زوم با اسکرول
        </label>
    </div>
    <div class="style-field">
        <label style="display:flex;align-items:center;gap:6px;font-size:11px;color:#374151;cursor:pointer">
            <input type="checkbox" {{ ($content['draggable'] ?? true) ? 'checked' : '' }} wire:change="updateBlockContent({{ $block->id }}, 'draggable', $event.target.checked)" style="width:14px;height:14px;accent-color:#4f46e5">
            جابجایی با درگ
        </label>
    </div>
    <div class="style-field">
        <label style="display:flex;align-items:center;gap:6px;font-size:11px;color:#374151;cursor:pointer">
            <input type="checkbox" {{ ($content['doubleClickZoom'] ?? true) ? 'checked' : '' }} wire:change="updateBlockContent({{ $block->id }}, 'doubleClickZoom', $event.target.checked)" style="width:14px;height:14px;accent-color:#4f46e5">
            زوم با دبل‌کلیک
        </label>
    </div>

    {{-- Height --}}
    <div style="border-top:1px solid #e5e7eb;margin:12px 0;padding-top:12px">
        <div class="style-field">
            <label class="style-label">ارتفاع نقشه</label>
            <input type="text" class="style-input" value="{{ $content['height'] ?? '400px' }}" wire:change="updateBlockContent({{ $block->id }}, 'height', $event.target.value)" placeholder="400px">
        </div>
        <div style="display:flex;gap:4px;margin-top:4px">
            @foreach(['250px', '300px', '400px', '500px', '600px'] as $h)
                <button wire:click="updateBlockContent({{ $block->id }}, 'height', '{{ $h }}')" style="flex:1;padding:4px;font-size:10px;background:{{ ($content['height'] ?? '400px') === $h ? '#eef2ff' : '#f9fafb' }};border:1px solid {{ ($content['height'] ?? '400px') === $h ? '#c7d2fe' : '#e5e7eb' }};border-radius:4px;cursor:pointer;color:#6b7280">{{ $h }}</button>
            @endforeach
        </div>
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

                {{-- Image Picker --}}
                <div style="margin-bottom:4px">
                    @if(!empty($card['image']))
                        <div style="position:relative;border-radius:6px;overflow:hidden;border:1px solid #e5e7eb;margin-bottom:4px">
                            <img src="{{ $card['image'] }}" style="width:100%;height:80px;object-fit:cover;display:block">
                            <div style="position:absolute;top:4px;right:4px;display:flex;gap:4px">
                                <button wire:click="openMediaPickerFor('content-card', {{ $block->id }}, 'cards', '{{ $idx }}', 'image')" style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.9);border:none;border-radius:4px;cursor:pointer;font-size:10px;color:#4f46e5"><i class="bi bi-pencil"></i></button>
                                <button wire:click="updateArrayItemField({{ $block->id }}, 'cards', {{ $idx }}, 'image', '')" style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.9);border:none;border-radius:4px;cursor:pointer;font-size:10px;color:#dc2626"><i class="bi bi-trash3"></i></button>
                            </div>
                        </div>
                    @endif
                    <button wire:click="openMediaPickerFor('content-card', {{ $block->id }}, 'cards', '{{ $idx }}', 'image')" style="width:100%;padding:6px;display:flex;align-items:center;justify-content:center;gap:4px;background:#f9fafb;border:1px dashed #d1d5db;border-radius:6px;cursor:pointer;font-size:11px;color:#6b7280">
                        <i class="bi bi-image"></i>
                        {{ $card['image'] ? 'تغییر تصویر' : 'انتخاب تصویر' }}
                    </button>
                </div>
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
