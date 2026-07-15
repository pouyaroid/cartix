@extends('layouts.dashboard')

@section('title', 'ویرایشگر: ' . $card->title)

@push('styles')
<style>
    .builder-section-item { transition: all 0.2s ease; border: 1px solid transparent !important; }
    .builder-section-item:hover { border-color: var(--bs-primary) !important; background: var(--bs-primary-bg-subtle); }
    .builder-section-item .section-actions { opacity: 0; transition: opacity 0.2s; }
    .builder-section-item:hover .section-actions { opacity: 1; }
    .block-type-option { transition: all 0.2s ease; border: 2px solid var(--bs-border-color) !important; }
    .block-type-option:hover { border-color: var(--bs-primary) !important; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .block-type-option.border-primary { border-color: var(--bs-primary) !important; background: var(--bs-primary-bg-subtle); }
    .preview-toolbar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 0.75rem; padding: 0.5rem; }
    .preview-toolbar .btn { border: none; color: rgba(255,255,255,0.7); }
    .preview-toolbar .btn.active, .preview-toolbar .btn:hover { color: #fff; background: rgba(255,255,255,0.2); }
    .color-presets { display: flex; gap: 0.4rem; flex-wrap: wrap; margin-top: 0.5rem; }
    .color-preset { width: 28px; height: 28px; border-radius: 50%; border: 2px solid transparent; cursor: pointer; transition: all 0.2s; }
    .color-preset:hover { transform: scale(1.15); }
    .color-preset.active { border-color: #333; box-shadow: 0 0 0 2px #fff; }
    .image-upload-zone { border: 2px dashed var(--bs-border-color); border-radius: 0.75rem; padding: 1rem; text-align: center; cursor: pointer; transition: all 0.2s; }
    .image-upload-zone:hover { border-color: var(--bs-primary); background: var(--bs-primary-bg-subtle); }
    .image-upload-zone input[type="file"] { display: none; }
    .item-card { background: var(--bs-body-bg); border: 1px solid var(--bs-border-color); border-radius: 0.5rem; padding: 0.75rem; margin-bottom: 0.5rem; }
</style>
@endpush

@php
    $typeConfig = \App\Config\CardTypeConfig::get($card->type);
    $blockTypes = \App\Config\CardTypeConfig::getBlockTypes();
    $allowedBlocks = $typeConfig['allowed_blocks'];
@endphp

@section('content')
<div class="row g-3">
    {{-- Left: Builder Panel --}}
    <div class="col-lg-4">
        {{-- Card Type Badge --}}
        <div class="card shadow-sm mb-3">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi {{ $typeConfig['icon'] }}" style="color:{{ $typeConfig['theme_color'] }}"></i>
                        <span class="fw-bold small">{{ $typeConfig['label'] }}</span>
                    </div>
                    <a href="{{ route('dashboard.cards.edit', $card) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-pencil" style="font-size:0.7rem"></i> تنظیمات
                    </a>
                </div>
            </div>
        </div>

        {{-- Sections --}}
        <div class="card shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">بخش‌ها</h6>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addSectionModal">
                    <i class="bi bi-plus"></i> افزودن
                </button>
            </div>
            <div class="card-body p-2">
                <div id="sections-list" class="list-group list-group-flush">
                    @foreach($card->sections->sortBy('sort_order') as $section)
                        @php $bt = $blockTypes[$section->type] ?? ['label' => $section->type, 'icon' => 'bi-puzzle']; @endphp
                        <div class="list-group-item list-group-item-action section-item builder-section-item" data-id="{{ $section->id }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-grip-vertical text-muted" style="cursor:move;opacity:0.5"></i>
                                    <div class="rounded d-flex align-items-center justify-content-center" style="width:32px;height:32px;background:{{ $typeConfig['theme_color'] }}15;color:{{ $typeConfig['theme_color'] }}">
                                        <i class="bi {{ $bt['icon'] }}" style="font-size:0.85rem"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium small">{{ $section->title ?: $bt['label'] }}</div>
                                        <div class="text-muted" style="font-size:0.7rem">{{ $bt['label'] }} @if(!$section->is_visible) <span class="badge bg-secondary" style="font-size:0.6rem">مخفی</span> @endif</div>
                                    </div>
                                </div>
                                <div class="d-flex gap-1 section-actions">
                                    <button class="btn btn-sm btn-outline-primary edit-section-btn" data-section-id="{{ $section->id }}" data-title="{{ $section->title }}" data-content="{{ $section->content }}" data-type="{{ $section->type }}" title="ویرایش">
                                        <i class="bi bi-pencil" style="font-size:0.7rem"></i>
                                    </button>
                                    @if($bt['has_items'] ?? false)
                                        <button class="btn btn-sm btn-outline-success manage-items-btn" data-section-id="{{ $section->id }}" data-type="{{ $section->type }}" data-title="{{ $bt['label'] }}" title="مدیریت آیتم‌ها">
                                            <i class="bi bi-list" style="font-size:0.7rem"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-sm btn-outline-warning toggle-section-btn" data-section-id="{{ $section->id }}" title="{{ $section->is_visible ? 'مخفی کردن' : 'نمایش' }}">
                                        <i class="bi bi-{{ $section->is_visible ? 'eye' : 'eye-slash' }}" style="font-size:0.7rem"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger delete-section-btn" data-section-id="{{ $section->id }}" title="حذف">
                                        <i class="bi bi-trash" style="font-size:0.7rem"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($card->sections->isEmpty())
                    <div class="text-center text-muted py-4 small">
                        <i class="bi bi-puzzle fs-3 d-block mb-2"></i>
                        بخشی اضافه نشده است
                    </div>
                @endif
            </div>
        </div>

        {{-- Theme Settings --}}
        <div class="card shadow-sm mt-3">
            <div class="card-header bg-transparent">
                <h6 class="fw-bold mb-0">ظاهر کارت</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label small fw-medium">رنگ اصلی</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="color" class="form-control form-control-color" id="themeColor" value="{{ $card->theme_color ?: $typeConfig['theme_color'] }}">
                        <input type="text" class="form-control form-control-sm" id="themeColorText" value="{{ $card->theme_color ?: $typeConfig['theme_color'] }}" style="width:100px">
                    </div>
                    <div class="color-presets">
                        @foreach(['#1a365d','#c0392b','#0077b6','#7c3aed','#6366f1','#059669','#d97706','#dc2626','#0891b2','#4f46e5'] as $color)
                            <div class="color-preset {{ ($card->theme_color ?: $typeConfig['theme_color']) === $color ? 'active' : '' }}" style="background:{{ $color }}" data-color="{{ $color }}"></div>
                        @endforeach
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-medium">فونت</label>
                    <select class="form-select form-select-sm" id="fontFamily">
                        <option value="Vazirmatn" {{ ($card->font_family ?? 'Vazirmatn') === 'Vazirmatn' ? 'selected' : '' }}>Vazirmatn</option>
                        <option value="IranSansX" {{ ($card->font_family ?? '') === 'IranSansX' ? 'selected' : '' }}>IranSansX</option>
                        <option value="Dana" {{ ($card->font_family ?? '') === 'Dana' ? 'selected' : '' }}>Dana</option>
                        <option value="Peyda" {{ ($card->font_family ?? '') === 'Peyda' ? 'selected' : '' }}>Peyda</option>
                        <option value="Shabnam" {{ ($card->font_family ?? '') === 'Shabnam' ? 'selected' : '' }}>Shabnam</option>
                    </select>
                </div>
                <button class="btn btn-primary btn-sm w-100" id="saveThemeBtn">
                    <i class="bi bi-check-lg ms-1"></i> ذخیره ظاهر
                </button>
            </div>
        </div>

        {{-- Card Settings --}}
        <div class="card shadow-sm mt-3">
            <div class="card-header bg-transparent">
                <h6 class="fw-bold mb-0">تنظیمات کارت</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('dashboard.cards.update', $card) }}">
                    @csrf @method('PUT')
                    <div class="mb-2">
                        <label class="form-label small">عنوان</label>
                        <input type="text" class="form-control form-control-sm" name="title" value="{{ $card->title }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">تلفن</label>
                        <input type="text" class="form-control form-control-sm" name="phone" value="{{ $card->phone }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">ایمیل</label>
                        <input type="email" class="form-control form-control-sm" name="email" value="{{ $card->email }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">وبسایت</label>
                        <input type="url" class="form-control form-control-sm" name="website" value="{{ $card->website }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">آدرس</label>
                        <textarea class="form-control form-control-sm" name="address" rows="2">{{ $card->address }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">ذخیره</button>
                </form>
            </div>
        </div>

        {{-- Media --}}
        <div class="card shadow-sm mt-3">
            <div class="card-header bg-transparent">
                <h6 class="fw-bold mb-0">تصاویر</h6>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-4">
                        <label class="form-label small fw-medium">لوگو</label>
                        <div class="image-upload-zone" onclick="document.getElementById('logoInput').click()">
                            <input type="file" id="logoInput" accept="image/*">
                            @if($card->logo)
                                <img src="{{ asset('storage/' . $card->logo) }}" style="width:100%;height:60px;object-fit:contain;border-radius:0.5rem">
                            @else
                                <i class="bi bi-image text-muted fs-4"></i>
                                <div class="text-muted small mt-1">لوگو</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-4">
                        <label class="form-label small fw-medium">پروفایل</label>
                        <div class="image-upload-zone" onclick="document.getElementById('profileInput').click()">
                            <input type="file" id="profileInput" accept="image/*">
                            @if($card->profile_image)
                                <img src="{{ asset('storage/' . $card->profile_image) }}" style="width:60px;height:60px;object-fit:cover;border-radius:50%;margin:0 auto">
                            @else
                                <i class="bi bi-person-circle text-muted fs-4"></i>
                                <div class="text-muted small mt-1">پروفایل</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-4">
                        <label class="form-label small fw-medium">کاور</label>
                        <div class="image-upload-zone" onclick="document.getElementById('coverInput').click()">
                            <input type="file" id="coverInput" accept="image/*">
                            @if($card->cover_image)
                                <img src="{{ asset('storage/' . $card->cover_image) }}" style="width:100%;height:60px;object-fit:cover;border-radius:0.5rem">
                            @else
                                <i class="bi bi-card-image text-muted fs-4"></i>
                                <div class="text-muted small mt-1">کاور</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Live Preview --}}
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-eye ms-1" style="color:{{ $typeConfig['theme_color'] }}"></i> پیش‌نمایش زنده</h6>
                <div class="d-flex gap-2 align-items-center">
                    <div class="preview-toolbar d-flex gap-1">
                        <button class="btn btn-sm preview-mode active" data-mode="desktop" title="دسکتاپ"><i class="bi bi-display"></i></button>
                        <button class="btn btn-sm preview-mode" data-mode="tablet" title="تبلت"><i class="bi bi-tablet"></i></button>
                        <button class="btn btn-sm preview-mode" data-mode="mobile" title="موبایل"><i class="bi bi-phone"></i></button>
                    </div>
                    @if($card->is_published)
                        <a href="/{{ $card->slug }}" class="btn btn-sm btn-outline-success" target="_blank"><i class="bi bi-box-arrow-up-left ms-1"></i> مشاهده</a>
                    @endif
                    <form action="{{ route('dashboard.cards.publish', $card) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-{{ $card->is_published ? 'outline-warning' : 'success' }}">
                            <i class="bi {{ $card->is_published ? 'bi-eye-slash' : 'bi-send' }} ms-1"></i>
                            {{ $card->is_published ? 'پیش‌نویس' : 'انتشار' }}
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="d-flex justify-content-center p-3" style="background:linear-gradient(135deg,#f5f7fa,#c3cfe2);min-height:600px;overflow:auto">
                    <div id="previewDeviceFrame" style="width:100%;max-width:100%;transition:width 0.3s ease">
                        <div style="background:white;border-radius:1rem;box-shadow:0 8px 32px rgba(0,0,0,0.12);overflow:hidden;min-height:600px">
                            <iframe id="preview-frame" src="{{ route('dashboard.cards.preview', $card) }}" style="width:100%;min-height:600px;border:none" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add Section Modal --}}
