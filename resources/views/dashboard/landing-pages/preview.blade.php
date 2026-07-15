<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->title }} - پیش‌نمایش</title>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {!! $fontLinks ?? '' !!}
    @if($page->custom_css)
        <style>{!! $page->custom_css !!}</style>
    @endif
    <style>*{font-family:'Vazirmatn',system-ui,sans-serif}body{margin:0;padding:0}img{max-width:100%;height:auto}</style>
</head>
<body>
    <div id="previewContent">
        {!! $html !!}
    </div>
    @if($page->custom_js)
        <script>{!! $page->custom_js !!}</script>
    @endif
</body>
</html>
