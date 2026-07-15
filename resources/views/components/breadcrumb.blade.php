@props(['items' => []])

@if(count($items) > 0)
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none"><i class="fas fa-home"></i></a></li>
            @foreach($items as $index => $item)
                @if($index === count($items) - 1)
                    <li class="breadcrumb-item active" aria-current="page">{{ $item['label'] }}</li>
                @else
                    <li class="breadcrumb-item"><a href="{{ $item['url'] ?? '#' }}" class="text-decoration-none">{{ $item['label'] }}</a></li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