<div class="modal fade" id="addSectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold">افزودن بخش جدید</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-medium">نوع بخش</label>
                    <div class="row g-2" id="blockTypeGrid">
                        @foreach($blockTypes as $key => $bt)
                            @if(in_array($key, $allowedBlocks))
                                <div class="col-6">
                                    <label class="card card-body text-center p-2 border block-type-option {{ in_array($key, $typeConfig['default_blocks']) ? 'border-primary bg-primary-subtle' : '' }}" style="cursor:pointer" data-type="{{ $key }}">
                                        <input type="radio" name="section_type" value="{{ $key }}" class="d-none" {{ in_array($key, $typeConfig['default_blocks']) ? 'checked' : '' }}>
                                        <i class="bi {{ $bt['icon'] }} fs-4 d-block mb-1" style="color:{{ $typeConfig['theme_color'] }}"></i>
                                        <div class="small fw-medium">{{ $bt['label'] }}</div>
                                    </label>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">عنوان بخش</label>
                    <input type="text" class="form-control" id="section-title" placeholder="اختیاری">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">لغو</button>
                <button type="button" class="btn btn-primary btn-sm" id="addSectionBtn">افزودن</button>
            </div>
        </div>
    </div>
</div>

{{-- Edit Section Modal --}}
<div class="modal fade" id="editSectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold">ویرایش بخش</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editSectionId">
                <div class="mb-3">
                    <label class="form-label fw-medium">عنوان</label>
                    <input type="text" class="form-control" id="editSectionTitle">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">محتوا</label>
                    <textarea class="form-control" id="editSectionContent" rows="4"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">لغو</button>
                <button type="button" class="btn btn-primary btn-sm" id="saveSectionBtn">ذخیره</button>
            </div>
        </div>
    </div>
