<div>
@if($isOpen)
<div style="position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.5);backdrop-filter:blur(4px)" wire:click.self="close">
    <div style="background:#fff;border-radius:12px;width:90%;max-width:800px;max-height:85vh;display:flex;flex-direction:column;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.3)">

        {{-- Header --}}
        <div style="padding:16px 20px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;justify-content:space-between">
            <h3 style="font-size:14px;font-weight:600;margin:0">انتخاب تصویر</h3>
            <button wire:click="close" style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;background:transparent;border:none;border-radius:6px;cursor:pointer;color:#6b7280;font-size:16px"><i class="bi bi-x-lg"></i></button>
        </div>

        {{-- Upload Area --}}
        <div style="padding:12px 20px;border-bottom:1px solid #e5e7eb">
            <div style="display:flex;gap:8px;align-items:center">
                <label style="flex:1;display:flex;align-items:center;gap:8px;padding:10px 14px;background:#f9fafb;border:2px dashed #d1d5db;border-radius:8px;cursor:pointer;transition:all .15s;font-size:12px;color:#6b7280" onmouseover="this.style.borderColor='#6366f1';this.style.background='#eef2ff'" onmouseout="this.style.borderColor='#d1d5db';this.style.background='#f9fafb'">
                    <i class="bi bi-cloud-upload" style="font-size:18px;color:#6366f1"></i>
                    <span>فایل را انتخاب کنید</span>
                    <input type="file" wire:model="newFile" accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml" style="display:none">
                </label>
                @if($newFile)
                    <button wire:click="saveUploadedFile" type="button" style="padding:10px 20px;background:#4f46e5;color:#fff;border:none;border-radius:8px;cursor:pointer;font-size:12px;font-weight:500;white-space:nowrap">آپلود</button>
                @endif
            </div>
            <div wire:loading wire:target="saveUploadedFile" style="margin-top:8px">
                <div style="height:4px;background:#e5e7eb;border-radius:2px;overflow:hidden">
                    <div style="height:100%;background:#4f46e5;animation:pulse 1.5s ease-in-out infinite;width:100%"></div>
                </div>
                <p style="font-size:11px;color:#9ca3af;margin-top:4px">در حال آپلود...</p>
            </div>
            @error('newFile')
                <p style="font-size:11px;color:#dc2626;margin-top:4px">{{ $message }}</p>
            @enderror
        </div>

        {{-- Filters --}}
        <div style="padding:8px 20px;border-bottom:1px solid #e5e7eb;display:flex;gap:4px">
            <button wire:click="$set('filter', 'all')" style="padding:5px 12px;font-size:11px;border:1px solid {{ $filter === 'all' ? '#c7d2fe' : '#e5e7eb' }};border-radius:16px;cursor:pointer;background:{{ $filter === 'all' ? '#eef2ff' : '#f9fafb' }};color:{{ $filter === 'all' ? '#4f46e5' : '#6b7280' }}">همه</button>
            <button wire:click="$set('filter', 'image')" style="padding:5px 12px;font-size:11px;border:1px solid {{ $filter === 'image' ? '#c7d2fe' : '#e5e7eb' }};border-radius:16px;cursor:pointer;background:{{ $filter === 'image' ? '#eef2ff' : '#f9fafb' }};color:{{ $filter === 'image' ? '#4f46e5' : '#6b7280' }}">تصاویر</button>
        </div>

        {{-- Media Grid --}}
        <div style="flex:1;overflow-y:auto;padding:16px 20px">
            @php $media = $this->getMedia(); @endphp
            @if($media->isEmpty())
                <div style="text-align:center;padding:48px;color:#9ca3af">
                    <i class="bi bi-image" style="font-size:32px;margin-bottom:12px;display:block"></i>
                    <p style="font-size:13px">هنوز فایلی آپلود نشده</p>
                </div>
            @else
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:8px">
                    @foreach($media as $item)
                        <div
                            wire:click="selectMedia({{ $item->id }})"
                            style="position:relative;border-radius:8px;overflow:hidden;cursor:pointer;border:2px solid {{ $selectedMediaId == $item->id ? '#6366f1' : 'transparent' }};transition:all .15s;aspect-ratio:1"
                            onmouseover="this.style.borderColor='rgba(99,102,241,.5)'"
                            onmouseout="this.style.borderColor='{{ $selectedMediaId == $item->id ? '#6366f1' : 'transparent' }}'"
                        >
                            @if(str_starts_with($item->mime_type, 'image/'))
                                <img src="{{ $item->url }}" alt="{{ $item->name }}" style="width:100%;height:100%;object-fit:cover">
                            @else
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f9fafb">
                                    <i class="bi bi-file-earmark" style="font-size:24px;color:#d1d5db"></i>
                                </div>
                            @endif
                            @if($selectedMediaId == $item->id)
                                <div style="position:absolute;top:6px;right:6px;width:22px;height:22px;display:flex;align-items:center;justify-content:center;background:#4f46e5;border-radius:50%;color:#fff;font-size:10px"><i class="bi bi-check-lg"></i></div>
                            @endif
                            <div style="position:absolute;bottom:0;left:0;right:0;padding:4px 6px;background:linear-gradient(transparent,rgba(0,0,0,.6));color:#fff;font-size:9px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $item->name }}</div>
                        </div>
                    @endforeach
                </div>
                <div style="margin-top:12px;display:flex;justify-content:center">
                    {{ $media->links() }}
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div style="padding:12px 20px;border-top:1px solid #e5e7eb;display:flex;align-items:center;justify-content:space-between">
            <div style="font-size:11px;color:#9ca3af">
                @if($selectedUrl)
                    <span style="color:#4f46e5;font-weight:500">تصویر انتخاب شده</span>
                @else
                    تصویری انتخاب نشده
                @endif
            </div>
            <div style="display:flex;gap:8px">
                <button wire:click="close" style="padding:8px 16px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;cursor:pointer;font-size:12px;color:#6b7280">لغو</button>
                @if($selectedUrl)
                    <button wire:click="confirmSelection" style="padding:8px 16px;background:#4f46e5;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:12px;font-weight:500">انتخاب</button>
                @else
                    <button disabled style="padding:8px 16px;background:#e5e7eb;color:#fff;border:none;border-radius:6px;cursor:not-allowed;font-size:12px">انتخاب</button>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
</div>
