@php
    $styles = $block->styles ?? ['desktop' => [], 'tablet' => [], 'mobile' => []];
    $currentMode = $responsiveMode ?? 'desktop';
    $ds = $styles[$currentMode] ?? [];
    $component = $block->component;
@endphp

<div style="margin-bottom:12px;display:flex;align-items:center;justify-content:space-between">
    <span style="font-size:10px;padding:2px 8px;background:#eef2ff;color:#4f46e5;border-radius:4px;font-weight:500">استایل — {{ $this->getBlockLabel($component) }}</span>
    <span style="font-size:9px;padding:2px 6px;background:#f3f4f6;color:#6b7280;border-radius:4px">{{ $currentMode === 'desktop' ? 'دسکتاپ' : ($currentMode === 'tablet' ? 'تبلت' : 'موبایل') }}</span>
</div>

{{-- Alignment --}}
<div style="margin-bottom:16px">
    <div style="font-size:10px;font-weight:600;color:#9ca3af;margin-bottom:6px">تراز</div>
    <div style="display:flex;gap:4px">
        <button wire:click="updateBlockStyle({{ $block->id }}, 'text-align', 'left')" style="flex:1;padding:8px;display:flex;align-items:center;justify-content:center;background:{{ ($ds['text-align'] ?? '') === 'left' ? '#eef2ff' : '#f9fafb' }};border:1px solid {{ ($ds['text-align'] ?? '') === 'left' ? '#c7d2fe' : '#e5e7eb' }};border-radius:6px;cursor:pointer;color:{{ ($ds['text-align'] ?? '') === 'left' ? '#4f46e5' : '#6b7280' }};font-size:16px"><i class="bi bi-text-left"></i></button>
        <button wire:click="updateBlockStyle({{ $block->id }}, 'text-align', 'center')" style="flex:1;padding:8px;display:flex;align-items:center;justify-content:center;background:{{ ($ds['text-align'] ?? '') === 'center' ? '#eef2ff' : '#f9fafb' }};border:1px solid {{ ($ds['text-align'] ?? '') === 'center' ? '#c7d2fe' : '#e5e7eb' }};border-radius:6px;cursor:pointer;color:{{ ($ds['text-align'] ?? '') === 'center' ? '#4f46e5' : '#6b7280' }};font-size:16px"><i class="bi bi-text-center"></i></button>
        <button wire:click="updateBlockStyle({{ $block->id }}, 'text-align', 'right')" style="flex:1;padding:8px;display:flex;align-items:center;justify-content:center;background:{{ ($ds['text-align'] ?? '') === 'right' ? '#eef2ff' : '#f9fafb' }};border:1px solid {{ ($ds['text-align'] ?? '') === 'right' ? '#c7d2fe' : '#e5e7eb' }};border-radius:6px;cursor:pointer;color:{{ ($ds['text-align'] ?? '') === 'right' ? '#4f46e5' : '#6b7280' }};font-size:16px"><i class="bi bi-text-right"></i></button>
    </div>
</div>

{{-- Padding --}}
<div class="style-field">
    <label class="style-label">پدینگ</label>
    <input type="text" class="style-input" value="{{ $ds['padding'] ?? '' }}" placeholder="24px" wire:change="updateBlockStyle({{ $block->id }}, 'padding', $event.target.value)">
</div>

{{-- Margin --}}
<div class="style-field">
    <label class="style-label">حاشیه</label>
    <input type="text" class="style-input" value="{{ $ds['margin'] ?? '' }}" placeholder="0 auto" wire:change="updateBlockStyle({{ $block->id }}, 'margin', $event.target.value)">
</div>

{{-- Background --}}
<div class="style-field">
    <label class="style-label">پس‌زمینه</label>
    <div style="display:flex;gap:8px;align-items:center">
        <input type="color" class="color-picker" value="{{ $ds['background-color'] ?? '#ffffff' }}" wire:change="updateBlockStyle({{ $block->id }}, 'background-color', $event.target.value)">
        <input type="text" class="style-input" value="{{ $ds['background-color'] ?? '' }}" placeholder="#ffffff" wire:change="updateBlockStyle({{ $block->id }}, 'background-color', $event.target.value)" style="flex:1">
    </div>
</div>

{{-- Border Radius --}}
<div class="style-field">
    <label class="style-label">گوشه‌ها</label>
    <input type="text" class="style-input" value="{{ $ds['border-radius'] ?? '' }}" placeholder="12px" wire:change="updateBlockStyle({{ $block->id }}, 'border-radius', $event.target.value)">
</div>

