@props(['type' => 'info', 'dismissible' => true])

@php
    $typeClasses = match($type) {
        'success' => 'alert-success',
        'error', 'danger' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info',
        default => 'alert-info',
    };
@endphp

<div class="alert {{ $typeClasses }} {{ $dismissible ? 'alert-dismissible fade show' : '' }}" role="alert">
    {{ $slot }}
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="بستن"></button>
    @endif
</div>
