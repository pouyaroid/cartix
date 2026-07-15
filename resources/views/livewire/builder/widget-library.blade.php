<div>
    <div class="p-2 border-bottom">
        <input type="text" class="form-control form-control-sm" placeholder="جستجوی ویجت..."
               wire:model.live.debounce.300ms="search">
    </div>

    <div class="p-2">
        @foreach($categories as $catKey => $cat)
            @if(isset($this->widgets[$catKey]))
                <div class="mb-3">
                    <div class="sidebar-section-title">
                        <i class="bi {{ $cat['icon'] }} ms-1"></i> {{ $cat['label'] }}
                    </div>
                    <div class="row g-1">
                        @foreach($this->widgets[$catKey] as $widget)
                            <div class="col-6">
                                <div class="widget-item" draggable="true"
                                     data-component="{{ $widget['component'] }}"
                                     data-type="{{ in_array($widget['component'], ['layout-section', 'layout-column', 'layout-container']) ? 'layout' : 'widget' }}">
                                    <i class="bi {{ $widget['icon'] }}"></i>
                                    <span style="font-size:0.8rem">{{ $widget['name'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
