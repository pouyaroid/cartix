<div class="builder-layout" style="display:flex;height:100vh;overflow:hidden;font-family:'Vazirmatn',system-ui,sans-serif" dir="rtl">

    {{-- LEFT SIDEBAR: Widget Library --}}
    <div class="builder-sidebar-left" style="width:260px;background:#fff;border-left:1px solid #e5e7eb;display:flex;flex-direction:column;flex-shrink:0">
        <div style="padding:14px 16px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;justify-content:space-between">
            <h6 style="font-size:13px;font-weight:600;margin:0">ابزارها</h6>
            <a href="{{ route('dashboard.landing-pages.index') }}" style="color:#9ca3af;text-decoration:none;font-size:14px"><i class="bi bi-x-lg"></i></a>
        </div>
        <div style="padding:8px 12px;border-bottom:1px solid #e5e7eb">
            <div style="position:relative">
                <i class="bi bi-search" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);font-size:12px;color:#9ca3af"></i>
                <input type="text" class="form-control form-control-sm" placeholder="جستجوی ابزار..." wire:model.live.debounce.300ms="widgetSearch" style="font-size:12px;padding-right:30px">
            </div>
        </div>
        <div style="flex:1;overflow-y:auto;padding:8px">
            @foreach($this->getWidgetCategories() as $catKey => $cat)
                @php
                    $visibleWidgets = collect($cat['widgets'])->filter(fn($w) => empty($widgetSearch) || str_contains($w['name'], $widgetSearch) || str_contains($w['component'], $widgetSearch));
                @endphp
                @if($visibleWidgets->isNotEmpty())
                    <div style="margin-bottom:12px">
                        <div style="font-size:10px;font-weight:600;color:#9ca3af;padding:8px 8px 4px;display:flex;align-items:center;gap:6px">
                            <i class="bi {{ $cat['icon'] }}"></i> {{ $cat['label'] }}
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:4px">
                            @foreach($visibleWidgets as $widget)
                                <div
                                    wire:click="addBlock('{{ $widget['component'] }}', 'widget')"
                                    class="widget-item"
                                    style="display:flex;align-items:center;gap:6px;padding:7px 10px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;cursor:pointer;transition:all .15s;font-size:11px;color:#374151"
                                    draggable="true"
                                    ondragstart="event.dataTransfer.setData('text/plain','{{ $widget['component'] }}')"
                                >
                                    <i class="bi {{ $widget['icon'] }}" style="font-size:13px;color:#6366f1"></i>
                                    <span style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $widget['name'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- CENTER: Canvas --}}
    <div style="flex:1;display:flex;flex-direction:column;overflow:hidden">
        {{-- Toolbar --}}
        <div style="height:48px;background:#fff;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;justify-content:space-between;padding:0 16px;flex-shrink:0">
            <div style="display:flex;align-items:center;gap:10px">
                <a href="{{ route('dashboard.landing-pages.edit', $page) }}" style="color:#9ca3af;text-decoration:none;font-size:16px" title="تنظیمات صفحه"><i class="bi bi-gear"></i></a>
                <span style="font-size:13px;font-weight:600;color:#111827">{{ $page->title }}</span>
                <span style="font-size:10px;padding:2px 8px;border-radius:20px;font-weight:500;background:{{ $page->isPublished() ? '#dcfce7' : '#f3f4f6' }};color:{{ $page->isPublished() ? '#16a34a' : '#6b7280' }}">{{ $page->isPublished() ? 'منتشر' : 'پیش‌نویس' }}</span>
                @if($isDirty)
                    <span style="font-size:9px;padding:2px 6px;border-radius:10px;background:#fef3c7;color:#d97706">تغییرات ذخیره نشده</span>
                @endif
            </div>
            <div style="display:flex;align-items:center;gap:6px">
                <div style="display:flex;gap:2px">
                    <button wire:click="undo" style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;background:#fff;border:1px solid #e5e7eb;border-radius:6px;cursor:pointer;font-size:13px;color:#6b7280" title="بازگشت"><i class="bi bi-arrow-return-right"></i></button>
                    <button wire:click="redo" style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;background:#fff;border:1px solid #e5e7eb;border-radius:6px;cursor:pointer;font-size:13px;color:#6b7280" title="بازانجام"><i class="bi bi-arrow-return-left"></i></button>
                </div>
                <div style="width:1px;height:20px;background:#e5e7eb"></div>
                <div style="display:flex;gap:2px">
                    <button class="toolbar-btn {{ $responsiveMode === 'desktop' ? 'active' : '' }}" wire:click="setResponsiveMode('desktop')" style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;background:#fff;border:1px solid #e5e7eb;border-radius:6px;cursor:pointer;font-size:13px" title="دسکتاپ"><i class="bi bi-display"></i></button>
                    <button class="toolbar-btn {{ $responsiveMode === 'tablet' ? 'active' : '' }}" wire:click="setResponsiveMode('tablet')" style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;background:#fff;border:1px solid #e5e7eb;border-radius:6px;cursor:pointer;font-size:13px" title="تبلت"><i class="bi bi-tablet"></i></button>
                    <button class="toolbar-btn {{ $responsiveMode === 'mobile' ? 'active' : '' }}" wire:click="setResponsiveMode('mobile')" style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;background:#fff;border:1px solid #e5e7eb;border-radius:6px;cursor:pointer;font-size:13px" title="موبایل"><i class="bi bi-phone"></i></button>
                </div>
                <div style="width:1px;height:20px;background:#e5e7eb"></div>
                <a href="{{ route('dashboard.landing-pages.preview', $page) }}" target="_blank" style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;background:#fff;border:1px solid #e5e7eb;border-radius:6px;color:#6b7280;text-decoration:none;font-size:13px" title="پیش‌نمایش"><i class="bi bi-eye"></i></a>
                <button wire:click="save" style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;background:#4f46e5;color:#fff;border:1px solid #4f46e5;border-radius:6px;cursor:pointer;font-size:13px" title="ذخیره"><i class="bi bi-save"></i></button>
                <form action="{{ route('dashboard.landing-pages.publish', $page) }}" method="POST" style="display:inline">
                    @csrf
                    <button type="submit" style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;background:{{ $page->isPublished() ? '#fff' : '#22c55e' }};color:{{ $page->isPublished() ? '#6b7280' : '#fff' }};border:1px solid {{ $page->isPublished() ? '#e5e7eb' : '#22c55e' }};border-radius:6px;cursor:pointer;font-size:13px" title="{{ $page->isPublished() ? 'لغو انتشار' : 'انتشار' }}"><i class="bi bi-{{ $page->isPublished() ? 'eye-slash' : 'send' }}"></i></button>
                </form>
            </div>
        </div>

        {{-- Canvas --}}
        <div style="flex:1;overflow:auto;background:#f3f4f6;display:flex;justify-content:center;padding:32px">
            <div id="canvasFrame" style="width:100%;max-width:{{ $responsiveMode === 'mobile' ? '390px' : ($responsiveMode === 'tablet' ? '768px' : '100%') }};background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.08),0 4px 16px rgba(0,0,0,.04);min-height:600px;overflow:hidden;transition:max-width .3s;position:relative">
                @if(empty($blocks))
                    <div style="padding:80px 40px;text-align:center;color:#6366f1;border:2px dashed #c7d2fe;border-radius:12px;margin:32px">
                        <i class="bi bi-plus-circle" style="font-size:40px;opacity:.4;margin-bottom:12px;display:block"></i>
                        <h6 style="font-size:14px;font-weight:600;margin-bottom:4px">ابزاری را اینجا اضافه کنید</h6>
                        <p style="font-size:12px;color:#9ca3af">از پنل سمت راست یک ویجت انتخاب کنید</p>
                    </div>
                @else
                    <div id="blocksContainer" wire:sortable="handleReorder" style="min-height:100px">
                        @foreach($blocks as $block)
                            @include('livewire.page-builder.block', ['block' => $block, 'depth' => 0])
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- RIGHT SIDEBAR: Properties / Styles / Layers --}}
    <div class="builder-sidebar-right" style="width:300px;background:#fff;border-right:1px solid #e5e7eb;display:flex;flex-direction:column;flex-shrink:0">
        <div style="display:flex;border-bottom:1px solid #e5e7eb">
            <button wire:click="$set('activeTab', 'properties')" class="sidebar-tab" style="flex:1;padding:10px;text-align:center;font-size:11px;font-weight:500;cursor:pointer;border:none;border-bottom:2px solid {{ $activeTab === 'properties' ? '#4f46e5' : 'transparent' }};background:{{ $activeTab === 'properties' ? '#fff' : '#f9fafb' }};color:{{ $activeTab === 'properties' ? '#4f46e5' : '#9ca3af' }}">محتوا</button>
            <button wire:click="$set('activeTab', 'style')" class="sidebar-tab" style="flex:1;padding:10px;text-align:center;font-size:11px;font-weight:500;cursor:pointer;border:none;border-bottom:2px solid {{ $activeTab === 'style' ? '#4f46e5' : 'transparent' }};background:{{ $activeTab === 'style' ? '#fff' : '#f9fafb' }};color:{{ $activeTab === 'style' ? '#4f46e5' : '#9ca3af' }}">استایل</button>
            <button wire:click="$set('activeTab', 'layers')" class="sidebar-tab" style="flex:1;padding:10px;text-align:center;font-size:11px;font-weight:500;cursor:pointer;border:none;border-bottom:2px solid {{ $activeTab === 'layers' ? '#4f46e5' : 'transparent' }};background:{{ $activeTab === 'layers' ? '#fff' : '#f9fafb' }};color:{{ $activeTab === 'layers' ? '#4f46e5' : '#9ca3af' }}">لایه‌ها</button>
        </div>
        <div style="flex:1;overflow-y:auto;padding:16px">
            @php $selectedBlock = $this->getSelectedBlock(); @endphp
            @if($activeTab === 'properties')
                @if($selectedBlock)
                    @include('livewire.page-builder.block-editor', ['block' => $selectedBlock])
                @else
                    <div style="text-align:center;padding:48px 16px;color:#9ca3af">
                        <i class="bi bi-cursor" style="font-size:32px;margin-bottom:12px;display:block"></i>
                        <p style="font-size:12px">یک بلوک انتخاب کنید</p>
                    </div>
                @endif
            @elseif($activeTab === 'style')
                @if($selectedBlock)
                    @include('livewire.page-builder.style-editor', ['block' => $selectedBlock, 'responsiveMode' => $responsiveMode])
                @else
                    <div style="text-align:center;padding:48px 16px;color:#9ca3af">
                        <i class="bi bi-palette" style="font-size:32px;margin-bottom:12px;display:block"></i>
                        <p style="font-size:12px">یک بلوک انتخاب کنید</p>
                    </div>
                @endif
            @elseif($activeTab === 'layers')
                <div style="font-size:10px;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px">لایه‌ها</div>
                @forelse($blocks as $block)
                    <div wire:click="selectBlock({{ $block['id'] }})" style="display:flex;align-items:center;gap:8px;padding:7px 10px;background:{{ $selectedBlockId == $block['id'] ? '#eef2ff' : '#f9fafb' }};border:1px solid {{ $selectedBlockId == $block['id'] ? '#c7d2fe' : '#e5e7eb' }};border-radius:6px;margin-bottom:4px;cursor:pointer;font-size:11px;color:{{ $selectedBlockId == $block['id'] ? '#4f46e5' : '#374151' }}">
                        <i class="bi bi-grip-vertical" style="font-size:10px;color:#d1d5db;cursor:grab"></i>
                        <i class="bi bi-puzzle" style="font-size:10px"></i>
                        <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $this->getBlockLabel($block['component']) }}</span>
                        @if(!($block['is_visible'] ?? true))
                            <i class="bi bi-eye-slash" style="font-size:9px;color:#dc2626"></i>
                        @endif
                    </div>
                @empty
                    <div style="text-align:center;padding:24px;color:#9ca3af">
                        <p style="font-size:11px">بلوکی وجود ندارد</p>
                    </div>
                @endforelse
            @endif
        </div>
    </div>
    @livewire('media-picker')

    <script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('show-toast', (data) => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true, position: 'top-end',
                    icon: data[0].type || 'info',
                    title: data[0].message,
                    showConfirmButton: false, timer: 2000,
                });
            }
        });
    });
    </script>
</div>
