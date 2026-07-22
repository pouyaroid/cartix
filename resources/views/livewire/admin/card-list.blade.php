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

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ number_format($stats['total']) }}</h4>
                    <small>کل کارت‌ها</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Search --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label fw-bold small">جستجو</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="جستجو بر اساس عنوان، توضیحات، نام کاربر...">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small">تعداد در صفحه</label>
                    <select wire:model.live="perPage" class="form-select">
                        <option value="10">۱۰</option>
                        <option value="15">۱۵</option>
                        <option value="25">۲۵</option>
                        <option value="50">۵۰</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width:60px">#</th>
                            <th style="width:80px">پیش‌نمایش</th>
                            <th>عنوان</th>
                            <th>کاربر</th>
                            <th class="text-center">تاریخ ایجاد</th>
                            <th class="text-center" style="width:120px">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cards as $card)
                        <tr>
                            <td class="text-center">{{ $card->id }}</td>
                            <td class="text-center">
                                <canvas class="admin-card-preview"
                                        data-card-id="{{ $card->id }}"
                                        data-width="{{ $card->canvas_width }}"
                                        data-height="{{ $card->canvas_height }}"
                                        data-bg="{{ $card->settings['background_color'] ?? '#ffffff' }}"
                                        data-design="{{ htmlspecialchars(json_encode($card->design_data ?? ['objects' => []])) }}"
                                        style="width:60px;height:45px">
                                </canvas>
                            </td>
                            <td>
                                <a href="{{ route('admin.cards.show', $card) }}" class="text-decoration-none fw-bold">{{ $card->title }}</a>
                                @if($card->description)<br><small class="text-muted">{{ Str::limit($card->description, 50) }}</small>@endif
                            </td>
                            <td>
                                <span class="fw-bold">{{ $card->user->name }}</span>
                                <br><small class="text-muted">{{ $card->user->email }}</small>
                            </td>
                            <td class="text-center">
                                <small class="text-muted">{{ \Morilog\Jalali\Jalalian::fromCarbon($card->created_at)->format('Y/m/d H:i') }}</small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.cards.show', $card) }}" class="btn btn-outline-primary" title="مشاهده">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button wire:click="delete({{ $card->id }})" class="btn btn-outline-danger" title="حذف"
                                            onclick="return confirm('آیا از حذف این کارت اطمینان دارید؟')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-inbox display-4 text-muted d-block mb-2"></i>
                                <p class="text-muted mb-0">کارتی یافت نشد.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($cards->hasPages())
        <div class="card-footer bg-transparent">{{ $cards->links() }}</div>
        @endif
    </div>

    @script
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
    <script>
        function initAdminPreviews() {
            document.querySelectorAll('.admin-card-preview').forEach(function(el) {
                if (el.dataset.rendered) return;
                var w = parseInt(el.dataset.width) || 900;
                var h = parseInt(el.dataset.height) || 600;
                var bg = el.dataset.bg || '#ffffff';
                var design;
                try { design = JSON.parse(el.dataset.design); } catch(e) { design = null; }

                var displayW = 60;
                var displayH = 45;

                var c = new fabric.Canvas(el, {
                    width: displayW,
                    height: displayH,
                    backgroundColor: bg,
                    selection: false,
                    readOnly: true,
                });

                c.setZoom(displayW / w);
                c.setWidth(displayW);
                c.setHeight(displayH);

                if (design && design.objects && design.objects.length > 0) {
                    c.loadFromJSON(design, function() {
                        c.setZoom(displayW / w);
                        c.setWidth(displayW);
                        c.setHeight(displayH);
                        c.forEachObject(function(obj) { obj.selectable = false; obj.evented = false; });
                        c.renderAll();
                    });
                }
                el.dataset.rendered = '1';
            });
        }

        initAdminPreviews();
        $wire.on('render', () => setTimeout(initAdminPreviews, 100));
    </script>
    @endscript
</div>
