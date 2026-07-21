<div>
    {{-- Toast --}}
    <div x-data="{ toasts: [] }"
         x-on:show-toast.window="toasts.push({type:$event.detail.type,message:$event.detail.message,id:Date.now()});setTimeout(()=>toasts.shift(),4000)"
         class="position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index:9999">
        <template x-for="t in toasts" :key="t.id">
            <div x-show x-transition :class="{'alert-success':t.type==='success','alert-danger':t.type==='error','alert-info':t.type==='info'}"
                 class="alert alert-dismissible fade show mb-2 shadow-sm"><span x-text="t.message"></span>
                <button type="button" class="btn-close" @click="toasts.shift()"></button>
            </div>
        </template>
    </div>

    <div class="row g-4">
        {{-- Preview --}}
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">{{ $card->title }}</h5>
                    <a href="{{ route('admin.cards.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-right me-1"></i> بازگشت
                    </a>
                </div>
                <div class="card-body text-center bg-light">
                    @if($card->getFirstMedia('final-image'))
                        <img src="{{ $card->getFirstMedia('final-image')->getUrl() }}" alt="{{ $card->title }}" class="img-fluid rounded shadow-sm" style="max-height:500px">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-white rounded border" style="min-height:400px">
                            <div class="text-center text-muted">
                                <i class="bi bi-image display-1 d-block mb-2"></i>
                                <p>تصویر نهایی تولید نشده است</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Canvas Preview --}}
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-transparent"><h6 class="mb-0 fw-bold">پیش‌نمایش طراحی</h6></div>
                <div class="card-body text-center bg-light">
                    <canvas id="previewCanvas" width="{{ $card->canvas_width }}" height="{{ $card->canvas_height }}"></canvas>
                </div>
            </div>
        </div>

        {{-- Info & Actions --}}
        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-transparent"><h6 class="mb-0 fw-bold">اطلاعات</h6></div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr><td class="text-muted">عنوان:</td><td class="fw-bold">{{ $card->title }}</td></tr>
                        <tr><td class="text-muted">کاربر:</td><td><a href="{{ route('admin.users.show', $card->user) }}" class="text-decoration-none">{{ $card->user->name }}</a></td></tr>
                        <tr><td class="text-muted">ایمیل:</td><td>{{ $card->user->email }}</td></tr>
                        <tr><td class="text-muted">اندازه:</td><td>{{ $card->canvas_width }} × {{ $card->canvas_height }} px</td></tr>
                        <tr><td class="text-muted">تاریخ ایجاد:</td><td>{{ \Morilog\Jalali\Jalalian::fromCarbon($card->created_at)->format('Y/m/d H:i') }}</td></tr>
                        <tr><td class="text-muted">آخرین بروزرسانی:</td><td>{{ \Morilog\Jalali\Jalalian::fromCarbon($card->updated_at)->format('Y/m/d H:i') }}</td></tr>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body d-grid gap-2">
                    <button wire:click="delete" class="btn btn-outline-danger"
                            onclick="return confirm('آیا از حذف این کارت اطمینان دارید؟')">
                        <i class="bi bi-trash me-1"></i> حذف کارت
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
    <script>
    (function() {
        const previewCanvas = new fabric.Canvas('previewCanvas', {
            width: {{ $card->canvas_width }}, height: {{ $card->canvas_height }},
            backgroundColor: '{{ $card->settings["background_color"] ?? "#ffffff" }}',
            selection: false, readOnly: true,
        });
        @if($card->design_data && isset($card->design_data['objects']))
            previewCanvas.loadFromJSON(@json($card->design_data), function() {
                previewCanvas.forEachObject(function(obj) { obj.selectable = false; obj.evented = false; });
                previewCanvas.renderAll();
            });
        @endif
    })();
    </script>
    @endpush
</div>