{{-- Box Shadow --}}
<div class="style-field">
    <label class="style-label">سایه</label>
    <input type="text" class="style-input" value="{{ $ds['box-shadow'] ?? '' }}" placeholder="0 4px 6px rgba(0,0,0,0.1)" wire:change="updateBlockStyle({{ $block->id }}, 'box-shadow', $event.target.value)">
</div>

{{-- Text Color (for text/heading widgets) --}}
@if(in_array($component, ['widget-text', 'widget-heading']))
<div class="style-field">
    <label class="style-label">رنگ متن</label>
    <div style="display:flex;gap:8px;align-items:center">
        <input type="color" class="color-picker" value="{{ $ds['color'] ?? '#111827' }}" wire:change="updateBlockStyle({{ $block->id }}, 'color', $event.target.value)">
        <input type="text" class="style-input" value="{{ $ds['color'] ?? '' }}" placeholder="#111827" wire:change="updateBlockStyle({{ $block->id }}, 'color', $event.target.value)" style="flex:1">
    </div>
</div>
@endif

{{-- Font Size --}}
<div class="style-field">
    <label class="style-label">اندازه فونت</label>
    <input type="text" class="style-input" value="{{ $ds['font-size'] ?? '' }}" placeholder="16px" wire:change="updateBlockStyle({{ $block->id }}, 'font-size', $event.target.value)">
</div>

{{-- Font Family --}}
<div class="style-field">
    <label class="style-label">فونت</label>
    <select class="style-select" wire:change="updateBlockStyle({{ $block->id }}, 'font-family', $event.target.value)">
        <option value="" {{ empty($ds['font-family']) ? 'selected' : '' }}>پیش‌فرض</option>
        <option value="Vazirmatn" {{ ($ds['font-family'] ?? '') === 'Vazirmatn' ? 'selected' : '' }}>Vazirmatn</option>
        <option value="IRANSans" {{ ($ds['font-family'] ?? '') === 'IRANSans' ? 'selected' : '' }}>IRANSans</option>
        <option value="Tahoma" {{ ($ds['font-family'] ?? '') === 'Tahoma' ? 'selected' : '' }}>Tahoma</option>
    </select>
</div>

{{-- Line Height --}}
<div class="style-field">
    <label class="style-label">ارتفاع خط</label>
    <input type="text" class="style-input" value="{{ $ds['line-height'] ?? '' }}" placeholder="1.5" wire:change="updateBlockStyle({{ $block->id }}, 'line-height', $event.target.value)">
</div>

{{-- Width --}}
<div class="style-field">
    <label class="style-label">عرض</label>
    <input type="text" class="style-input" value="{{ $ds['width'] ?? '' }}" placeholder="100%" wire:change="updateBlockStyle({{ $block->id }}, 'width', $event.target.value)">
</div>

{{-- Max Width --}}
<div class="style-field">
    <label class="style-label">حداکثر عرض</label>
    <input type="text" class="style-input" value="{{ $ds['max-width'] ?? '' }}" placeholder="1200px" wire:change="updateBlockStyle({{ $block->id }}, 'max-width', $event.target.value)">
</div>

{{-- Border --}}
<div class="style-field">
    <label class="style-label">حاشیه</label>
    <input type="text" class="style-input" value="{{ $ds['border'] ?? '' }}" placeholder="1px solid #e5e7eb" wire:change="updateBlockStyle({{ $block->id }}, 'border', $event.target.value)">
</div>

{{-- Border Color --}}
<div class="style-field">
    <label class="style-label">رنگ حاشیه</label>
    <div style="display:flex;gap:8px;align-items:center">
        <input type="color" class="color-picker" value="{{ $ds['border-color'] ?? '#e5e7eb' }}" wire:change="updateBlockStyle({{ $block->id }}, 'border-color', $event.target.value)">
        <input type="text" class="style-input" value="{{ $ds['border-color'] ?? '' }}" placeholder="#e5e7eb" wire:change="updateBlockStyle({{ $block->id }}, 'border-color', $event.target.value)" style="flex:1">
    </div>
</div>

{{-- Opacity --}}
<div class="style-field">
    <label class="style-label">شفافیت</label>
    <input type="range" min="0" max="1" step="0.05" value="{{ $ds['opacity'] ?? '1' }}" wire:change="updateBlockStyle({{ $block->id }}, 'opacity', $event.target.value)" style="width:100%">
</div>

@if($currentMode !== 'desktop')
<div style="margin-top:16px;padding:10px;background:#fef3c7;border-radius:6px;font-size:10px;color:#92400e">
    <i class="bi bi-info-circle"></i> تنظیمات استایل برای {{ $currentMode === 'tablet' ? 'تبلت' : 'موبایل' }} فقط روی این صفحه‌نمای اعمال می‌شود.
</div>
@endif
