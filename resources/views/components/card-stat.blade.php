@props(['title', 'value', 'icon' => null, 'color' => 'primary', 'change' => null, 'changeType' => null])

@php
    $colorClasses = match($color) {
        'primary' => 'bg-primary bg-opacity-10 text-primary',
        'success' => 'bg-success bg-opacity-10 text-success',
        'warning' => 'bg-warning bg-opacity-10 text-warning',
        'danger' => 'bg-danger bg-opacity-10 text-danger',
        'info' => 'bg-info bg-opacity-10 text-info',
        'purple' => 'bg-purple bg-opacity-10 text-purple',
        default => 'bg-primary bg-opacity-10 text-primary',
    };
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <p class="text-muted mb-1 fs-7">{{ $title }}</p>
                <h3 class="mb-0 fw-bold">{{ $value }}</h3>
                @if($change !== null)
                    <small class="text-{{ $changeType === 'up' ? 'success' : ($changeType === 'down' ? 'danger' : 'muted') }}">
                        @if($changeType === 'up')
                            <i class="fas fa-arrow-up"></i>
                        @elseif($changeType === 'down')
                            <i class="fas fa-arrow-down"></i>
                        @endif
                        {{ $change }}
                    </small>
                @endif
            </div>
            @if($icon)
                <div class="{{ $colorClasses }} rounded-3 p-3">
                    <i class="fas {{ $icon }} fa-2x"></i>
                </div>
            @endif
        </div>
    </div>
</div>
