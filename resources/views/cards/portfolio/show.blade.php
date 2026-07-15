@php
    $sections = $card->sections->where('is_visible', true)->sortBy('sort_order');
    $themeColor = $card->theme_color ?: '#6366f1';
    $meta = $card->meta ?? [];
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
        body{font-family:'Vazirmatn',sans-serif;background:#fafafa;color:#1e1e1e}
        .pf-hero{padding:3rem 1.5rem 2rem;text-align:center}
        .pf-avatar{width:100px;height:100px;border-radius:1.5rem;object-fit:cover;margin:0 auto;display:block;border:3px solid {{ $themeColor }}20;box-shadow:0 8px 30px {{ $themeColor }}20}
        .pf-avatar-placeholder{width:100px;height:100px;border-radius:1.5rem;margin:0 auto;display:flex;align-items:center;justify-content:center;font-size:2.2rem;font-weight:700;color:#fff;background:linear-gradient(135deg,{{ $themeColor }},{{ $themeColor }}bb)}
        .pf-hero h1{font-size:1.5rem;font-weight:700;margin-top:1rem}
        .pf-hero .pf-role{color:{{ $themeColor }};font-size:.9rem;font-weight:500}
        .pf-hero .pf-bio{color:#666;font-size:.88rem;margin-top:.5rem;max-width:400px;margin-left:auto;margin-right:auto;line-height:1.8}
        .pf-links{display:flex;flex-wrap:wrap;justify-content:center;gap:.5rem;margin-top:1.25rem}
        .pf-link{display:inline-flex;align-items:center;gap:.35rem;padding:.45rem 1rem;border-radius:.75rem;background:{{ $themeColor }}0d;color:{{ $themeColor }};text-decoration:none;font-size:.85rem;font-weight:500;border:1px solid {{ $themeColor }}20;transition:all .2s}
        .pf-link:hover{background:{{ $themeColor }};color:#fff;border-color:{{ $themeColor }}}
        .pf-container{max-width:560px;margin:0 auto;padding:0 1rem 2rem}
        .pf-card{background:#fff;border-radius:1rem;box-shadow:0 1px 8px rgba(0,0,0,.04);margin-bottom:1rem;overflow:hidden;border:1px solid #f0f0f0}
        .pf-card-head{padding:1rem 1.25rem .5rem;font-weight:700;font-size:.9rem;color:{{ $themeColor }};display:flex;align-items:center;gap:.5rem}
        .pf-card-body{padding:0 1.25rem 1.25rem}
        .pf-portfolio-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:.75rem}
        .pf-portfolio-item{border-radius:.75rem;overflow:hidden;border:1px solid #f0f0f0;transition:transform .2s}
        .pf-portfolio-item:hover{transform:translateY(-2px);box-shadow:0 4px 16px rgba(0,0,0,.08)}
        .pf-portfolio-item img{width:100%;height:140px;object-fit:cover}
        .pf-portfolio-item .pi-title{padding:.5rem;font-weight:600;font-size:.82rem}
        .pf-portfolio-item .pi-desc{padding:0 .5rem .5rem;font-size:.75rem;color:#888}
        .pf-skill{display:inline-block;padding:.3rem .7rem;border-radius:2rem;background:{{ $themeColor }}0d;color:{{ $themeColor }};font-size:.78rem;font-weight:500;margin:.2rem;border:1px solid {{ $themeColor }}15}
        .pf-social{display:flex;flex-wrap:wrap;gap:.5rem;justify-content:center}
        .pf-social a{display:inline-flex;align-items:center;gap:.35rem;padding:.4rem .85rem;border-radius:2rem;color:#fff;font-size:.78rem;text-decoration:none;transition:transform .2s}
        .pf-social a:hover{transform:translateY(-2px);color:#fff}
        .pf-contact-row{display:flex;align-items:center;gap:.75rem;padding:.6rem 0;border-bottom:1px solid #f5f5f5}
        .pf-contact-row:last-child{border-bottom:none}
        .pf-contact-row i{color:{{ $themeColor }};width:20px;text-align:center}
        .pf-contact-row a,.pf-contact-row span{color:#555;text-decoration:none;font-size:.88rem}
        .pf-map{border-radius:1rem;overflow:hidden;height:180px}
        .pf-footer{text-align:center;padding:2rem 1rem;color:#bbb;font-size:.78rem}
        .pf-footer a{color:{{ $themeColor }};text-decoration:none}
    </style>
</head>
<body>
    <div class="pf-hero">
        @if($card->profile_image)
            <img src="{{ asset('storage/' . $card->profile_image) }}" class="pf-avatar" alt="{{ $card->title }}">
        @else
            <div class="pf-avatar-placeholder">{{ mb_substr($card->title, 0, 1) }}</div>
        @endif
        <h1>{{ $card->title }}</h1>
        @if($card->description)
            <div class="pf-role">{{ $card->description }}</div>
        @endif
        @if($meta['bio'] ?? false)
            <div class="pf-bio">{{ $meta['bio'] }}</div>
        @endif
        <div class="pf-links">
            @if($card->phone)
                <a href="tel:{{ $card->phone }}" class="pf-link"><i class="bi bi-telephone"></i> تماس</a>
            @endif
            @if($card->email)
                <a href="mailto:{{ $card->email }}" class="pf-link"><i class="bi bi-envelope"></i> ایمیل</a>
            @endif
            @if($card->website)
                <a href="{{ $card->website }}" class="pf-link" target="_blank"><i class="bi bi-globe"></i> وبسایت</a>
            @endif
        </div>
    </div>

    <div class="pf-container">
        @if($meta['skills'] ?? false)
            <div class="pf-card">
                <div class="pf-card-head"><i class="bi bi-stars"></i> مهارت‌ها</div>
                <div class="pf-card-body">
                    @foreach($meta['skills'] as $skill)
                        <span class="pf-skill">{{ $skill }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        @foreach($sections as $section)
            @if($section->type === 'gallery' && $card->galleryItems->count())
                <div class="pf-card">
                    <div class="pf-card-head"><i class="bi bi-grid"></i> {{ $section->title ?: 'نمونه کارها' }}</div>
                    <div class="pf-card-body">
                        <div class="pf-portfolio-grid">
                            @foreach($card->galleryItems->sortBy('sort_order') as $item)
                                <div class="pf-portfolio-item">
                                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->caption }}" loading="lazy">
                                    @if($item->caption)
                                        <div class="pi-title">{{ $item->caption }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if($section->type === 'services' && $card->services->count())
                <div class="pf-card">
                    <div class="pf-card-head"><i class="bi bi-briefcase"></i> {{ $section->title ?: 'خدمات' }}</div>
                    <div class="pf-card-body">
                        @foreach($card->services->sortBy('sort_order') as $service)
                            <div style="display:flex;align-items:flex-start;gap:.75rem;padding:.6rem 0;border-bottom:1px solid #f5f5f5">
                                @if($service->icon)
                                    <div style="width:36px;height:36px;border-radius:.5rem;background:{{ $themeColor }}0d;color:{{ $themeColor }};display:flex;align-items:center;justify-content:center;flex-shrink:0"><i class="bi bi-{{ $service->icon }}"></i></div>
                                @endif
                                <div>
                                    <div style="font-weight:600;font-size:.88rem">{{ $service->name }}</div>
                                    @if($service->description)
                                        <div style="font-size:.78rem;color:#888;margin-top:.15rem">{{ $service->description }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'testimonials' && $card->testimonials->count())
                <div class="pf-card">
                    <div class="pf-card-head"><i class="bi bi-chat-quote"></i> {{ $section->title ?: 'نظرات' }}</div>
                    <div class="pf-card-body">
                        @foreach($card->testimonials->sortBy('sort_order') as $t)
                            <div style="padding:.6rem 0;border-bottom:1px solid #f5f5f5;border-left:3px solid {{ $themeColor }};padding-left:.75rem;margin-bottom:.5rem">
                                <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.25rem">
                                    <span style="font-weight:600;font-size:.85rem">{{ $t->author_name }}</span>
                                    @if($t->rating)
                                        <span style="color:#f59e0b;font-size:.7rem">@for($i = 0; $i < $t->rating; $i++)★@endfor</span>
                                    @endif
                                </div>
                                <div style="color:#888;font-size:.82rem;line-height:1.6">{{ $t->content }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'contact')
                <div class="pf-card">
                    <div class="pf-card-head"><i class="bi bi-envelope"></i> {{ $section->title ?: 'تماس' }}</div>
                    <div class="pf-card-body">
                        @if($card->phone)
                            <div class="pf-contact-row"><i class="bi bi-telephone"></i><a href="tel:{{ $card->phone }}">{{ $card->phone }}</a></div>
                        @endif
                        @if($card->email)
                            <div class="pf-contact-row"><i class="bi bi-envelope"></i><a href="mailto:{{ $card->email }}">{{ $card->email }}</a></div>
                        @endif
                        @if($card->website)
                            <div class="pf-contact-row"><i class="bi bi-globe"></i><a href="{{ $card->website }}" target="_blank">{{ $card->website }}</a></div>
                        @endif
                        @if($card->address)
                            <div class="pf-contact-row"><i class="bi bi-geo-alt"></i><span>{{ $card->address }}</span></div>
                        @endif
                    </div>
                </div>
            @endif

            @if($section->type === 'social' && $card->socialLinks->count())
                <div class="pf-card" style="text-align:center">
                    <div class="pf-card-head" style="justify-content:center"><i class="bi bi-share"></i> {{ $section->title ?: 'شبکه‌ها' }}</div>
                    <div class="pf-card-body">
                        <div class="pf-social">
                            @foreach($card->socialLinks->sortBy('sort_order') as $link)
                                @php
                                    $sC = ['instagram'=>'#E4405F','telegram'=>'#0088CC','linkedin'=>'#0A66C2','github'=>'#333','dribbble'=>'#EA4C89','behance'=>'#1769FF'];
                                    $sI = ['instagram'=>'instagram','telegram'=>'telegram','linkedin'=>'linkedin','github'=>'github','dribbble'=>'dribbble','behance'=>'bezier2'];
                                @endphp
                                <a href="{{ $link->url }}" style="background:{{ $sC[$link->platform] ?? '#6c757d' }}" target="_blank">
                                    <i class="bi bi-{{ $sI[$link->platform] ?? 'globe' }}"></i> {{ ucfirst($link->platform) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if($section->type === 'map' && $card->map_lat && $card->map_lng)
                <div class="pf-card" style="padding:0;overflow:hidden">
                    <div class="pf-map">
                        <iframe src="https://www.openstreetmap.org/export/embed.html?bbox={{ $card->map_lng - 0.01 }}%2C{{ $card->map_lat - 0.01 }}%2C{{ $card->map_lng + 0.01 }}%2C{{ $card->map_lat + 0.01 }}&layer=mapnik&marker={{ $card->map_lat }}%2C{{ $card->map_lng }}" width="100%" height="180" style="border:0" loading="lazy"></iframe>
                    </div>
                </div>
            @endif

            @if($section->type === 'custom' && $section->content)
                <div class="pf-card">
                    <div class="pf-card-body" style="padding-top:1.25rem;font-size:.88rem;line-height:1.8;color:#555">{!! nl2br(e($section->content)) !!}</div>
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
                <div class="pf-card" style="text-align:center">
                    <div class="pf-card-head" style="justify-content:center"><i class="bi bi-qr-code"></i> کد QR</div>
                    <div class="pf-card-body">
                        <img src="data:image/png;base64,{{ $qrData }}" alt="QR" style="max-width:120px;border-radius:.5rem">
                    </div>
                </div>
            @endif
        @endif

        <div class="pf-footer">ساخته شده با <a href="/">کارت‌اکس</a></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
