@props(['icon' => 'fa-inbox', 'title' => 'داده‌ای وجود ندارد', 'description' => null, 'actionUrl' => null, 'actionLabel' => null])

<div class="text-center py-5">
    <div class="mb-3">
        <i class="fas {{ $icon }} fa-3x text-muted"></i>
    </div>
    <h5 class="text-muted">{{ $title }}</h5>
    @if($description)
        <p class="text-muted">{{ $description }}</p>
    @endif
    {{ $slot }}
    @if($actionUrl && $actionLabel)
        <a href="{{ $actionUrl }}" class="btn btn-primary mt-3">
            <i class="fas fa-plus me-1"></i> {{ $actionLabel }}
        </a>
    @endif
</div>
