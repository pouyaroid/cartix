@php
    $sections = $card->sections->where('is_visible', true)->sortBy('sort_order');
    $themeColor = $card->theme_color ?: '#667eea';
    $fontFamily = $card->font_family ?: 'Vazirmatn';

    $socialColors = [
        'instagram' => '#E4405F', 'telegram' => '#0088CC', 'whatsapp' => '#25D366',
        'twitter' => '#1DA1F2', 'linkedin' => '#0A66C2', 'youtube' => '#FF0000',
        'facebook' => '#1877F2', 'pinterest' => '#BD081C', 'tiktok' => '#000000',
        'aparat' => '#ED8B00', 'rubika' => '#FF4081', 'bale' => '#FF6B00',
    ];
    $socialIcons = [
        'instagram' => 'instagram', 'telegram' => 'telegram', 'whatsapp' => 'whatsapp',
        'twitter' => 'twitter-x', 'linkedin' => 'linkedin', 'youtube' => 'youtube',
        'facebook' => 'facebook', 'pinterest' => 'pinterest', 'tiktok' => 'tiktok',
        'aparat' => 'play-circle', 'rubika' => 'chat-dots', 'bale' => 'chat-left-text',
    ];
@endphp
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $card->seo_title ?: $card->title }}</title>
    <meta name="description" content="{{ $card->seo_description ?: $card->description }}">
    <meta property="og:title" content="{{ $card->seo_title ?: $card->title }}">
    <meta property="og:description" content="{{ $card->seo_description ?: $card->description }}">
    <meta property="og:type" content="profile">
    @if($card->og_image)
        <meta property="og:image" content="{{ asset('storage/' . $card->og_image) }}">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        *{box-sizing:border-box}
        body{font-family:'{{ $fontFamily }}','Vazirmatn',sans-serif;background:#f8f9fa;margin:0;padding:0}
        .card-hero{background:linear-gradient(135deg,{{ $themeColor }},{{ $themeColor }}dd);color:#fff;padding:3rem 1rem 4rem;text-align:center;position:relative;overflow:hidden}
        .card-hero .cover-overlay{position:absolute;inset:0;background-size:cover;background-position:center;opacity:.3}
        .card-hero .avatar{width:100px;height:100px;border-radius:50%;border:4px solid #fff;object-fit:cover;margin-top:-50px;background:#fff}
        .card-hero .avatar-placeholder{width:100px;height:100px;border-radius:50%;border:4px solid #fff;margin-top:-50px;background:#fff;display:flex;align-items:center;justify-content:center;font-size:2.5rem;color:{{ $themeColor }};margin-left:auto;margin-right:auto}
        .card-container{max-width:600px;margin:-2rem auto 0;position:relative;z-index:1;padding:0 1rem}
        .section-card{border:none;border-radius:1rem;box-shadow:0 .125rem .25rem rgba(0,0,0,.075);margin-bottom:1rem;background:#fff}
        .social-icon{width:48px;height:48px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:1.2rem;text-decoration:none;transition:transform .2s}
        .social-icon:hover{transform:scale(1.1);color:#fff}
        .contact-btn{display:inline-flex;align-items:center;gap:.5rem;padding:.6rem 1.2rem;border-radius:.5rem;text-decoration:none;color:#fff;font-size:.9rem;transition:transform .2s,box-shadow .2s}
        .contact-btn:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,.15);color:#fff}
        .gallery-item{border-radius:.75rem;overflow:hidden}
        .gallery-item img{width:100%;height:150px;object-fit:cover;transition:transform .3s}
        .gallery-item:hover img{transform:scale(1.05)}
        .product-card{border:1px solid #eee;border-radius:.75rem;overflow:hidden}
        .product-card img{width:100%;height:120px;object-fit:cover}
        .testimonial-card{border-left:3px solid {{ $themeColor }}}
        .faq-item .accordion-button{border-radius:.5rem!important}
        .faq-item .accordion-button:not(.collapsed){background:{{ $themeColor }}10;color:{{ $themeColor }}}
        .map-container{border-radius:.75rem;overflow:hidden;height:200px}
        .qr-section{text-align:center}
        .qr-section img{max-width:200px;border-radius:.5rem}
        .card-footer{text-align:center;padding:2rem 1rem;color:#999;font-size:.85rem}
        .card-footer a{color:{{ $themeColor }};text-decoration:none}
    </style>
    @if($card->settings['animations'] ?? false)
    <style>
        .section-card{animation:fadeInUp .5s ease-out}
        @@keyframes fadeInUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
    </style>
    @endif
</head>
<body>
    <div class="card-hero">
        @if($card->cover_image)
            <div class="cover-overlay" style="background-image:url('{{ asset('storage/' . $card->cover_image) }}')"></div>
        @endif
        <div class="position-relative">
            @if($card->logo)
                <img src="{{ asset('storage/' . $card->logo) }}" alt="لوگو" style="max-height:50px" class="mb-3">
            @endif
            @if($card->profile_image)
                <img src="{{ asset('storage/' . $card->profile_image) }}" class="avatar d-block mx-auto" alt="{{ $card->title }}">
            @else
                <div class="avatar-placeholder">{{ mb_substr($card->title, 0, 1) }}</div>
            @endif
            <h1 class="mt-3 mb-1" style="font-size:1.5rem">{{ $card->title }}</h1>
            @if($card->description)
                <p class="mb-0 opacity-75" style="font-size:.95rem">{{ $card->description }}</p>
            @endif
            <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
                @if($card->phone)
                    <a href="tel:{{ $card->phone }}" class="contact-btn" style="background:rgba(255,255,255,.2);backdrop-filter:blur(4px)">
                        <i class="bi bi-telephone"></i> تماس
                    </a>
                @endif
                @if($card->email)
                    <a href="mailto:{{ $card->email }}" class="contact-btn" style="background:rgba(255,255,255,.2);backdrop-filter:blur(4px)">
                        <i class="bi bi-envelope"></i> ایمیل
                    </a>
                @endif
                @if($card->website)
                    <a href="{{ $card->website }}" class="contact-btn" style="background:rgba(255,255,255,.2);backdrop-filter:blur(4px)" target="_blank">
                        <i class="bi bi-globe"></i> وبسایت
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="card-container">
        @foreach($sections as $section)
            @if($section->type === 'contact')
                <div class="card section-card">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="bi bi-telephone ms-2" style="color:{{ $themeColor }}"></i> {{ $section->title ?: 'اطلاعات تماس' }}</h6>
                        @if($card->phone)
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-telephone-fill text-muted ms-2"></i>
                                <a href="tel:{{ $card->phone }}" class="text-decoration-none">{{ $card->phone }}</a>
                            </div>
                        @endif
                        @if($card->email)
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-envelope-fill text-muted ms-2"></i>
                                <a href="mailto:{{ $card->email }}" class="text-decoration-none">{{ $card->email }}</a>
                            </div>
                        @endif
                        @if($card->website)
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-globe text-muted ms-2"></i>
                                <a href="{{ $card->website }}" class="text-decoration-none" target="_blank">{{ $card->website }}</a>
                            </div>
                        @endif
                        @if($card->address)
                            <div class="d-flex align-items-center">
                                <i class="bi bi-geo-alt-fill text-muted ms-2"></i>
                                <span class="text-muted small">{{ $card->address }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($section->type === 'social' && $card->socialLinks->count())
                <div class="card section-card">
                    <div class="card-body text-center">
                        <h6 class="fw-bold mb-3"><i class="bi bi-share ms-2" style="color:{{ $themeColor }}"></i> {{ $section->title ?: 'شبکه‌های اجتماعی' }}</h6>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            @foreach($card->socialLinks->sortBy('sort_order') as $link)
                                @php
                                    $sColor = $socialColors[$link->platform] ?? '#6c757d';
                                    $sIcon = $socialIcons[$link->platform] ?? 'globe';
                                @endphp
                                <a href="{{ $link->url }}" class="social-icon" style="background:{{ $sColor }}" target="_blank" title="{{ $link->platform }}">
                                    <i class="bi bi-{{ $sIcon }}"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if($section->type === 'gallery' && $card->galleryItems->count())
                <div class="card section-card">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="bi bi-images ms-2" style="color:{{ $themeColor }}"></i> {{ $section->title ?: 'گالری' }}</h6>
                        <div class="row g-2">
                            @foreach($card->galleryItems->sortBy('sort_order') as $item)
                                <div class="col-4 gallery-item">
                                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->caption }}" loading="lazy">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if($section->type === 'products' && $card->products->count())
                <div class="card section-card">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="bi bi-box-seam ms-2" style="color:{{ $themeColor }}"></i> {{ $section->title ?: 'محصولات' }}</h6>
                        <div class="row g-2">
                            @foreach($card->products->sortBy('sort_order') as $product)
                                <div class="col-6">
                                    <div class="product-card text-center p-2">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy">
                                        @endif
                                        <div class="fw-medium small mt-2">{{ $product->name }}</div>
                                        @if($product->price)
                                            <div class="small" style="color:{{ $themeColor }}">{{ number_format($product->price) }} تومان</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if($section->type === 'services' && $card->services->count())
                <div class="card section-card">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="bi bi-tools ms-2" style="color:{{ $themeColor }}"></i> {{ $section->title ?: 'خدمات' }}</h6>
                        @foreach($card->services->sortBy('sort_order') as $service)
                            <div class="d-flex gap-2 mb-2 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                @if($service->icon)
                                    <i class="bi bi-{{ $service->icon }}" style="color:{{ $themeColor }}"></i>
                                @endif
                                <div>
                                    <div class="fw-medium small">{{ $service->name }}</div>
                                    @if($service->description)
                                        <div class="text-muted" style="font-size:.8rem">{{ $service->description }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'testimonials' && $card->testimonials->count())
                <div class="card section-card">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="bi bi-chat-quote ms-2" style="color:{{ $themeColor }}"></i> {{ $section->title ?: 'نظرات مشتریان' }}</h6>
                        @foreach($card->testimonials->sortBy('sort_order') as $t)
                            <div class="mb-2 pb-2 testimonial-card ps-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    @if($t->author_image)
                                        <img src="{{ asset('storage/' . $t->author_image) }}" class="rounded-circle" style="width:28px;height:28px;object-fit:cover" alt="">
                                    @endif
                                    <span class="fw-medium small">{{ $t->author_name }}</span>
                                    @if($t->rating)
                                        <span class="text-warning" style="font-size:.75rem">
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

            @if($section->type === 'faq' && $card->faqs->count())
                <div class="card section-card">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="bi bi-question-circle ms-2" style="color:{{ $themeColor }}"></i> {{ $section->title ?: 'سوالات متداول' }}</h6>
                        <div class="accordion" id="faq-{{ $card->id }}">
                            @foreach($card->faqs as $faq)
                                <div class="accordion-item border-0 mb-1 faq-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed small fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#faq-{{ $card->id }}-{{ $faq->id }}">
                                            {{ $faq->question }}
                                        </button>
                                    </h2>
                                    <div id="faq-{{ $card->id }}-{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#faq-{{ $card->id }}">
                                        <div class="accordion-body small text-muted">{{ $faq->answer }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if($section->type === 'map' && $card->map_lat && $card->map_lng)
                <div class="card section-card">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt ms-2" style="color:{{ $themeColor }}"></i> {{ $section->title ?: 'موقعیت مکانی' }}</h6>
                        <div class="map-container">
                            <iframe src="https://www.openstreetmap.org/export/embed.html?bbox={{ $card->map_lng - 0.01 }}%2C{{ $card->map_lat - 0.01 }}%2C{{ $card->map_lng + 0.01 }}%2C{{ $card->map_lat + 0.01 }}&layer=mapnik&marker={{ $card->map_lat }}%2C{{ $card->map_lng }}" width="100%" height="200" style="border:0" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            @endif

            @if($section->type === 'custom' && $section->content)
                <div class="card section-card">
                    <div class="card-body">{!! nl2br(e($section->content)) !!}</div>
                </div>
            @endif
        @endforeach

        @if($card->qrCodes->where('is_active', true)->count())
            @php $qr = $card->qrCodes->where('is_active', true)->first(); @endphp
            @if($qr)
                <div class="card section-card">
                    <div class="card-body qr-section">
                        <h6 class="fw-bold mb-3"><i class="bi bi-qr-code ms-2" style="color:{{ $themeColor }}"></i> کد QR</h6>
                        @php
                            $qrOpts = new \Chillerlan\QRCode\QROptions([
                                'outputInterface' => \Chillerlan\QRCode\Output\QRGdImagePNG::class,
                                'eccLevel' => \Chillerlan\QRCode\QRCode::ECC_M,
                                'scale' => 5,
                                'imageBase64' => false,
                                'bgColor' => $qr->background_color ?? '#FFFFFF',
                            ]);
                            $qrGen = new \Chillerlan\QRCode\QRCode($qrOpts);
                            $qrData = base64_encode($qrGen->render(route('qr.redirect', $qr->unique_code)));
                        @endphp
                        <img src="data:image/png;base64,{{ $qrData }}" alt="QR Code" style="max-width:150px">
                        <p class="text-muted small mt-2 mb-0">برای مشاهده کارت اسکن کنید</p>
                    </div>
                </div>
            @endif
        @endif

        <div class="card-footer">
            <p class="mb-0">ساخته شده با <a href="/">کارت‌اکس</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
