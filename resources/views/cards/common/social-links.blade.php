@props(['links' => [], 'themeColor' => '#667eea', 'style' => 'icons'])

@php
    $colors = [
        'instagram' => '#E4405F', 'telegram' => '#0088CC', 'whatsapp' => '#25D366',
        'twitter' => '#1DA1F2', 'linkedin' => '#0A66C2', 'youtube' => '#FF0000',
        'facebook' => '#1877F2', 'pinterest' => '#BD081C', 'tiktok' => '#000000',
        'aparat' => '#ED8B00', 'rubika' => '#FF4081', 'bale' => '#FF6B00',
    ];
    $icons = [
        'instagram' => 'instagram', 'telegram' => 'telegram', 'whatsapp' => 'whatsapp',
        'twitter' => 'twitter-x', 'linkedin' => 'linkedin', 'youtube' => 'youtube',
        'facebook' => 'facebook', 'pinterest' => 'pinterest', 'tiktok' => 'tiktok',
        'aparat' => 'play-circle', 'rubika' => 'chat-dots', 'bale' => 'chat-left-text',
    ];
@endphp

@if($style === 'icons')
    <div class="d-flex flex-wrap justify-content-center gap-2">
        @foreach($links->sortBy('sort_order') as $link)
            <a href="{{ $link->url }}" class="social-icon-circle" style="background:{{ $colors[$link->platform] ?? '#6c757d' }}" target="_blank" title="{{ $link->platform }}">
                <i class="bi bi-{{ $icons[$link->platform] ?? 'globe' }}"></i>
            </a>
        @endforeach
    </div>
@elseif($style === 'buttons')
    <div class="d-flex flex-wrap justify-content-center gap-2">
        @foreach($links->sortBy('sort_order') as $link)
            <a href="{{ $link->url }}" class="social-btn" style="background:{{ $colors[$link->platform] ?? '#6c757d' }}" target="_blank">
                <i class="bi bi-{{ $icons[$link->platform] ?? 'globe' }}"></i> {{ ucfirst($link->platform) }}
            </a>
        @endforeach
    </div>
@endif