</div>

{{-- Manage Items Modal --}}
<div class="modal fade" id="manageItemsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold" id="manageItemsTitle">مدیریت آیتم‌ها</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="manageItemsSectionId">
                <input type="hidden" id="manageItemsType">
                <div id="itemsList" class="list-group mb-3"></div>
                <div id="addItemForm">
                    <h6 class="fw-bold small mb-2">افزودن آیتم جدید</h6>
                    <div id="itemFields"></div>
                    <button class="btn btn-primary btn-sm mt-2" id="addItemBtn">
                        <i class="bi bi-plus ms-1"></i> افزودن
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const cardId = {{ $card->id }};
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const apiHeaders = { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' };

function refreshPreview() {
    document.getElementById('preview-frame').src = document.getElementById('preview-frame').src;
}

// Block type selection
document.querySelectorAll('.block-type-option').forEach(opt => {
    opt.addEventListener('click', function() {
        document.querySelectorAll('.block-type-option').forEach(o => o.classList.remove('border-primary', 'bg-primary-subtle'));
        this.classList.add('border-primary', 'bg-primary-subtle');
        this.querySelector('input').checked = true;
    });
});

// Add Section
document.getElementById('addSectionBtn').addEventListener('click', function() {
    const type = document.querySelector('input[name="section_type"]:checked')?.value;
    const title = document.getElementById('section-title').value;
    if (!type) { alert('نوع بخش را انتخاب کنید'); return; }

    fetch(`/dashboard/cards/${cardId}/sections`, {
        method: 'POST', headers: apiHeaders,
        body: JSON.stringify({ type, title })
    }).then(r => r.json()).then(data => {
        if (data.success) location.reload();
    });
});

// Edit Section
document.querySelectorAll('.edit-section-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('editSectionId').value = this.dataset.sectionId;
        document.getElementById('editSectionTitle').value = this.dataset.title || '';
        document.getElementById('editSectionContent').value = this.dataset.content || '';
        new bootstrap.Modal(document.getElementById('editSectionModal')).show();
    });
});

