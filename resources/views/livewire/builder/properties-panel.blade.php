<div>
    @if($blockId)
        <div class="p-3">
            <h6 class="fw-bold mb-3" style="font-size:0.85rem">ویژگی‌های بلوک</h6>

            {{-- Content Properties --}}
            <div class="mb-3">
                <label class="form-label small fw-medium">محتوا</label>
                @foreach($content as $key => $value)
                    @if(is_string($value))
                        <div class="mb-2">
                            <label class="form-label" style="font-size:0.75rem">{{ $key }}</label>
                            <input type="text" class="form-control form-control-sm"
                                   value="{{ $value }}"
                                   wire:change="updateContent('{{ $key }}', $event.target.value)">
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- Style Properties --}}
            <div class="mb-3">
                <label class="form-label small fw-medium">استایل</label>
                @if(isset($styles['desktop']))
                    @foreach($styles['desktop'] as $property => $value)
                        @if(is_string($value))
                            <div class="mb-2">
                                <label class="form-label" style="font-size:0.75rem">{{ $property }}</label>
                                <input type="text" class="form-control form-control-sm"
                                       value="{{ $value }}"
                                       wire:change="updateStyle('{{ $property }}', $event.target.value)">
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    @else
        <div class="text-center text-muted py-5">
            <i class="bi bi-cursor fs-1 d-block mb-2"></i>
            <p class="small">یک بلوک را انتخاب کنید</p>
        </div>
    @endif
</div>
