<div>
    <div class="row g-4">
        {{-- Left: Settings Panel --}}
        <div class="col-lg-7">
            <form wire:submit.prevent="save">
                {{-- Basic Info --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="section-header"><i class="bi bi-info-circle me-2"></i>اطلاعات پایه</div>

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-medium">عنوان</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       wire:model.live.debounce.300ms="title" required
                                       placeholder="مثال: کد QR وبسایت">
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">نوع کد</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                               wire:model.live="type" value="static" id="typeStatic">
                                        <label class="form-check-label" for="typeStatic">ثابت</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                               wire:model.live="type" value="dynamic" id="typeDynamic">
                                        <label class="form-check-label" for="typeDynamic">پویا</label>
                                    </div>
                                </div>
                                <small class="text-muted">کد پویا: لینک مقصد قابل تغییر است</small>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-8">
                                <label class="form-label fw-medium">محتوا / لینک</label>
                                <input type="url" class="form-control @error('content') is-invalid @enderror"
                                       wire:model.live.debounce.300ms="content" required
                                       placeholder="https://example.com">
                                @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">مرتبط با کارت</label>
                                <select class="form-select" wire:model.live="cardId">
                                    <option value="">بدون کارت</option>
                                    @foreach($cards as $id => $title)
                                        <option value="{{ $id }}">{{ $title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Templates --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="section-header"><i class="bi bi-palette me-2"></i>قالب‌های آماده</div>
                        <div class="row g-2">
                            @php
                                $templates = [
                                    'business'   => ['name' => 'شرکتی', 'color' => '#1a365d', 'bg' => '#FFFFFF'],
                                    'restaurant' => ['name' => 'رستوران', 'color' => '#c0392b', 'bg' => '#FFF5F5'],
                                    'cafe'       => ['name' => 'کافه', 'color' => '#6F4E37', 'bg' => '#F5F0E8'],
                                    'event'      => ['name' => 'رویداد', 'color' => '#7c3aed', 'bg' => '#F5F3FF'],
                                    'product'    => ['name' => 'محصول', 'color' => '#059669', 'bg' => '#F0FDF4'],
                                    'wedding'    => ['name' => 'عروسی', 'color' => '#b8860b', 'bg' => '#FFFDF5'],
                                    'tech'       => ['name' => 'تکنولوژی', 'color' => '#6366f1', 'bg' => '#FFFFFF'],
                                    'minimal'    => ['name' => 'مینیمال', 'color' => '#000000', 'bg' => '#FFFFFF'],
                                    'colorful'   => ['name' => 'رنگارنگ', 'color' => '#e91e63', 'bg' => '#E3F2FD'],
                                    'dark'       => ['name' => 'تاریک', 'color' => '#FFFFFF', 'bg' => '#1a1a2e'],
                                ];
                            @endphp
                            @foreach($templates as $key => $tpl)
                                <div class="col-4 col-md-3">
                                    <div class="template-card {{ $selectedTemplate === $key ? 'active' : '' }}"
                                         wire:click="selectTemplate('{{ $key }}')" style="cursor:pointer">
                                        <div class="color-preview" style="background: linear-gradient(135deg, {{ $tpl['color'] }}, {{ $tpl['bg'] }});"></div>
                                        <div class="template-name">{{ $tpl['name'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Colors --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="section-header"><i class="bi bi-droplet me-2"></i>رنگ‌ها</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">رنگ پیش‌زمینه</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" class="form-control form-control-color"
                                           wire:model.live="foregroundColor">
                                    <input type="text" class="form-control form-control-sm"
                                           wire:model.live.debounce.300ms="foregroundColor" style="width:100px">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">رنگ پس‌زمینه</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" class="form-control form-control-color"
                                           wire:model.live="backgroundColor">
                                    <input type="text" class="form-control form-control-sm"
                                           wire:model.live.debounce.300ms="backgroundColor" style="width:100px">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">رنگ گرادیان شروع (اختیاری)</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" class="form-control form-control-color"
                                           wire:model.live="gradientFrom">
                                    <input type="text" class="form-control form-control-sm"
                                           wire:model.live.debounce.300ms="gradientFrom"
                                           placeholder="اختیاری" style="width:100px">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">رنگ گرادیان پایان (اختیاری)</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" class="form-control form-control-color"
                                           wire:model.live="gradientTo">
                                    <input type="text" class="form-control form-control-sm"
                                           wire:model.live.debounce.300ms="gradientTo"
                                           placeholder="اختیاری" style="width:100px">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">رنگ چشم‌ها</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" class="form-control form-control-color"
                                           wire:model.live="eyeColor">
                                    <input type="text" class="form-control form-control-sm"
                                           wire:model.live.debounce.300ms="eyeColor" style="width:100px">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Style & Pattern --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="section-header"><i class="bi bi-grid me-2"></i>سبک و الگو</div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-medium">سبک ماژول</label>
                                <div class="d-flex gap-2">
                                    @foreach(['square' => 'مربعی', 'rounded' => 'گرد', 'dots' => 'نقطه‌ای'] as $val => $lbl)
                                        <div class="style-option {{ $style === $val ? 'active' : '' }}"
                                             wire:click="setStyle('{{ $val }}')" style="cursor:pointer">
                                            <div class="style-name">{{ $lbl }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">شکل</label>
                                <div class="d-flex gap-2">
                                    @foreach(['square' => 'مربعی', 'circle' => 'گرد'] as $val => $lbl)
                                        <div class="style-option {{ $shape === $val ? 'active' : '' }}"
                                             wire:click="setShape('{{ $val }}')" style="cursor:pointer">
                                            <div class="style-name">{{ $lbl }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">الگو</label>
                                <select class="form-select" wire:model.live="pattern">
                                    <option value="default">پیش‌فرض</option>
                                    <option value="diamond">الماسی</option>
                                    <option value="circle">دایره‌ای</option>
                                    <option value="star">ستاره‌ای</option>
                                    <option value="heart">قلبی</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Eye Style --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="section-header"><i class="bi bi-eye me-2"></i>سبک چشم‌ها</div>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach(['square' => 'مربعی', 'rounded' => 'گرد', 'dots' => 'نقطه‌ای', 'circle' => 'دایره‌ای'] as $val => $lbl)
                                <div class="style-option {{ $eyeStyle === $val ? 'active' : '' }}"
                                     wire:click="setEyeStyle('{{ $val }}')" style="cursor:pointer">
                                    <div class="style-name">{{ $lbl }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Frame --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="section-header"><i class="bi bi-frame me-2"></i>قاب</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">سبک قاب</label>
                                <div class="d-flex gap-2">
                                    @foreach(['none' => 'بدون قاب', 'box' => 'جعبه', 'circle' => 'دایره', 'bubble' => 'حباب'] as $val => $lbl)
                                        <div class="style-option {{ $frameStyle === $val ? 'active' : '' }}"
                                             wire:click="setFrameStyle('{{ $val }}')" style="cursor:pointer">
                                            <div class="style-name">{{ $lbl }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">رنگ قاب</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" class="form-control form-control-color"
                                           wire:model.live="frameColor">
                                    <input type="text" class="form-control form-control-sm"
                                           wire:model.live.debounce.300ms="frameColor" style="width:100px">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Logo --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="section-header"><i class="bi bi-image me-2"></i>لوگو</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">آپلود لوگو</label>
                                <input type="file" class="form-control" wire:model="logo" accept="image/*">
                                <small class="text-muted">فرمت‌های مجاز: JPG, PNG, SVG (حداکثر 2MB)</small>
                                @if($logoPreview)
                                    <div class="mt-2 d-flex align-items-center gap-2">
                                        <img src="{{ $logoPreview }}" style="max-height:60px;border-radius:0.5rem">
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                wire:click="removeLogo" title="حذف لوگو">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-medium">اندازه لوگو (%)</label>
                                <input type="range" class="form-range"
                                       wire:model.live="logoSize" min="10" max="100">
                                <div class="text-center small text-muted">{{ $logoSize }}%</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-medium">فاصله لوگو</label>
                                <input type="range" class="form-range"
                                       wire:model.live="logoPadding" min="0" max="20">
                                <div class="text-center small text-muted">{{ $logoPadding }}px</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Text --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="section-header"><i class="bi bi-fonts me-2"></i>متن</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">متن (اختیاری)</label>
                                <input type="text" class="form-control"
                                       wire:model.live.debounce.300ms="text"
                                       placeholder="متن زیر/بالای کد QR">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-medium">موقعیت</label>
                                <select class="form-select" wire:model.live="textPosition">
                                    <option value="bottom">پایین</option>
                                    <option value="top">بالا</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-medium">فونت</label>
                                <select class="form-select" wire:model.live="textFont">
                                    <option value="Vazirmatn">وزیرمتن</option>
                                    <option value="Arial">Arial</option>
                                    <option value="Helvetica">Helvetica</option>
                                    <option value="Times New Roman">Times</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-medium">اندازه</label>
                                <input type="range" class="form-range"
                                       wire:model.live="textSize" min="8" max="32">
                                <div class="text-center small text-muted">{{ $textSize }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">رنگ متن</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" class="form-control form-control-color"
                                           wire:model.live="textColor">
                                    <input type="text" class="form-control form-control-sm"
                                           wire:model.live.debounce.300ms="textColor" style="width:100px">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Size & Quality --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="section-header"><i class="bi bi-arrows-angle-expand me-2"></i>اندازه و کیفیت</div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-medium">اندازه (پیکسل)</label>
                                <input type="range" class="form-range"
                                       wire:model.live="size" min="100" max="1000">
                                <div class="text-center small text-muted">{{ $size }}px</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">حاشیه</label>
                                <input type="range" class="form-range"
                                       wire:model.live="margin" min="0" max="50">
                                <div class="text-center small text-muted">{{ $margin }}px</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">تصحیح خطا</label>
                                <select class="form-select" wire:model.live="errorCorrection">
                                    <option value="L">کم (7%)</option>
                                    <option value="M">متوسط (15%)</option>
                                    <option value="Q">زیاد (25%)</option>
                                    <option value="H">خیلی زیاد (30%)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">رزولوشن</label>
                                <input type="range" class="form-range"
                                       wire:model.live="resolution" min="100" max="1000">
                                <div class="text-center small text-muted">{{ $resolution }} DPI</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="d-flex gap-2 mb-4">
                    <button type="submit" class="btn btn-primary btn-lg" wire:loading.attr="disabled">
                        <i class="bi bi-check-lg me-1"></i> ساخت کد QR
                    </button>
                    <a href="{{ route('dashboard.qr.index') }}" class="btn btn-outline-secondary btn-lg">بازگشت</a>
                </div>
            </form>
        </div>

        {{-- Right: Live Preview --}}
        <div class="col-lg-5">
            <div class="card shadow-sm sticky-top" style="top: 1rem;">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0"><i class="bi bi-eye me-2"></i>پیش‌نمایش زنده</h6>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-success"
                                wire:click="downloadPng" wire:loading.attr="disabled">
                            <i class="bi bi-download me-1"></i> PNG
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary"
                                wire:click="downloadSvg" wire:loading.attr="disabled">
                            <i class="bi bi-download me-1"></i> SVG
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Preview --}}
                    <div class="qr-preview-container preview-frame" wire:loading.class="d-none">
                        @if($previewImage)
                            <img src="{{ $previewImage }}" class="qr-preview-img" alt="پیش‌نمایش کد QR">
                        @else
                            <div class="text-center text-muted">
                                <i class="bi bi-qr-code" style="font-size:4rem;opacity:0.3"></i>
                                <p class="mt-2">محتوا را وارد کنید تا پیش‌نمایش نمایش داده شود</p>
                            </div>
                        @endif
                    </div>

                    {{-- Loading spinner --}}
                    <div class="qr-preview-container d-none" wire:loading>
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">در حال بارگذاری...</span>
                            </div>
                            <p class="mt-2 text-muted small">در حال ساخت پیش‌نمایش...</p>
                        </div>
                    </div>

                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            پیش‌نمایش به صورت خودکار بروزرسانی می‌شود
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Toast notification handler --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('show-toast', (data) => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: data[0].type || 'info',
                        title: data[0].message,
                        showConfirmButton: false,
                        timer: 3000,
                    });
                }
            });
        });
    </script>
</div>

@push('styles')
<style>
    .qr-preview-container {
        background: #f8f9fa;
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed #dee2e6;
        transition: all 0.3s ease;
    }
    .qr-preview-container:hover {
        border-color: #0d6efd;
    }
    .qr-preview-img {
        max-width: 100%;
        max-height: 350px;
        border-radius: 0.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .template-card {
        cursor: pointer;
        border: 2px solid #dee2e6;
        border-radius: 0.75rem;
        padding: 0.75rem;
        text-align: center;
        transition: all 0.2s ease;
        height: 100%;
    }
    .template-card:hover {
        border-color: #0d6efd;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .template-card.active {
        border-color: #0d6efd;
        background: #e7f1ff;
    }
    .template-card .color-preview {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin: 0 auto 0.5rem;
        border: 2px solid #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .template-card .template-name {
        font-size: 0.8rem;
        font-weight: 500;
    }
    .style-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0.75rem;
        border: 2px solid #dee2e6;
        border-radius: 0.75rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .style-option:hover {
        border-color: #0d6efd;
    }
    .style-option.active {
        border-color: #0d6efd;
        background: #e7f1ff;
    }
    .style-option .style-name {
        font-size: 0.75rem;
        font-weight: 500;
    }
    .section-header {
        font-size: 0.9rem;
        font-weight: 600;
        color: #495057;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
        border-bottom: 2px solid #e9ecef;
    }
    .preview-frame {
        background: repeating-conic-gradient(#f0f0f0 0% 25%, transparent 0% 50%) 50% / 20px 20px;
        border-radius: 0.5rem;
    }
</style>
@endpush