document.getElementById('saveSectionBtn').addEventListener('click', function() {
    const id = document.getElementById('editSectionId').value;
    fetch(`/dashboard/cards/${cardId}/sections/${id}`, {
        method: 'PUT', headers: apiHeaders,
        body: JSON.stringify({
            title: document.getElementById('editSectionTitle').value,
            content: document.getElementById('editSectionContent').value
        })
    }).then(r => r.json()).then(data => {
        if (data.success) { location.reload(); }
    });
});

// Toggle Section Visibility
document.querySelectorAll('.toggle-section-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.sectionId;
        fetch(`/dashboard/cards/${cardId}/sections/${id}`, {
            method: 'PUT', headers: apiHeaders,
            body: JSON.stringify({ is_visible: false })
        }).then(r => r.json()).then(data => {
            if (data.success) location.reload();
        });
    });
});

// Delete Section
document.querySelectorAll('.delete-section-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if (!confirm('آیا از حذف این بخش مطمئنید؟')) return;
        const id = this.dataset.sectionId;
        fetch(`/dashboard/cards/${cardId}/sections/${id}`, {
            method: 'DELETE', headers: apiHeaders
        }).then(r => r.json()).then(data => {
            if (data.success) location.reload();
        });
    });
});

// Manage Items
document.querySelectorAll('.manage-items-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const sectionId = this.dataset.sectionId;
        const type = this.dataset.type;
        document.getElementById('manageItemsSectionId').value = sectionId;
        document.getElementById('manageItemsType').value = type;
        document.getElementById('manageItemsTitle').textContent = 'مدیریت ' + this.dataset.title;
        loadItems(sectionId, type);
        new bootstrap.Modal(document.getElementById('manageItemsModal')).show();
    });
});

