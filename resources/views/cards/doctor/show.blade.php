@php
    $sections = $card->sections->where('is_visible', true)->sortBy('sort_order');
    $themeColor = $card->theme_color ?: '#0077b6';
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
        body{font-family:'Vazirmatn',sans-serif;background:#f0f4f8;color:#1e293b}
        .doc-hero{background:linear-gradient(135deg,{{ $themeColor }},{{ $themeColor }}bb);padding:2.5rem 1.5rem 3.5rem;text-align:center;color:#fff;position:relative;overflow:hidden}
        .doc-hero::before{content:'';position:absolute;top:-50%;right:-30%;width:300px;height:300px;border-radius:50%;background:rgba(255,255,255,.08)}
        .doc-hero::after{content:'';position:absolute;bottom:-30px;left:0;right:0;height:50px;background:#f0f4f8;border-radius:50% 50% 0 0}
        .doc-avatar{width:120px;height:120px;border-radius:50%;border:4px solid rgba(255,255,255,.3);object-fit:cover;margin:0 auto;display:block;background:#fff;padding:3px}
        .doc-avatar-placeholder{width:120px;height:120px;border-radius:50%;border:4px solid rgba(255,255,255,.3);margin:0 auto;display:flex;align-items:center;justify-content:center;font-size:2.5rem;background:rgba(255,255,255,.15)}
        .doc-hero h1{font-size:1.6rem;font-weight:700;margin-top:1rem}
        .doc-hero .doc-spec{background:rgba(255,255,255,.2);backdrop-filter:blur(4px);display:inline-block;padding:.3rem 1rem;border-radius:2rem;font-size:.85rem;margin-top:.5rem}
        .doc-hero .doc-clinic{opacity:.8;font-size:.85rem;margin-top:.5rem}
        .doc-container{max-width:560px;margin:-1.5rem auto 0;position:relative;z-index:1;padding:0 1rem}
        .doc-card{background:#fff;border-radius:1rem;box-shadow:0 2px 12px rgba(0,0,0,.05);margin-bottom:1rem;overflow:hidden;border:1px solid #e2e8f0}
        .doc-card-head{padding:1rem 1.25rem .5rem;font-weight:700;font-size:.9rem;color:{{ $themeColor }};display:flex;align-items:center;gap:.5rem}
        .doc-card-body{padding:0 1.25rem 1.25rem}
        .doc-hours{display:grid;grid-template-columns:1fr auto;gap:.4rem .75rem;font-size:.85rem}
        .doc-hours .dh-day{font-weight:600}
        .doc-hours .dh-time{color:#64748b;text-align:left}
        .doc-cert{display:flex;align-items:center;gap:.5rem;padding:.5rem 0;border-bottom:1px solid #f1f5f9}
        .doc-cert:last-child{border-bottom:none}
        .doc-cert i{color:{{ $themeColor }}}
        .doc-cert span{font-size:.88rem}
        .doc-review{padding:.75rem 0;border-bottom:1px solid #f1f5f9}
        .doc-review:last-child{border-bottom:none}
        .doc-review .dr-header{display:flex;align-items:center;gap:.5rem;margin-bottom:.25rem}
        .doc-review .dr-name{font-weight:600;font-size:.85rem}
        .doc-review .dr-stars{color:#f59e0b;font-size:.75rem}
        .doc-review .dr-text{font-size:.82rem;color:#64748b;line-height:1.6}
        .doc-book-btn{display:flex;align-items:center;justify-content:center;gap:.5rem;padding:.85rem;border-radius:.75rem;background:{{ $themeColor }};color:#fff;text-decoration:none;font-weight:600;font-size:1rem;transition:all .2s;margin-top:.5rem}
        .doc-book-btn:hover{opacity:.9;color:#fff}
        .doc-contact-row{display:flex;align-items:center;gap:.75rem;padding:.6rem 0;border-bottom:1px solid #f1f5f9}
        .doc-contact-row:last-child{border-bottom:none}
        .doc-contact-row i{color:{{ $themeColor }};width:20px;text-align:center}
        .doc-contact-row a,.doc-contact-row span{color:#475569;text-decoration:none;font-size:.88rem}
        .doc-social{display:flex;flex-wrap:wrap;gap:.5rem;justify-content:center}
        .doc-social a{display:inline-flex;align-items:center;gap:.35rem;padding:.4rem .85rem;border-radius:2rem;color:#fff;font-size:.78rem;text-decoration:none;transition:transform .2s}
        .doc-social a:hover{transform:translateY(-2px);color:#fff}
        .doc-map{border-radius:1rem;overflow:hidden;height:180px}
        .doc-footer{text-align:center;padding:2rem 1rem;color:#94a3b8;font-size:.78rem}
        .doc-footer a{color:{{ $themeColor }};text-decoration:none}
    </style>
</head>
<body>
    <div class="doc-hero">
        <div style="position:relative">
            @if($card->profile_image)
                <img src="{{ asset('storage/' . $card->profile_image) }}" class="doc-avatar" alt="{{ $card->title }}">
            @else
                <div class="doc-avatar-placeholder">{{ mb_substr($card->title, 0, 1) }}</div>
            @endif
            <h1>{{ $card->title }}</h1>
            @if($card->description)
                <div class="doc-spec">{{ $card->description }}</div>
            @endif
            @if($meta['clinic_name'] ?? false)
                <div class="doc-clinic"><i class="bi bi-hospital ms-1"></i> {{ $meta['clinic_name'] }}</div>
            @endif
            @if($card->phone)
                <div style="margin-top:1rem"><a href="tel:{{ $card->phone }}" class="doc-book-btn" style="display:inline-flex"><i class="bi bi-calendar-check"></i> نوبت‌دهی آنلاین</a></div>
            @endif
        </div>
    </div>

    <div class="doc-container">
        @foreach($sections as $section)
            @if($section->type === 'services' && $card->services->count())
                <div class="doc-card">
                    <div class="doc-card-head"><i class="bi bi-stethoscope"></i> {{ $section->title ?: 'تخصص‌ها' }}</div>
                    <div class="doc-card-body">
                        @foreach($card->services->sortBy('sort_order') as $service)
                            <div class="doc-cert">
                                <i class="bi bi-patch-check-fill"></i>
                                <div>
                                    <span>{{ $service->name }}</span>
                                    @if($service->description)
                                        <div style="font-size:.78rem;color:#94a3b8">{{ $service->description }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'contact')
                <div class="doc-card">
                    <div class="doc-card-head"><i class="bi bi-telephone-fill"></i> {{ $section->title ?: 'اطلاعات تماس' }}</div>
                    <div class="doc-card-body">
                        @if($card->phone)
                            <div class="doc-contact-row"><i class="bi bi-telephone"></i><a href="tel:{{ $card->phone }}">{{ $card->phone }}</a></div>
                        @endif
                        @if($card->email)
                            <div class="doc-contact-row"><i class="bi bi-envelope"></i><a href="mailto:{{ $card->email }}">{{ $card->email }}</a></div>
                        @endif
                        @if($card->address)
                            <div class="doc-contact-row"><i class="bi bi-geo-alt"></i><span>{{ $card->address }}</span></div>
                        @endif
                    </div>
                </div>
            @endif

            @if($section->type === 'testimonials' && $card->testimonials->count())
                <div class="doc-card">
                    <div class="doc-card-head"><i class="bi bi-chat-quote-fill"></i> {{ $section->title ?: 'نظرات بیماران' }}</div>
                    <div class="doc-card-body">
                        @foreach($card->testimonials->sortBy('sort_order') as $t)
                            <div class="doc-review">
                                <div class="dr-header">
                                    <span class="dr-name">{{ $t->author_name }}</span>
                                    @if($t->rating)
                                        <span class="dr-stars">@for($i = 0; $i < $t->rating; $i++)★@endfor</span>
                                    @endif
                                </div>
                                <div class="dr-text">{{ $t->content }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'social' && $card->socialLinks->count())
                <div class="doc-card" style="text-align:center">
                    <div class="doc-card-head" style="justify-content:center"><i class="bi bi-share"></i> شبکه‌های اجتماعی</div>
                    <div class="doc-card-body">
                        <div class="doc-social">
                            @foreach($card->socialLinks->sortBy('sort_order') as $link)
                                @php
                                    $sC = ['instagram'=>'#E4405F','telegram'=>'#0088CC','aparat'=>'#ED8B00'];
                                    $sI = ['instagram'=>'instagram','telegram'=>'telegram','aparat'=>'play-circle'];
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
                <div class="doc-card" style="padding:0;overflow:hidden">
                    <div class="doc-map">
                        <iframe src="https://www.openstreetmap.org/export/embed.html?bbox={{ $card->map_lng - 0.01 }}%2C{{ $card->map_lat - 0.01 }}%2C{{ $card->map_lng + 0.01 }}%2C{{ $card->map_lat + 0.01 }}&layer=mapnik&marker={{ $card->map_lat }}%2C{{ $card->map_lng }}" width="100%" height="180" style="border:0" loading="lazy"></iframe>
                    </div>
                </div>
            @endif

            @if($section->type === 'custom' && $section->content)
                <div class="doc-card">
                    <div class="doc-card-body" style="padding-top:1.25rem;font-size:.88rem;line-height:1.8;color:#475569">{!! nl2br(e($section->content)) !!}</div>
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
                <div class="doc-card" style="text-align:center">
                    <div class="doc-card-head" style="justify-content:center"><i class="bi bi-qr-code"></i> کد QR</div>
                    <div class="doc-card-body">
                        <img src="data:image/png;base64,{{ $qrData }}" alt="QR" style="max-width:120px;border-radius:.5rem">
                    </div>
                </div>
            @endif
        @endif

        <div class="doc-footer">ساخته شده با <a href="/">کارت‌اکس</a></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
