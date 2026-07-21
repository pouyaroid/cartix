<div class="card-builder-container" wire:ignore.self>
    {{-- Toast Notifications --}}
    <div x-data="{ toasts: [] }"
         x-on:show-toast.window="
            toasts.push({ type: $event.detail.type, message: $event.detail.message, id: Date.now() });
            setTimeout(() => toasts.shift(), 4000);
         "
         class="position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 9999;">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="true" x-transition
                 :class="{
                    'alert-success': toast.type === 'success',
                    'alert-danger': toast.type === 'error',
                    'alert-warning': toast.type === 'warning',
                    'alert-info': toast.type === 'info'
                 }"
                 class="alert alert-dismissible fade show mb-2 shadow-sm" role="alert">
                <span x-text="toast.message"></span>
                <button type="button" class="btn-close" @click="toasts.shift()"></button>
            </div>
        </template>
    </div>

    <div class="row g-0" style="height: calc(100vh - 120px);">
        {{-- Left Sidebar: Tools --}}
        <div class="col-lg-3 col-md-4 border-end bg-body overflow-auto" style="max-height: calc(100vh - 120px);">
            <div class="p-3">
                {{-- Card Title --}}
                <div class="mb-3">
                    <label class="form-label fw-bold small">عنوان کارت</label>
                    <input type="text" wire:model.live="title" class="form-control form-control-sm" placeholder="عنوان کارت را وارد کنید">
                    @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Tab Navigation --}}
                <ul class="nav nav-pills nav-fill mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link btn-sm {{ $activeTab === 'text' ? 'active' : '' }}"
                                onclick="window.setTab('text')" type="button">
                            <i class="bi bi-fonts"></i>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link btn-sm {{ $activeTab === 'image' ? 'active' : '' }}"
                                onclick="window.setTab('image')" type="button">
                            <i class="bi bi-image"></i>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link btn-sm {{ $activeTab === 'video' ? 'active' : '' }}"
                                onclick="window.setTab('video')" type="button">
                            <i class="bi bi-camera-video"></i>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link btn-sm {{ $activeTab === 'link' ? 'active' : '' }}"
                                onclick="window.setTab('link')" type="button">
                            <i class="bi bi-link-45deg"></i>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link btn-sm {{ $activeTab === 'background' ? 'active' : '' }}"
                                onclick="window.setTab('background')" type="button">
                            <i class="bi bi-palette"></i>
                        </button>
                    </li>
                </ul>

                {{-- Text Tab --}}
                <div id="tabText" class="tab-pane" style="{{ $activeTab !== 'text' ? 'display:none' : '' }}">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">متن</label>
                        <input type="text" id="inputText" class="form-control form-control-sm" placeholder="متن مورد نظر را تایپ کنید" value="متن جدید">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small">فونت</label>
                            <select id="inputFont" class="form-select form-select-sm">
                                @foreach($fonts as $font)
                                    <option value="{{ $font }}">{{ $font }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small">اندازه</label>
                            <input type="number" id="inputSize" class="form-control form-control-sm" min="8" max="200" value="{{ $textSize }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">رنگ</label>
                        <input type="color" id="inputColor" class="form-control form-control form-control-sm form-control-color" value="{{ $textColor }}">
                    </div>
                    <div class="mb-3 d-flex gap-2">
                        <button id="btnBold" class="btn btn-sm btn-outline-dark" onclick="window.toggleBold(this)">
                            <i class="bi bi-type-bold"></i>
                        </button>
                        <button id="btnItalic" class="btn btn-sm btn-outline-dark" onclick="window.toggleItalic(this)">
                            <i class="bi bi-type-italic"></i>
                        </button>
                        <button id="btnUnderline" class="btn btn-sm btn-outline-dark" onclick="window.toggleUnderline(this)">
                            <i class="bi bi-type-underline"></i>
                        </button>
                    </div>
                    <div class="d-grid gap-2">
                        <button onclick="window.canvasAddText()" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> افزودن متن
                        </button>
                        <button onclick="window.canvasUpdateText()" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-pencil me-1"></i> بروزرسانی متن انتخاب شده
                        </button>
                    </div>
                </div>

                {{-- Image Tab --}}
                <div id="tabImage" class="tab-pane" style="{{ $activeTab !== 'image' ? 'display:none' : '' }}">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">آپلود تصویر</label>
                        <input type="file" id="fileImageInput" accept="image/*" class="form-control form-control-sm"
                               onchange="window.handleImageFile(this)">
                        <small class="text-muted">فرمت‌های مجاز: JPG, PNG, GIF, WebP (حداکثر ۱۰ مگابایت)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">یا URL تصویر</label>
                        <div class="input-group input-group-sm">
                            <input type="url" id="imageUrlInput" class="form-control" placeholder="https://example.com/image.jpg">
                            <button class="btn btn-outline-primary" type="button"
                                    onclick="window.canvasAddImage(document.getElementById('imageUrlInput').value)">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Video Tab --}}
                <div id="tabVideo" class="tab-pane" style="{{ $activeTab !== 'video' ? 'display:none' : '' }}">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">آپلود ویدیو</label>
                        <input type="file" id="fileVideoInput" accept="video/mp4,video/webm,video/ogg" class="form-control form-control-sm"
                               onchange="window.handleVideoFile(this)">
                        <small class="text-muted">فرمت‌های مجاز: MP4, WebM, OGG (حداکثر ۵۰ مگابایت)</small>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">ویدیو به صورت خودکار با آیکون پخش روی canvas قرار می‌گیرد.</small>
                    </div>
                </div>

                {{-- Link Tab --}}
                <div id="tabLink" class="tab-pane" style="{{ $activeTab !== 'link' ? 'display:none' : '' }}">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">افزودن لینک / دکمه</label>
                        <button onclick="window.canvasAddLink()" class="btn btn-outline-primary btn-sm w-100 mb-2">
                            <i class="bi bi-link-45deg me-1"></i> افزودن متن لینک‌دار
                        </button>
                        <button onclick="window.canvasAddQrCode()" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="bi bi-qr-code me-1"></i> افزودن کد QR
                        </button>
                    </div>
                </div>

                {{-- Background Tab --}}
                <div id="tabBackground" class="tab-pane" style="{{ $activeTab !== 'background' ? 'display:none' : '' }}">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">رنگ پس‌زمینه</label>
                        <input type="color" id="inputBgColor" class="form-control form-control form-control-sm form-control-color w-100" value="{{ $backgroundColor }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">نوع گرادیانت</label>
                        <select id="inputGradientType" class="form-select form-select-sm" onchange="window.canvasApplyGradient()">
                            <option value="none" {{ $gradientType === 'none' ? 'selected' : '' }}>بدون گرادیانت</option>
                            <option value="linear" {{ $gradientType === 'linear' ? 'selected' : '' }}>خطی (Linear)</option>
                            <option value="radial" {{ $gradientType === 'radial' ? 'selected' : '' }}>شعاعی (Radial)</option>
                        </select>
                    </div>
                    <div id="gradientOptions" style="{{ $gradientType === 'none' ? 'display:none' : '' }}">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">رنگ اول</label>
                            <input type="color" id="inputGradColor1" class="form-control form-control form-control-sm form-control-color w-100" value="{{ $gradientColor1 }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">رنگ دوم</label>
                            <input type="color" id="inputGradColor2" class="form-control form-control form-control-sm form-control-color w-100" value="{{ $gradientColor2 }}">
                        </div>
                        <div id="gradientAngleWrap" class="mb-3" style="{{ $gradientType !== 'linear' ? 'display:none' : '' }}">
                            <label class="form-label fw-bold small">زاویه (<span id="gradAngleVal">{{ $gradientAngle }}</span>°)</label>
                            <input type="range" id="inputGradAngle" class="form-range" min="0" max="360" step="5" value="{{ $gradientAngle }}">
                        </div>
                    </div>
                </div>

                <hr>

                {{-- Object Actions --}}
                <div class="mb-3">
                    <label class="form-label fw-bold small">عملیات</label>
                    <div class="d-grid gap-1">
                        <button onclick="window.canvasDuplicate()" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-clone me-1"></i> کپی
                        </button>
                        <button onclick="window.canvasBringForward()" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-up me-1"></i> بالا بردن
                        </button>
                        <button onclick="window.canvasSendBackward()" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-down me-1"></i> پایین بردن
                        </button>
                        <button onclick="window.canvasDeleteSelected()" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash me-1"></i> حذف انتخاب شده
                        </button>
                    </div>
                </div>

                <hr>

                {{-- Canvas Size --}}
                <div class="mb-3">
                    <label class="form-label fw-bold small">اندازه Canvas</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="number" id="inputCanvasW" class="form-control form-control-sm" min="300" max="2000" value="{{ $canvasWidth }}" onchange="window.canvasResize()">
                        </div>
                        <div class="col-6">
                            <input type="number" id="inputCanvasH" class="form-control form-control-sm" min="200" max="2000" value="{{ $canvasHeight }}" onchange="window.canvasResize()">
                        </div>
                    </div>
                </div>

                <hr>

                {{-- Save Actions --}}
                <div class="d-grid gap-2">
                    <button onclick="window.syncAndCall('save')" class="btn btn-success btn-sm" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">
                            <i class="bi bi-check-circle me-1"></i> ذخیره
                        </span>
                        <span wire:loading wire:target="save">
                            <span class="spinner-border spinner-border-sm"></span> در حال ذخیره...
                        </span>
                    </button>
                    <button onclick="window.syncAndCall('generateImage')" class="btn btn-warning btn-sm" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="generateImage">
                            <i class="bi bi-image me-1"></i> تولید تصویر نهایی
                        </span>
                        <span wire:loading wire:target="generateImage">
                            <span class="spinner-border spinner-border-sm"></span> در حال تولید...
                        </span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Center: Canvas --}}
        <div class="col-lg-9 col-md-8 d-flex align-items-center justify-content-center bg-secondary bg-opacity-10 overflow-auto" style="max-height: calc(100vh - 120px);">
            <div class="canvas-wrapper position-relative my-3" id="canvasWrapper" wire:ignore>
                <canvas id="fabricCanvas"></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
    <script>
    (function() {
        let canvas = null;
        let textBold = false;
        let textItalic = false;
        let textUnderline = false;

        // ─── Tab switching ──────────────────────────────────────────────
        const tabs = ['text', 'image', 'video', 'link', 'background'];
        window.setTab = function(name) {
            tabs.forEach(t => {
                const el = document.getElementById('tab' + t.charAt(0).toUpperCase() + t.slice(1));
                if (el) el.style.display = (t === name) ? '' : 'none';
            });
            // Update nav pills active state
            document.querySelectorAll('.nav-pills .nav-link').forEach(btn => btn.classList.remove('active'));
            event.currentTarget.classList.add('active');
        };

        // ─── Toggle buttons ─────────────────────────────────────────────
        window.toggleBold = function(btn) {
            textBold = !textBold;
            btn.classList.toggle('btn-dark');
            btn.classList.toggle('btn-outline-dark');
        };
        window.toggleItalic = function(btn) {
            textItalic = !textItalic;
            btn.classList.toggle('btn-dark');
            btn.classList.toggle('btn-outline-dark');
        };
        window.toggleUnderline = function(btn) {
            textUnderline = !textUnderline;
            btn.classList.toggle('btn-dark');
            btn.classList.toggle('btn-outline-dark');
        };

        // ─── Sync canvas JSON to Livewire before any server call ────────
        window.getLivewireComponent = function() {
            const el = document.querySelector('[wire\\:id]');
            if (!el) return null;
            return Livewire.find(el.getAttribute('wire:id'));
        };

        window.syncCanvasToLivewire = function() {
            if (!canvas) return;
            const lw = window.getLivewireComponent();
            if (!lw) return;
            const json = JSON.stringify(canvas.toJSON(['id', 'selectable', 'evented']));
            lw.set('designDataPayload', json);
        };

        window.syncAndCall = function(method) {
            window.syncCanvasToLivewire();
            const lw = window.getLivewireComponent();
            if (lw) lw.call(method);
        };

        // ─── Initialize Fabric.js ───────────────────────────────────────
        function initCanvas() {
            canvas = new fabric.Canvas('fabricCanvas', {
                width: @json($canvasWidth),
                height: @json($canvasHeight),
                backgroundColor: @json($backgroundColor),
                selection: true,
                preserveObjectStacking: true,
            });

            canvas.on('selection:created', updateSidebarFromSelection);
            canvas.on('selection:updated', updateSidebarFromSelection);
            canvas.on('selection:cleared', function() {});

            // Load existing design
            @if($loadedDesignData)
            try {
                const existingData = {!! $loadedDesignData !!};
                if (existingData && existingData.objects && existingData.objects.length > 0) {
                    canvas.loadFromJSON(existingData, function() {
                        canvas.renderAll();
                    });
                }
            } catch(e) {
                console.error('Failed to load design data:', e);
            }
            @endif
        }

        // ─── Update sidebar inputs from selected object ─────────────────
        function updateSidebarFromSelection() {
            const active = canvas.getActiveObject();
            if (!active) return;
            if (active.type === 'i-text' || active.type === 'text') {
                const el = document.getElementById('inputText');
                if (el) el.value = active.text;
            }
        }

        // ─── Canvas operations (pure JS, no server roundtrip) ───────────
        window.canvasAddText = function() {
            const text = document.getElementById('inputText').value || 'New Text';
            const color = document.getElementById('inputColor').value;
            const font = document.getElementById('inputFont').value;
            const size = parseInt(document.getElementById('inputSize').value) || 24;

            const iText = new fabric.IText(text, {
                left: canvas.width / 2 - 50,
                top: canvas.height / 2 - 20,
                fontFamily: font,
                fontSize: size,
                fill: color,
                fontWeight: textBold ? 'bold' : 'normal',
                fontStyle: textItalic ? 'italic' : 'normal',
                underline: textUnderline,
                editable: true,
                padding: 5,
                id: 'text_' + Date.now(),
            });
            canvas.add(iText);
            canvas.setActiveObject(iText);
            canvas.renderAll();
        };

        window.canvasUpdateText = function() {
            const active = canvas.getActiveObject();
            if (!active || (active.type !== 'i-text' && active.type !== 'text')) return;
            active.set({
                fill: document.getElementById('inputColor').value,
                fontFamily: document.getElementById('inputFont').value,
                fontSize: parseInt(document.getElementById('inputSize').value) || 24,
                fontWeight: textBold ? 'bold' : 'normal',
                fontStyle: textItalic ? 'italic' : 'normal',
                underline: textUnderline,
            });
            canvas.renderAll();
        };

        window.canvasAddImage = function(url) {
            if (!url) return;
            fabric.Image.fromURL(url, function(img) {
                const maxW = canvas.width * 0.6;
                const maxH = canvas.height * 0.6;
                if (img.width > maxW || img.height > maxH) {
                    const scale = Math.min(maxW / img.width, maxH / img.height);
                    img.scale(scale);
                }
                img.set({
                    left: canvas.width / 2 - (img.width * (img.scaleX || 1)) / 2,
                    top: canvas.height / 2 - (img.height * (img.scaleY || 1)) / 2,
                    id: 'image_' + Date.now(),
                });
                canvas.add(img);
                canvas.setActiveObject(img);
                canvas.renderAll();
            }, { crossOrigin: 'anonymous' });
        };

        window.canvasAddLink = function() {
            const linkText = new fabric.IText('Click Here', {
                left: canvas.width / 2 - 50,
                top: canvas.height / 2 - 15,
                fontFamily: 'Arial',
                fontSize: 18,
                fill: '#0066cc',
                underline: true,
                id: 'link_' + Date.now(),
                _linkUrl: 'https://',
            });
            canvas.add(linkText);
            canvas.setActiveObject(linkText);
            canvas.renderAll();
        };

        window.canvasAddQrCode = function() {
            try {
                const qr = qrcode(0, 'M');
                qr.addData('https://cardx.example.com');
                qr.make();
                const qrSvg = qr.createSvgTag(6, 0);
                fabric.loadSVGFromString(qrSvg, function(objects, options) {
                    const qrGroup = fabric.util.groupSVGElements(objects, options);
                    qrGroup.set({
                        left: canvas.width / 2 - 50,
                        top: canvas.height / 2 - 50,
                        id: 'qr_' + Date.now(),
                        _qrUrl: 'https://cardx.example.com',
                    });
                    canvas.add(qrGroup);
                    canvas.setActiveObject(qrGroup);
                    canvas.renderAll();
                });
            } catch(e) {
                console.error('QR generation failed:', e);
            }
        };

        window.canvasDeleteSelected = function() {
            const active = canvas.getActiveObjects();
            if (active.length) {
                active.forEach(obj => canvas.remove(obj));
                canvas.discardActiveObject();
                canvas.renderAll();
            }
        };

        window.canvasDuplicate = function() {
            const active = canvas.getActiveObject();
            if (!active || active.type === 'activeSelection') return;
            active.clone(function(cloned) {
                cloned.set({ left: active.left + 20, top: active.top + 20, id: active.id + '_copy_' + Date.now() });
                canvas.add(cloned);
                canvas.setActiveObject(cloned);
                canvas.renderAll();
            });
        };

        window.canvasBringForward = function() {
            const active = canvas.getActiveObject();
            if (active) { canvas.bringForward(active); canvas.renderAll(); }
        };

        window.canvasSendBackward = function() {
            const active = canvas.getActiveObject();
            if (active) { canvas.sendBackwards(active); canvas.renderAll(); }
        };

        window.canvasResize = function() {
            const w = parseInt(document.getElementById('inputCanvasW').value) || 900;
            const h = parseInt(document.getElementById('inputCanvasH').value) || 600;
            canvas.setWidth(w);
            canvas.setHeight(h);
            canvas.renderAll();
        };

        window.canvasApplyGradient = function() {
            const type = document.getElementById('inputGradientType').value;
            const optsEl = document.getElementById('gradientOptions');
            const angleEl = document.getElementById('gradientAngleWrap');

            optsEl.style.display = type === 'none' ? 'none' : '';
            angleEl.style.display = type === 'linear' ? '' : 'none';

            // Remove existing gradient rect
            const objects = canvas.getObjects();
            const bgRect = objects.find(o => o.id === '__bg_gradient__');
            if (bgRect) canvas.remove(bgRect);

            if (type === 'none') {
                canvas.backgroundColor = document.getElementById('inputBgColor').value;
                canvas.renderAll();
                return;
            }

            const c1 = document.getElementById('inputGradColor1').value;
            const c2 = document.getElementById('inputGradColor2').value;
            const angle = parseInt(document.getElementById('inputGradAngle').value) || 0;

            const coords = { x1: 0, y1: 0, x2: canvas.width, y2: canvas.height };
            if (type === 'linear') {
                const rad = (angle - 90) * Math.PI / 180;
                const cx = canvas.width / 2, cy = canvas.height / 2;
                const len = Math.max(canvas.width, canvas.height);
                coords.x1 = cx - Math.cos(rad) * len;
                coords.y1 = cy - Math.sin(rad) * len;
                coords.x2 = cx + Math.cos(rad) * len;
                coords.y2 = cy + Math.sin(rad) * len;
            }

            const gradient = new fabric.Gradient({
                type: type, coords: coords,
                colorStops: [{ offset: 0, color: c1 }, { offset: 1, color: c2 }],
            });

            const bgGradient = new fabric.Rect({
                id: '__bg_gradient__', left: 0, top: 0,
                width: canvas.width, height: canvas.height,
                fill: gradient, selectable: false, evented: false,
            });
            canvas.backgroundColor = 'transparent';
            canvas.add(bgGradient);
            bgGradient.sendToBack();
            canvas.renderAll();
        };

        // Live gradient update listeners
        document.addEventListener('DOMContentLoaded', function() {
            const gradInputs = ['inputBgColor', 'inputGradColor1', 'inputGradColor2', 'inputGradAngle'];
            gradInputs.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', function() {
                        if (id === 'inputBgColor') {
                            const objects = canvas.getObjects();
                            const bgRect = objects.find(o => o.id === '__bg_gradient__');
                            if (!bgRect) {
                                canvas.backgroundColor = el.value;
                                canvas.renderAll();
                            }
                        } else {
                            window.canvasApplyGradient();
                        }
                        if (id === 'inputGradAngle') {
                            document.getElementById('gradAngleVal').textContent = el.value;
                        }
                    });
                }
            });
        });

        // ─── Keyboard shortcuts ──────────────────────────────────────────
        document.addEventListener('keydown', function(e) {
            if (!canvas) return;
            if (e.target.closest('input, textarea, select')) return;

            if (e.key === 'Delete' || e.key === 'Backspace') {
                e.preventDefault();
                window.canvasDeleteSelected();
            }
            if (e.ctrlKey && e.key === 'd') {
                e.preventDefault();
                window.canvasDuplicate();
            }
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                window.syncAndCall('save');
            }
        });

        // ─── Init ────────────────────────────────────────────────────────
        if (document.readyState !== 'loading') {
            initCanvas();
        } else {
            document.addEventListener('DOMContentLoaded', initCanvas);
        }

        // ─── File upload handlers (pure JS, no server roundtrip) ───────
        window.handleImageFile = function(input) {
            const file = input.files[0];
            if (!file) return;
            if (file.size > 10 * 1024 * 1024) {
                alert('حجم فایل نباید بیشتر از ۱۰ مگابایت باشد.');
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                window.canvasAddImage(e.target.result);
            };
            reader.readAsDataURL(file);
            input.value = '';
        };

        window.handleVideoFile = function(input) {
            const file = input.files[0];
            if (!file) return;
            if (file.size > 50 * 1024 * 1024) {
                alert('حجم فایل نباید بیشتر از ۵۰ مگابایت باشد.');
                input.value = '';
                return;
            }
            const url = URL.createObjectURL(file);
            const bg = new fabric.Rect({ width: 150, height: 100, fill: '#333', rx: 8, ry: 8 });
            const icon = new fabric.Text('\u25B6', { fontSize: 60, fill: '#fff', shadow: new fabric.Shadow({ color: 'rgba(0,0,0,0.5)', blur: 10 }) });
            const label = new fabric.Text(file.name || 'Video', { fontSize: 14, fill: '#fff', fontFamily: 'Arial' });
            const g = new fabric.Group([bg, icon, label], {
                left: canvas.width / 2 - 75, top: canvas.height / 2 - 50,
                id: 'video_' + Date.now(), _videoUrl: url, _isVideo: true,
                _videoFileName: file.name,
            });
            canvas.add(g);
            canvas.setActiveObject(g);
            canvas.renderAll();
            input.value = '';
        };

    })();
    </script>
    @endpush
</div>