function loadItems(sectionId, type) {
    const fields = getItemFields(type);
    document.getElementById('itemFields').innerHTML = fields.map(f =>
        f.type === 'textarea'
            ? `<div class="mb-2"><label class="form-label small">${f.label}</label><textarea class="form-control form-control-sm item-field" data-field="${f.key}" rows="2" placeholder="${f.placeholder || ''}"></textarea></div>`
            : `<div class="mb-2"><label class="form-label small">${f.label}</label><input type="${f.type}" class="form-control form-control-sm item-field" data-field="${f.key}" placeholder="${f.placeholder || ''}"></div>`
    ).join('');

    // Load existing items
    fetch(`/dashboard/cards/${cardId}/sections/${sectionId}/items/list`, {
        method: 'GET', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
    }).then(r => r.json()).then(data => {
        const list = document.getElementById('itemsList');
        if (data.items && data.items.length > 0) {
            list.innerHTML = data.items.map(item => {
                const label = item.name || item.question || item.author_name || item.caption || 'آیتم';
                return `<div class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="small">${label}</span>
                    <button class="btn btn-sm btn-outline-danger delete-item-btn" data-item-id="${item.id}" data-section-id="${sectionId}" data-type="${type}"><i class="bi bi-trash" style="font-size:0.7rem"></i></button>
                </div>`;
            }).join('');

            list.querySelectorAll('.delete-item-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    fetch(`/dashboard/cards/${cardId}/sections/${this.dataset.sectionId}/items/${this.dataset.itemId}`, {
                        method: 'DELETE', headers: apiHeaders
                    }).then(r => r.json()).then(data => {
                        if (data.success) loadItems(sectionId, type);
                    });
                });
            });
        } else {
            list.innerHTML = '<div class="text-muted small text-center py-2">آیتمی وجود ندارد</div>';
        }
    }).catch(() => {
        document.getElementById('itemsList').innerHTML = '<div class="text-muted small text-center py-2">آیتمی وجود ندارد</div>';
    });
}

