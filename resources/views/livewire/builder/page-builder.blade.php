<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center gap-2">
            <span class="fw-bold">{{ $page->title }}</span>
            <span class="badge bg-{{ $page->isPublished() ? 'success' : 'secondary' }}" style="font-size:0.7rem">
                {{ $page->isPublished() ? 'منتشر شده' : 'پیش‌نویس' }}
            </span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-secondary {{ $responsiveMode === 'desktop' ? 'active' : '' }}"
                        wire:click="setResponsiveMode('desktop')">
                    <i class="bi bi-display"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary {{ $responsiveMode === 'tablet' ? 'active' : '' }}"
                        wire:click="setResponsiveMode('tablet')">
                    <i class="bi bi-tablet"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary {{ $responsiveMode === 'mobile' ? 'active' : '' }}"
                        wire:click="setResponsiveMode('mobile')">
                    <i class="bi bi-phone"></i>
                </button>
            </div>

            <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="save">
                <i class="bi bi-save"></i>
            </button>

            <form action="{{ route('dashboard.landing-pages.publish', $page) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-{{ $page->isPublished() ? 'outline-warning' : 'success' }}">
                    {{ $page->isPublished() ? 'پیش‌نویس' : 'انتشار' }}
                </button>
            </form>
        </div>
    </div>
</div>
