<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $card->title }} | CardX</title>
    <meta name="description" content="{{ $card->description ?? $card->title }}">
    <meta property="og:title" content="{{ $card->title }}">
    <meta property="og:description" content="{{ $card->description ?? $card->title }}">
    <meta property="og:type" content="website">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body.embed-mode {
            background: {{ $card->settings['background_color'] ?? '#ffffff' }};
            overflow: hidden;
            width: {{ $card->canvas_width }}px;
            height: {{ $card->canvas_height }}px;
        }
        body.public-mode {
            font-family: 'Vazirmatn', sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            overflow: hidden;
            max-width: 100%;
        }
        .card-canvas-wrapper { display: flex; align-items: center; justify-content: center; overflow: auto; }
        .card-title { padding: 16px 20px; font-size: 18px; font-weight: 600; text-align: center; color: #333; border-top: 1px solid #eee; }
        .card-branding { padding: 12px 20px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #f0f0f0; }
        .card-branding a { color: #0d6efd; text-decoration: none; }
    </style>
</head>
<body class="{{ request('embed') ? 'embed-mode' : 'public-mode' }}">
    @if(!request('embed'))
    <div class="card-container">
        <div class="card-canvas-wrapper">
    @endif
            <canvas id="publicCanvas" width="{{ $card->canvas_width }}" height="{{ $card->canvas_height }}"></canvas>
    @if(!request('embed'))
        </div>
        @if($card->title)
            <div class="card-title">{{ $card->title }}</div>
        @endif
        <div class="card-branding">
            ساخته شده با <a href="{{ route('home') }}">CardX</a>
        </div>
    </div>
    @endif

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
    <script>
    (function() {
        var canvas = new fabric.Canvas('publicCanvas', {
            width: {{ $card->canvas_width }},
            height: {{ $card->canvas_height }},
            backgroundColor: '{{ $card->settings["background_color"] ?? "#ffffff" }}',
            selection: false,
            readOnly: true,
        });
        @if($card->design_data && isset($card->design_data['objects']))
            canvas.loadFromJSON(@json($card->design_data), function() {
                canvas.forEachObject(function(obj) { obj.selectable = false; obj.evented = false; });
                canvas.renderAll();
            });
        @endif
    })();
    </script>
</body>
</html>
