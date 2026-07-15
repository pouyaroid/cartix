@php
    $sections = $card->sections->where('is_visible', true)->sortBy('sort_order');
    $themeColor = $card->theme_color ?: '#1a365d';
@endphp
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $card->seo_title ?: $card->title }}</title>
    <meta name="description" content="{{ $card->seo_description ?: $card->description }}">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Vazirmatn',sans-serif;background:#f0f2f5;color:#1a202c}
        .biz-hero{background:linear-gradient(135deg,{{ $themeColor }},{{ $themeColor }}cc);padding:2.5rem 1.5rem 3rem;text-align:center;color:#fff;position:relative;overflow:hidden}
        .biz-hero::after{content:'';position:absolute;bottom:-30px;left:0;right:0;height:60px;background:#f0f2f5;border-radius:50% 50% 0 0}
        .biz-hero .company-logo{max-height:45px;margin-bottom:1rem;filter:brightness(0) invert(1)}
        .biz-hero .avatar{width:110px;height:110px;border-radius:50%;border:4px solid rgba(255,255,255,0.3);object-fit:cover;margin:0 auto;display:block;background:#fff;padding:3px}
        .biz-hero .avatar-placeholder{width:110px;height:110px;border-radius:50%;border:4px solid rgba(255,255,255,0.3);margin:0 auto;display:flex;align-items:center;justify-content:center;font-size:2.5rem;background:rgba(255,255,255,0.15);backdrop-filter:blur(4px)}
        .biz-hero h1{font-size:1.6rem;font-weight:700;margin-top:1rem}
        .biz-hero .position{font-size:.9rem;opacity:.85;margin-top:.25rem}
        .biz-container{max-width:560px;margin:-1.5rem auto 0;position:relative;z-index:1;padding:0 1rem}
        .biz-card{background:#fff;border-radius:1rem;box-shadow:0 2px 12px rgba(0,0,0,.06);margin-bottom:1rem;overflow:hidden;border:1px solid rgba(0,0,0,.04)}
        .biz-card .card-head{padding:1rem 1.25rem .5rem;font-weight:700;font-size:.9rem;color:{{ $themeColor }};display:flex;align-items:center;gap:.5rem}
        .biz-card .card-head i{font-size:1rem}
        .biz-card .card-body-custom{padding:0 1.25rem 1.25rem}
        .contact-row{display:flex;align-items:center;gap:.75rem;padding:.6rem 0;border-bottom:1px solid #f1f3f5}
        .contact-row:last-child{border-bottom:none}
        .contact-row i{width:20px;text-align:center;color:{{ $themeColor }};font-size:.95rem}
        .contact-row a,.contact-row span{color:#4a5568;text-decoration:none;font-size:.88rem}
        .contact-row a:hover{color:{{ $themeColor }}}
        .social-row{display:flex;flex-wrap:wrap;gap:.5rem;justify-content:center}
        .social-pill{display:inline-flex;align-items:center;gap:.4rem;padding:.45rem .9rem;border-radius:2rem;color:#fff;font-size:.8rem;text-decoration:none;transition:transform .2s,box-shadow .2s}
        .social-pill:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,.15);color:#fff}
        .service-item{display:flex;align-items:flex-start;gap:.75rem;padding:.6rem 0;border-bottom:1px solid #f1f3f5}
        .service-item:last-child{border-bottom:none}
        .service-item .svc-icon{width:36px;height:36px;border-radius:.5rem;background:{{ $themeColor }}10;color:{{ $themeColor }};display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .service-item .svc-name{font-weight:600;font-size:.88rem}
        .service-item .svc-desc{font-size:.78rem;color:#718096;margin-top:.15rem}
        .biz-map{border-radius:.75rem;overflow:hidden;height:180px}
        .biz-footer{text-align:center;padding:1.5rem 1rem;color:#a0aec0;font-size:.78rem}
        .biz-footer a{color:{{ $themeColor }};text-decoration:none}
        .action-btns{display:flex;gap:.5rem;justify-content:center;flex-wrap:wrap;margin-top:1rem}
        .action-btn{display:inline-flex;align-items:center;gap:.4rem;padding:.6rem 1.2rem;border-radius:.75rem;background:rgba(255,255,255,.18);backdrop-filter:blur(4px);color:#fff;text-decoration:none;font-size:.88rem;transition:all .2s}
        .action-btn:hover{background:rgba(255,255,255,.3);color:#fff}
    </style>
</head>
<body>
    <div class="biz-hero">
        @if($card->cover_image)
            <div style="position:absolute;inset:0;background:url('{{ asset('storage/' . $card->cover_image) }}') center/cover;opacity:.2"></div>
        @endif
        <div style="position:relative">
            @if($card->logo)
                <img src="{{ asset('storage/' . $card->logo) }}" alt="لوگو" class="company-logo">
            @endif
            @if($card->profile_image)
                <img src="{{ asset('storage/' . $card->profile_image) }}" class="avatar" alt="{{ $card->title }}">
            @else
                <div class="avatar-placeholder">{{ mb_substr($card->title, 0, 1) }}</div>
            @endif
            <h1>{{ $card->title }}</h1>
            @if($card->description)
                <div class="position">{{ $card->description }}</div>
            @endif
            <div class="action-btns">
                @if($card->phone)
                    <a href="tel:{{ $card->phone }}" class="action-btn"><i class="bi bi-telephone-fill"></i> تماس</a>
                @endif
                @if($card->email)
                    <a href="mailto:{{ $card->email }}" class="action-btn"><i class="bi bi-envelope-fill"></i> ایمیل</a>
                @endif
                @if($card->website)
                    <a href="{{ $card->website }}" class="action-btn" target="_blank"><i class="bi bi-globe"></i> وبسایت</a>
                @endif
            </div>
        </div>
    </div>

    <div class="biz-container">
        @foreach($sections as $section)
            @if($section->type === 'contact')
                <div class="biz-card">
                    <div class="card-head"><i class="bi bi-telephone-fill"></i> {{ $section->title ?: 'اطلاعات تماس' }}</div>
                    <div class="card-body-custom">
                        @if($card->phone)
                            <div class="contact-row"><i class="bi bi-telephone"></i><a href="tel:{{ $card->phone }}">{{ $card->phone }}</a></div>
                        @endif
                        @if($card->email)
                            <div class="contact-row"><i class="bi bi-envelope"></i><a href="mailto:{{ $card->email }}">{{ $card->email }}</a></div>
                        @endif
                        @if($card->website)
                            <div class="contact-row"><i class="bi bi-globe"></i><a href="{{ $card->website }}" target="_blank">{{ $card->website }}</a></div>
                        @endif
                        @if($card->address)
                            <div class="contact-row"><i class="bi bi-geo-alt"></i><span>{{ $card->address }}</span></div>
                        @endif
                    </div>
                </div>
            @endif

            @if($section->type === 'social' && $card->socialLinks->count())
                <div class="biz-card">
                    <div class="card-head"><i class="bi bi-share-fill"></i> {{ $section->title ?: 'شبکه‌های اجتماعی' }}</div>
                    <div class="card-body-custom">
                        <div class="social-row">
                            @foreach($card->socialLinks->sortBy('sort_order') as $link)
                                @php
                                    $sColors = ['instagram'=>'#E4405F','telegram'=>'#0088CC','whatsapp'=>'#25D366','twitter'=>'#1DA1F2','linkedin'=>'#0A66C2','youtube'=>'#FF0000','facebook'=>'#1877F2'];
                                    $sIcons = ['instagram'=>'instagram','telegram'=>'telegram','whatsapp'=>'whatsapp','twitter'=>'twitter-x','linkedin'=>'linkedin','youtube'=>'youtube','facebook'=>'facebook'];
                                @endphp
                                <a href="{{ $link->url }}" class="social-pill" style="background:{{ $sColors[$link->platform] ?? '#6c757d' }}" target="_blank">
                                    <i class="bi bi-{{ $sIcons[$link->platform] ?? 'globe' }}"></i> {{ ucfirst($link->platform) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if($section->type === 'services' && $card->services->count())
                <div class="biz-card">
                    <div class="card-head"><i class="bi bi-briefcase-fill"></i> {{ $section->title ?: 'خدمات' }}</div>
                    <div class="card-body-custom">
                        @foreach($card->services->sortBy('sort_order') as $service)
                            <div class="service-item">
                                <div class="svc-icon"><i class="bi bi-{{ $service->icon ?? 'check-circle' }}"></i></div>
                                <div>
                                    <div class="svc-name">{{ $service->name }}</div>
                                    @if($service->description)
                                        <div class="svc-desc">{{ $service->description }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'products' && $card->products->count())
                <div class="biz-card">
                    <div class="card-head"><i class="bi bi-box-seam-fill"></i> {{ $section->title ?: 'محصولات' }}</div>
                    <div class="card-body-custom">
                        <div class="row g-2">
                            @foreach($card->products->sortBy('sort_order') as $product)
                                <div class="col-6">
                                    <div style="border:1px solid #f1f3f5;border-radius:.75rem;overflow:hidden;text-align:center">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" style="width:100%;height:100px;object-fit:cover" alt="{{ $product->name }}" loading="lazy">
                                        @endif
                                        <div style="padding:.5rem">
                                            <div style="font-weight:600;font-size:.82rem">{{ $product->name }}</div>
                                            @if($product->price)
                                                <div style="color:{{ $themeColor }};font-size:.78rem;font-weight:700">{{ number_format($product->price) }} تومان</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if($section->type === 'testimonials' && $card->testimonials->count())
                <div class="biz-card">
                    <div class="card-head"><i class="bi bi-chat-quote-fill"></i> {{ $section->title ?: 'نظرات مشتریان' }}</div>
                    <div class="card-body-custom">
                        @foreach($card->testimonials->sortBy('sort_order') as $t)
                            <div style="padding:.6rem 0;border-bottom:1px solid #f1f3f5;border-left:3px solid {{ $themeColor }};padding-left:.75rem;margin-bottom:.5rem">
                                <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.25rem">
                                    @if($t->author_image)
                                        <img src="{{ asset('storage/' . $t->author_image) }}" style="width:28px;height:28px;border-radius:50%;object-fit:cover" alt="">
                                    @endif
                                    <span style="font-weight:600;font-size:.82rem">{{ $t->author_name }}</span>
                                    @if($t->rating)
                                        <span style="color:#f6ad55;font-size:.7rem">@for($i = 0; $i < $t->rating; $i++)★@endfor</span>
                                    @endif
                                </div>
                                <div style="color:#718096;font-size:.82rem">{{ $t->content }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'faq' && $card->faqs->count())
                <div class="biz-card">
                    <div class="card-head"><i class="bi bi-question-circle-fill"></i> {{ $section->title ?: 'سوالات متداول' }}</div>
                    <div class="card-body-custom">
                        <div class="accordion" id="bizFaq">
                            @foreach($card->faqs as $faq)
                                <div style="border-bottom:1px solid #f1f3f5">
                                    <button class="accordion-button collapsed" style="font-size:.88rem;font-weight:600;padding:.75rem 0;border:none;background:none" data-bs-toggle="collapse" data-bs-target="#bfq{{ $faq->id }}">{{ $faq->question }}</button>
                                    <div id="bfq{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#bizFaq">
                                        <div style="padding:0 0 .75rem;font-size:.82rem;color:#718096">{{ $faq->answer }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if($section->type === 'map' && $card->map_lat && $card->map_lng)
                <div class="biz-card">
                    <div class="card-head"><i class="bi bi-geo-alt-fill"></i> {{ $section->title ?: 'موقعیت مکانی' }}</div>
                    <div class="card-body-custom">
                        <div class="biz-map">
                            <iframe src="https://www.openstreetmap.org/export/embed.html?bbox={{ $card->map_lng - 0.01 }}%2C{{ $card->map_lat - 0.01 }}%2C{{ $card->map_lng + 0.01 }}%2C{{ $card->map_lat + 0.01 }}&layer=mapnik&marker={{ $card->map_lat }}%2C{{ $card->map_lng }}" width="100%" height="180" style="border:0" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            @endif

            @if($section->type === 'custom' && $section->content)
                <div class="biz-card">
                    <div class="card-body-custom" style="padding-top:1.25rem">{!! nl2br(e($section->content)) !!}</div>
                </div>
            @endif
        @endforeach

        @if($card->qrCodes->where('is_active', true)->count())
            @php $qr = $card->qrCodes->where('is_active', true)->first(); @endphp
            @if($qr)
                @php
                    $qrOpts = new \Chillerlan\QRCode\QROptions(['outputInterface' => \Chillerlan\QRCode\Output\QRGdImagePNG::class, 'eccLevel' => \Chillerlan\QRCode\QRCode::ECC_M, 'scale' => 5, 'imageBase64' => false, 'bgColor' => '#FFFFFF']);
                    $qrGen = new \Chillerlan\QRCode\QRCode($qrOpts);
                    $qrData = base64_encode($qrGen->render(route('qr.redirect', $qr->unique_code)));
                @endphp
                <div class="biz-card" style="text-align:center">
                    <div class="card-head" style="justify-content:center"><i class="bi bi-qr-code"></i> کد QR</div>
                    <div class="card-body-custom">
                        <img src="data:image/png;base64,{{ $qrData }}" alt="QR" style="max-width:140px;border-radius:.5rem">
                        <div style="color:#a0aec0;font-size:.78rem;margin-top:.5rem">برای مشاهده کارت اسکن کنید</div>
                    </div>
                </div>
            @endif
        @endif

        <div class="biz-footer">ساخته شده با <a href="/">کارت‌اکس</a></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
