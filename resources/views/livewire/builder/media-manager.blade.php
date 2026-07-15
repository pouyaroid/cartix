<div>
    @if($showModal)
        <div class="modal d-block" tabindex="-1" style="background:rgba(0,0,0,0.5)">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title fw-bold">انتخاب تصویر</h6>
                        <button type="button" class="btn-close" wire:click="close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-2">
                            @foreach($mediaItems as $item)
                                <div class="col-3">
                                    <div class="card card-body p-1 text-center" style="cursor:pointer"
                                         wire:click="select('{{ asset('storage/' . $item['path']) }}')">
                                        @if(str_starts_with($item['mime_type'], 'image/'))
                                            <img src="{{ asset('storage/' . $item['path']) }}"
                                                 style="width:100%;height:80px;object-fit:cover;border-radius:4px">
                                        @else
                                            <i class="bi bi-file-earmark fs-3 text-muted"></i>
                                        @endif
                                        <div class="text-muted mt-1" style="font-size:0.65rem">{{ $item['name'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
