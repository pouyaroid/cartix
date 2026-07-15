<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- SEO --}}
    <title>{{ $page->seo_title ?: $page->title }}</title>
    @if($page->seo_description)
        <meta name="description" content="{{ $page->seo_description }}">
    @endif
    @if($page->og_image)
        <meta property="og:image" content="{{ $page->og_image }}">
        <meta property="og:title" content="{{ $page->seo_title ?: $page->title }}">
        <meta property="og:description" content="{{ $page->seo_description }}">
    @endif
    <link rel="canonical" href="{{ $page->getPublicUrlAttribute() }}">
    @if($page->favicon)
        <link rel="icon" href="{{ $page->favicon }}">
    @endif

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    {!! $fontLinks ?? '' !!}

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- Styles --}}
    <style>
        * { font-family: 'Vazirmatn', sans-serif; }
        body { margin: 0; padding: 0; }
        img { max-width: 100%; height: auto; }
    </style>

    @if($page->custom_css)
        <style>{!! $page->custom_css !!}</style>
    @endif

    {{-- Structured Data --}}
    @if($page->seo_title)
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebPage",
            "name": "{{ $page->seo_title }}",
            "description": "{{ $page->seo_description }}",
            "url": "{{ $page->getPublicUrlAttribute() }}"
        }
        </script>
    @endif
</head>
<body>
    {!! $html !!}

    {{-- Form Submission Handler --}}
    <script>
    document.querySelectorAll('form[data-lp-form]').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const data = {};
            formData.forEach((value, key) => {
                if (key !== '_token' && key !== 'form_id') data[key] = value;
            });

            try {
                const res = await fetch('{{ route("lp.form.submit", $page->slug) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        form_id: form.dataset.formId,
                        data: data
                    })
                });
                const result = await res.json();
                if (result.success) {
                    const successMsg = form.querySelector('.form-success');
                    if (successMsg) {
                        successMsg.style.display = 'block';
                        form.reset();
                    }
                }
            } catch (err) {
                console.error('Form submission error:', err);
            }
        });
    });

    // Analytics tracking
    (function() {
        fetch('{{ route("api.analytics.event") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                page_slug: '{{ $page->slug }}',
                event_type: 'page_view'
            })
        }).catch(() => {});
    })();
    </script>

    @if($page->custom_js)
        <script>{!! $page->custom_js !!}</script>
    @endif
</body>
</html>
