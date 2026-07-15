<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $card->seo_title ?: $card->title }}</title>
    <meta name="description" content="{{ $card->seo_description ?: $card->description }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $card->seo_title ?: $card->title }}">
    <meta property="og:description" content="{{ $card->seo_description ?: $card->description }}">
    <meta property="og:type" content="profile">
    @if($card->og_image)
        <meta property="og:image" content="{{ asset('storage/' . $card->og_image) }}">
    @endif

    {{-- Schema.org --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "{{ $card->schema_type ?: 'Person' }}",
        "name": "{{ $card->title }}",
        "description": "{{ $card->description }}",
        "url": "{{ url('/' . $card->slug) }}"
        @if($card->email)
        ,"email": "{{ $card->email }}"
        @endif
        @if($card->phone)
        ,"telephone": "{{ $card->phone }}"
        @endif
    }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body { font-family: 'Vazirmatn', sans-serif; background: #f8f9fa; }
        .card-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 1rem 4rem;
            text-align: center;
        }
        .card-hero .avatar {
            width: 100px; height: 100px; border-radius: 50%;
            border: 4px solid white; object-fit: cover;
            margin-top: -50px; background: white;
        }
        .card-hero .avatar-placeholder {
            width: 100px; height: 100px; border-radius: 50%;
            border: 4px solid white;
            margin-top: -50px; background: white;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.5rem; color: #667eea; margin-left: auto; margin-right: auto;
        }
        .section-card { border: none; border-radius: 1rem; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); }
        .social-icon { width: 48px; height: 48px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem; text-decoration: none; transition: transform 0.2s; }
        .social-icon:hover { transform: scale(1.1); color: white; }
    </style>
