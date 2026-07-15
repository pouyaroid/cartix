@php
    $sections = $card->sections->where('is_visible', true)->sortBy('sort_order');
    $themeColor = $card->theme_color ?: '#c0392b';
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
        body{font-family:'Vazirmatn',sans-serif;background:#1a1a1a;color:#f5f5f5}
        .rest-hero{position:relative;height:320px;overflow:hidden}
        .rest-hero img{width:100%;height:100%;object-fit:cover}
        .rest-hero .overlay{position:absolute;inset:0;background:linear-gradient(transparent 40%,rgba(0,0,0,.85))}
        .rest-hero .hero-content{position:absolute;bottom:0;left:0;right:0;padding:1.5rem}
        .rest-hero .rest-logo{width:70px;height:70px;border-radius:50%;border:3px solid {{ $themeColor }};object-fit:cover;background:#222}
        .rest-hero h1{font-size:1.8rem;font-weight:700;margin-top:.75rem}
        .rest-hero .rest-type{color:{{ $themeColor }};font-size:.85rem;font-weight:500}
        .rest-hero .rest-desc{color:rgba(255,255,255,.7);font-size:.85rem;margin-top:.25rem}
        .rest-container{max-width:560px;margin:0 auto;padding:0 1rem 2rem}
        .rest-hours-bar{display:flex;align-items:center;justify-content:center;gap:.5rem;padding:.75rem;margin:-1rem auto 1rem;max-width:500px;background:#252525;border-radius:1rem;border:1px solid #333;position:relative;z-index:1}
        .rest-hours-bar .dot{width:8px;height:8px;border-radius:50%;background:#2ecc71;animation:pulse 2s infinite}
        @keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}
        .rest-hours-bar span{font-size:.85rem;color:#ccc}
        .rest-card{background:#252525;border-radius:1rem;padding:1.25rem;margin-bottom:1rem;border:1px solid #333}
        .rest-card-title{font-size:.95rem;font-weight:700;color:{{ $themeColor }};margin-bottom:1rem;display:flex;align-items:center;gap:.5rem}
        .menu-category{margin-bottom:1rem}
        .menu-cat-name{font-size:.82rem;font-weight:600;color:#999;text-transform:uppercase;letter-spacing:1px;margin-bottom:.5rem}
        .menu-item{display:flex;justify-content:space-between;align-items:flex-start;padding:.6rem 0;border-bottom:1px solid #333}
        .menu-item:last-child{border-bottom:none}
        .menu-item .mi-name{font-weight:600;font-size:.88rem}
        .menu-item .mi-desc{font-size:.75rem;color:#888;margin-top:.15rem}
        .menu-item .mi-price{font-weight:700;color:{{ $themeColor }};font-size:.9rem;white-space:nowrap}
        .rest-gallery{display:grid;grid-template-columns:repeat(3,1fr);gap:.5rem}
        .rest-gallery img{width:100%;aspect-ratio:1;object-fit:cover;border-radius:.75rem}
        .rest-social{display:flex;flex-wrap:wrap;gap:.5rem;justify-content:center}
        .rest-social a{display:inline-flex;align-items:center;gap:.35rem;padding:.4rem .85rem;border-radius:2rem;color:#fff;font-size:.78rem;text-decoration:none;transition:transform .2s}
        .rest-social a:hover{transform:translateY(-2px);color:#fff}
        .rest-contact-row{display:flex;align-items:center;gap:.75rem;padding:.6rem 0;border-bottom:1px solid #333}
        .rest-contact-row:last-child{border-bottom:none}
        .rest-contact-row i{color:{{ $themeColor }};width:20px;text-align:center}
        .rest-contact-row a,.rest-contact-row span{color:#ccc;text-decoration:none;font-size:.88rem}
        .rest-btn{display:block;text-align:center;padding:.75rem;border-radius:.75rem;background:{{ $themeColor }};color:#fff;text-decoration:none;font-weight:600;font-size:.95rem;margin-top:.75rem;transition:opacity .2s}
        .rest-btn:hover{opacity:.9;color:#fff}
        .rest-map{border-radius:1rem;overflow:hidden;height:180px}
        .rest-footer{text-align:center;padding:2rem 1rem;color:#666;font-size:.78rem}
        .rest-footer a{color:{{ $themeColor }};text-decoration:none}
        .rest-reviews{display:flex;align-items:center;gap:.5rem;margin-top:.5rem}
        .rest-reviews .stars{color:#f1c40f;font-size:1.1rem}
        .rest-reviews .rating{font-weight:700;font-size:1.1rem}
        .rest-reviews .count{color:#888;font-size:.82rem}
    </style>
</head>
<body>
    <div class="rest-hero">
        @if($card->cover_image)
            <img src="{{ asset('storage/' . $card->cover_image) }}" alt="{{ $card->title }}">
        @else
            <div style="width:100%;height:100%;background:linear-gradient(135deg,{{ $themeColor }}30,#1a1a1a)"></div>
        @endif
        <div class="overlay"></div>
        <div class="hero-content">
            @if($card->logo)
                <img src="{{ asset('storage/' . $card->logo) }}" alt="لوگو" class="rest-logo">
            @endif
            <div class="rest-type">{{ $card->description ?: 'رستوران' }}</div>
            <h1>{{ $card->title }}</h1>
            @if($meta['rating'] ?? false)
                <div class="rest-reviews">
                    <span class="stars">★★★★★</span>
                    <span class="rating">{{ $meta['rating'] }}</span>
                    <span class="count">({{ $meta['review_count'] ?? 0 }} نظر)</span>
                </div>
            @endif
            @if($meta['working_hours'] ?? false)
                <div class="rest-hours-bar">
                    <span class="dot"></span>
                    <span>{{ $meta['working_hours'] }}</span>
                </div>
            @endif
        </div>
    </div>

    <div class="rest-container">
        @if($card->phone)
            <a href="tel:{{ $card->phone }}" class="rest-btn"><i class="bi bi-telephone-fill ms-1"></i> رزرو میز</a>
        @endif

        @foreach($sections as $section)
            @if($section->type === 'products' && $card->products->count())
                <div class="rest-card">
                    <div class="rest-card-title"><i class="bi bi-book"></i> {{ $section->title ?: 'منو' }}</div>
                    @php $grouped = $card->products->sortBy('sort_order')->groupBy(fn($p) => $p->description ?? 'عمومی'); @endphp
                    @foreach($grouped as $catName => $items)
                        <div class="menu-category">
                            <div class="menu-cat-name">{{ $catName }}</div>
                            @foreach($items as $product)
                                <div class="menu-item">
                                    <div>
                                        <div class="mi-name">{{ $product->name }}</div>
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" style="width:50px;height:50px;border-radius:.5rem;object-fit:cover;margin-top:.25rem" alt="" loading="lazy">
                                        @endif
                                    </div>
                                    @if($product->price)
                                        <div class="mi-price">{{ number_format($product->price) }} <span style="font-size:.7rem;font-weight:400">تومان</span></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif

            @if($section->type === 'gallery' && $card->galleryItems->count())
                <div class="rest-card">
                    <div class="rest-card-title"><i class="bi bi-camera"></i> {{ $section->title ?: 'گالری' }}</div>
                    <div class="rest-gallery">
                        @foreach($card->galleryItems->sortBy('sort_order') as $item)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="" loading="lazy">
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'social' && $card->socialLinks->count())
                <div class="rest-card" style="text-align:center">
                    <div class="rest-card-title" style="justify-content:center"><i class="bi bi-instagram"></i> ما را دنبال کنید</div>
                    <div class="rest-social">
                        @foreach($card->socialLinks->sortBy('sort_order') as $link)
                            @php
                                $sC = ['instagram'=>'#E4405F','telegram'=>'#0088CC','whatsapp'=>'#25D366','aparat'=>'#ED8B00'];
                                $sI = ['instagram'=>'instagram','telegram'=>'telegram','whatsapp'=>'whatsapp','aparat'=>'play-circle'];
                            @endphp
                            <a href="{{ $link->url }}" style="background:{{ $sC[$link->platform] ?? '#6c757d' }}" target="_blank">
                                <i class="bi bi-{{ $sI[$link->platform] ?? 'globe' }}"></i> {{ ucfirst($link->platform) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'contact')
                <div class="rest-card">
                    <div class="rest-card-title"><i class="bi bi-info-circle"></i> {{ $section->title ?: 'اطلاعات' }}</div>
                    @if($card->phone)
                        <div class="rest-contact-row"><i class="bi bi-telephone"></i><a href="tel:{{ $card->phone }}">{{ $card->phone }}</a></div>
                    @endif
                    @if($card->email)
                        <div class="rest-contact-row"><i class="bi bi-envelope"></i><a href="mailto:{{ $card->email }}">{{ $card->email }}</a></div>
                    @endif
                    @if($card->address)
                        <div class="rest-contact-row"><i class="bi bi-geo-alt"></i><span>{{ $card->address }}</span></div>
                    @endif
                    @if($card->website)
                        <div class="rest-contact-row"><i class="bi bi-globe"></i><a href="{{ $card->website }}" target="_blank">{{ $card->website }}</a></div>
                    @endif
                </div>
            @endif

            @if($section->type === 'map' && $card->map_lat && $card->map_lng)
                <div class="rest-card" style="padding:0;overflow:hidden">
                    <div class="rest-map">
                        <iframe src="https://www.openstreetmap.org/export/embed.html?bbox={{ $card->map_lng - 0.01 }}%2C{{ $card->map_lat - 0.01 }}%2C{{ $card->map_lng + 0.01 }}%2C{{ $card->map_lat + 0.01 }}&layer=mapnik&marker={{ $card->map_lat }}%2C{{ $card->map_lng }}" width="100%" height="180" style="border:0" loading="lazy"></iframe>
                    </div>
                </div>
            @endif

            @if($section->type === 'custom' && $section->content)
                <div class="rest-card">
                    <div style="font-size:.88rem;line-height:1.8;color:#ccc">{!! nl2br(e($section->content)) !!}</div>
                </div>
            @endif
        @endforeach

        @if($card->qrCodes->where('is_active', true)->count())
            @php $qr = $card->qrCodes->where('is_active', true)->first(); @endphp
            @if($qr)
                @php
                    $qrOpts = new \Chillerlan\QRCode\QROptions(['outputInterface' => \Chillerlan\QRCode\Output\QRGdImagePNG::class, 'eccLevel' => \Chillerlan\QRCode\QRCode::ECC_M, 'scale' => 5, 'imageBase64' => false, 'bgColor' => '#252525']);
                    $qrGen = new \Chillerlan\QRCode\QRCode($qrOpts);
                    $qrData = base64_encode($qrGen->render(route('qr.redirect', $qr->unique_code)));
                @endphp
                <div class="rest-card" style="text-align:center">
                    <img src="data:image/png;base64,{{ $qrData }}" alt="QR" style="max-width:120px;border-radius:.5rem">
                </div>
            @endif
        @endif

        <div class="rest-footer">ساخته شده با <a href="/">کارت‌اکس</a></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