function getItemFields(type) {
    const fields = {
        services: [
            { key: 'name', label: 'نام', type: 'text', placeholder: 'نام خدمت' },
            { key: 'description', label: 'توضیحات', type: 'textarea', placeholder: 'توضیحات خدمت' },
            { key: 'icon', label: 'آیکون (Bootstrap)', type: 'text', placeholder: 'check-circle' }
        ],
        products: [
            { key: 'name', label: 'نام', type: 'text', placeholder: 'نام محصول' },
            { key: 'description', label: 'توضیحات', type: 'textarea', placeholder: 'توضیحات محصول' },
            { key: 'price', label: 'قیمت (تومان)', type: 'number', placeholder: '0' }
        ],
        testimonials: [
            { key: 'author_name', label: 'نام نویسنده', type: 'text', placeholder: 'نام' },
            { key: 'content', label: 'متن نظر', type: 'textarea', placeholder: 'نظر مشتری' },
            { key: 'rating', label: 'امتیاز (۱ تا ۵)', type: 'number', placeholder: '5' }
        ],
        faq: [
            { key: 'question', label: 'سوال', type: 'text', placeholder: 'سوال متداول' },
            { key: 'answer', label: 'پاسخ', type: 'textarea', placeholder: 'پاسخ' }
        ],
        gallery: [
            { key: 'image_path', label: 'مسیر تصویر', type: 'text', placeholder: 'uploads/image.jpg' },
            { key: 'caption', label: 'عنوان', type: 'text', placeholder: 'عنوان تصویر' }
        ],
        timeline: [
            { key: 'name', label: 'عنوان', type: 'text', placeholder: 'عنوان رویداد' },
            { key: 'description', label: 'توضیحات', type: 'textarea', placeholder: 'توضیحات' }
        ]
    };
    return fields[type] || fields.services;
}

document.getElementById('addItemBtn').addEventListener('click', function() {
    const sectionId = document.getElementById('manageItemsSectionId').value;
    const type = document.getElementById('manageItemsType').value;
    const data = {};
    document.querySelectorAll('.item-field').forEach(f => { data[f.dataset.field] = f.value; });

    fetch(`/dashboard/cards/${cardId}/sections/${sectionId}/items`, {
        method: 'POST', headers: apiHeaders,
        body: JSON.stringify({ type, ...data })
    }).then(r => r.json()).then(data => {
        if (data.success) {
            loadItems(sectionId, type);
            refreshPreview();
        }
    });
});

// Theme
document.getElementById('themeColor').addEventListener('input', function() {
    document.getElementById('themeColorText').value = this.value;
    document.querySelectorAll('.color-preset').forEach(p => p.classList.remove('active'));
});
document.getElementById('themeColorText').addEventListener('input', function() {
    document.getElementById('themeColor').value = this.value;
});

document.querySelectorAll('.color-preset').forEach(preset => {
    preset.addEventListener('click', function() {
        const color = this.dataset.color;
        document.getElementById('themeColor').value = color;
        document.getElementById('themeColorText').value = color;
        document.querySelectorAll('.color-preset').forEach(p => p.classList.remove('active'));
        this.classList.add('active');
    });
});

document.getElementById('saveThemeBtn').addEventListener('click', function() {
    fetch(`/dashboard/cards/${cardId}`, {
        method: 'PUT', headers: apiHeaders,
        body: JSON.stringify({
            theme_color: document.getElementById('themeColor').value,
            font_family: document.getElementById('fontFamily').value
        })
    }).then(r => r.json()).then(data => {
        if (data.success) refreshPreview();
    });
});

// Image Uploads
['logo', 'profile', 'cover'].forEach(field => {
    const input = document.getElementById(field + 'Input');
    if (input) {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            const fd = new FormData();
            fd.append('file', file);
            fd.append('_token', csrfToken);
            fetch(`/dashboard/cards/${cardId}/upload/${field}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: fd
            }).then(r => r.json()).then(data => {
                if (data.success) refreshPreview();
            });
        });
    }
});

// Preview Mode
document.querySelectorAll('.preview-mode').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.preview-mode').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const frame = document.getElementById('previewDeviceFrame');
        const mode = this.dataset.mode;
        frame.style.width = mode === 'mobile' ? '375px' : mode === 'tablet' ? '768px' : '100%';
    });
});

// SortableJS
if (typeof Sortable !== 'undefined') {
    Sortable.create(document.getElementById('sections-list'), {
        handle: '.bi-grip-vertical',
        animation: 150,
        onEnd: function() {
            const order = Array.from(document.querySelectorAll('.section-item')).map(el => el.dataset.id);
            fetch(`/dashboard/cards/${cardId}/sections/reorder`, {
                method: 'POST', headers: apiHeaders,
                body: JSON.stringify({ order })
            });
        }
    });
}
</script>
@endpush
@endsection