</head>
<body>
    {{-- Hero --}}
    <div class="card-hero">
        @if($card->cover_image)
            <div style="position:absolute;inset:0;background:url('{{ asset('storage/' . $card->cover_image) }}') center/cover;opacity:0.3;"></div>
        @endif
        <div class="position-relative">
            @if($card->logo)
                <img src="{{ asset('storage/' . $card->logo) }}" alt="لوگو" style="max-height:50px;" class="mb-3">
            @endif
            @if($card->profile_image)
                <img src="{{ asset('storage/' . $card->profile_image) }}" class="avatar d-block mx-auto" alt="{{ $card->title }}">
            @else
                <div class="avatar-placeholder">{{ mb_substr($card->title, 0, 1) }}</div>
            @endif
            <h1 class="mt-3 mb-1">{{ $card->title }}</h1>
            @if($card->description)
                <p class="mb-0 opacity-75">{{ $card->description }}</p>
            @endif
        </div>
    </div>

    <div class="container" style="max-width: 600px; margin-top: -2rem; position: relative; z-index: 1;">
        {{-- Sections --}}
        @foreach($card->sections->where('is_visible', true) as $section)
            @if($section->type === 'contact')
                <div class="card section-card mb-3 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="bi bi-telephone ms-2"></i> {{ $section->title ?: 'تماس' }}</h6>
                        @if($card->phone)
                            <div class="d-flex align-items-center mb-2">
                                <a href="tel:{{ $card->phone }}" class="text-decoration-none">{{ $card->phone }}</a>
                            </div>
                        @endif
                        @if($card->email)
                            <div class="d-flex align-items-center mb-2">
                                <a href="mailto:{{ $card->email }}" class="text-decoration-none">{{ $card->email }}</a>
                            </div>
                        @endif
                        @if($card->website)
                            <div class="d-flex align-items-center mb-2">
                                <a href="{{ $card->website }}" class="text-decoration-none" target="_blank">{{ $card->website }}</a>
                            </div>
                        @endif
                        @if($card->address)
                            <div class="d-flex align-items-center">
                                <i class="bi bi-geo-alt text-muted ms-2"></i>
                                <span class="text-muted small">{{ $card->address }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($section->type === 'social' && $card->socialLinks->count())
                <div class="card section-card mb-3 shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="fw-bold mb-3"><i class="bi bi-share ms-2"></i> {{ $section->title ?: 'شبکه‌های اجتماعی' }}</h6>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            @foreach($card->socialLinks as $link)
                                <a href="{{ $link->url }}" class="social-icon" style="background:{{ match($link->platform) {
                                    'instagram' => '#E4405F', 'telegram' => '#0088CC', 'whatsapp' => '#25D366',
                                    'twitter' => '#1DA1F2', 'linkedin' => '#0A66C2', 'youtube' => '#FF0000',
                                    default => '#6c757d'
                                } }};" target="_blank" title="{{ $link->platform }}">
                                    <i class="bi bi-{{ match($link->platform) {
                                        'instagram' => 'instagram', 'telegram' => 'telegram', 'whatsapp' => 'whatsapp',
                                        'twitter' => 'twitter-x', 'linkedin' => 'linkedin', 'youtube' => 'youtube',
                                        default => 'globe'
                                    } }}"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if($section->type === 'products' && $card->products->count())
                <div class="card section-card mb-3 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="bi bi-box-seam ms-2"></i> {{ $section->title ?: 'محصولات' }}</h6>
                        <div class="row g-2">
                            @foreach($card->products as $product)
                            <div class="col-6">
                                <div class="border rounded p-2 text-center small">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" class="rounded mb-1" style="width:100%;height:80px;object-fit:cover;" alt="{{ $product->name }}">
                                    @endif
                                    <div class="fw-medium">{{ $product->name }}</div>
                                    @if($product->price)
                                        <div class="text-primary">{{ number_format($product->price) }} تومان</div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if($section->type === 'services' && $card->services->count())
                <div class="card section-card mb-3 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="bi bi-tools ms-2"></i> {{ $section->title ?: 'خدمات' }}</h6>
                        @foreach($card->services as $service)
                            <div class="d-flex gap-2 mb-2 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                @if($service->icon)
                                    <i class="bi bi-{{ $service->icon }} text-primary"></i>
                                @endif
                                <div>
                                    <div class="fw-medium small">{{ $service->name }}</div>
                                    @if($service->description)
                                        <div class="text-muted" style="font-size:0.8rem;">{{ $service->description }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'faq' && $card->faqs->count())
                <div class="card section-card mb-3 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="bi bi-question-circle ms-2"></i> {{ $section->title ?: 'سوالات متداول' }}</h6>
                        <div class="accordion" id="faqAccordion">
                            @foreach($card->faqs as $faq)
                            <div class="accordion-item border-0 mb-1">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed small fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $faq->id }}">
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="faq{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body small text-muted">{{ $faq->answer }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if($section->type === 'testimonials' && $card->testimonials->count())
                <div class="card section-card mb-3 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="bi bi-chat-quote ms-2"></i> {{ $section->title ?: 'نظرات' }}</h6>
                        @foreach($card->testimonials as $t)
                        <div class="mb-2 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                @if($t->author_image)
                                    <img src="{{ asset('storage/' . $t->author_image) }}" class="rounded-circle" style="width:28px;height:28px;object-fit:cover;" alt="">
                                @endif
                                <span class="fw-medium small">{{ $t->author_name }}</span>
                                @if($t->rating)
                                    <span class="text-warning" style="font-size:0.75rem;">
                                        @for($i = 0; $i < $t->rating; $i++)&#9733;@endfor
                                    </span>
                                @endif
                            </div>
                            <div class="text-muted small">{{ $t->content }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'custom' && $section->content)
                <div class="card section-card mb-3 shadow-sm">
                    <div class="card-body">{!! nl2br(e($section->content)) !!}</div>
                </div>
            @endif
        @endforeach

        {{-- Footer --}}
        <div class="text-center py-4 text-muted small">
            <p class="mb-0">ساخته شده با <a href="/" class="text-decoration-none">کارت ایکس</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
