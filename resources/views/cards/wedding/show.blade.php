@php
    $sections = $card->sections->where('is_visible', true)->sortBy('sort_order');
    $themeColor = $card->theme_color ?: '#b8860b';
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
        body{font-family:'Vazirmatn',sans-serif;background:linear-gradient(180deg,#fdf8f0,#f5ebe0);color:#3d2c1e;min-height:100vh}
        .wed-header{text-align:center;padding:3rem 1.5rem 2rem;position:relative}
        .wed-ornament{font-size:2rem;color:{{ $themeColor }};opacity:.4;margin-bottom:.5rem}
        .wed-header h2{font-size:1rem;font-weight:400;color:#8b7355;letter-spacing:2px;margin-bottom:.5rem}
        .wed-header h1{font-size:2.2rem;font-weight:300;color:{{ $themeColor }};line-height:1.4}
        .wed-header .amp{font-size:1.5rem;margin:0 .5rem;opacity:.5}
        .wed-date-box{display:inline-block;border:1px solid {{ $themeColor }}40;border-radius:1rem;padding:.75rem 2rem;margin-top:1rem;background:rgba(255,255,255,.5);backdrop-filter:blur(4px)}
        .wed-date-box .day{font-size:1.8rem;font-weight:700;color:{{ $themeColor }};line-height:1}
        .wed-date-box .month{font-size:.85rem;color:#8b7355}
        .wed-countdown{display:flex;justify-content:center;gap:1rem;margin-top:1.25rem}
        .wed-countdown .cd-item{text-align:center;background:rgba(255,255,255,.6);backdrop-filter:blur(4px);border-radius:.75rem;padding:.5rem .75rem;min-width:60px;border:1px solid {{ $themeColor }}20}
        .wed-countdown .cd-num{font-size:1.5rem;font-weight:700;color:{{ $themeColor }};line-height:1}
        .wed-countdown .cd-label{font-size:.65rem;color:#8b7355}
        .wed-container{max-width:520px;margin:0 auto;padding:0 1rem 2rem}
        .wed-section{background:rgba(255,255,255,.7);backdrop-filter:blur(8px);border-radius:1.25rem;padding:1.5rem;margin-bottom:1rem;border:1px solid {{ $themeColor }}15;box-shadow:0 2px 16px rgba(0,0,0,.04)}
        .wed-section-title{text-align:center;font-size:.95rem;font-weight:600;color:{{ $themeColor }};margin-bottom:1rem;display:flex;align-items:center;justify-content:center;gap:.5rem}
        .wed-section-title::before,.wed-section-title::after{content:'';flex:1;height:1px;background:linear-gradient(90deg,transparent,{{ $themeColor }}40,transparent)}
        .wed-gallery-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:.5rem}
        .wed-gallery-grid img{width:100%;aspect-ratio:1;object-fit:cover;border-radius:.75rem;transition:transform .3s}
        .wed-gallery-grid img:hover{transform:scale(1.05)}
        .wed-timeline{position:relative;padding-right:1.5rem}
        .wed-timeline::before{content:'';position:absolute;right:5px;top:0;bottom:0;width:2px;background:{{ $themeColor }}30}
        .wed-timeline-item{position:relative;padding:.75rem 0;padding-right:1rem}
        .wed-timeline-item::before{content:'';position:absolute;right:-2px;top:1rem;width:10px;height:10px;border-radius:50%;background:{{ $themeColor }};border:2px solid #fff}
        .wed-timeline-item .tl-title{font-weight:600;font-size:.88rem}
        .wed-timeline-item .tl-desc{font-size:.8rem;color:#8b7355;margin-top:.2rem}
        .wed-social-row{display:flex;flex-wrap:wrap;justify-content:center;gap:.5rem}
        .wed-social-pill{display:inline-flex;align-items:center;gap:.35rem;padding:.4rem .85rem;border-radius:2rem;color:#fff;font-size:.78rem;text-decoration:none;transition:transform .2s}
        .wed-social-pill:hover{transform:translateY(-2px);color:#fff}
        .wed-map{border-radius:1rem;overflow:hidden;height:180px}
        .wed-footer{text-align:center;padding:2rem 1rem;color:#b8a88a;font-size:.78rem}
        .wed-footer a{color:{{ $themeColor }};text-decoration:none}
        .wed-rsvp-form input,.wed-rsvp-form select{border-radius:.75rem;border:1px solid {{ $themeColor }}30;padding:.6rem 1rem;font-size:.88rem;width:100%;margin-bottom:.75rem;background:rgba(255,255,255,.8)}
        .wed-rsvp-form button{background:{{ $themeColor }};color:#fff;border:none;border-radius:.75rem;padding:.65rem 2rem;font-size:.9rem;width:100%;cursor:pointer;transition:opacity .2s}
        .wed-rsvp-form button:hover{opacity:.9}
    </style>
</head>
<body>
    <div class="wed-header">
        <div class="wed-ornament">❋</div>
        <h2>دعوت نامه عروسی</h2>
        <h1>
            {{ $card->title }}
            @if($meta['partner_name'] ?? false)
                <span class="amp">&</span> {{ $meta['partner_name'] }}
            @endif
        </h1>
        @if($meta['wedding_date'] ?? false)
            @php
                try { $wdCarbon = \Carbon\Carbon::parse($meta['wedding_date']); } catch (\Exception $e) { $wdCarbon = null; }
            @endphp
            @if($wdCarbon)
            <div class="wed-date-box">
                <div class="day">{{ \Morilog\Jalali\Jalalian::fromCarbon($wdCarbon)->format('d') }}</div>
                <div class="month">{{ \Morilog\Jalali\Jalalian::fromCarbon($wdCarbon)->format('F Y') }}</div>
            </div>
            @endif
        @endif
        @if($meta['wedding_date'] ?? false)
            @php $wd = $wdCarbon ?? null; @endphp
            @if($wd)
            <div class="wed-countdown" id="countdown">
                <div class="cd-item"><div class="cd-num" id="cd-days">--</div><div class="cd-label">روز</div></div>
                <div class="cd-item"><div class="cd-num" id="cd-hours">--</div><div class="cd-label">ساعت</div></div>
                <div class="cd-item"><div class="cd-num" id="cd-mins">--</div><div class="cd-label">دقیقه</div></div>
                <div class="cd-item"><div class="cd-num" id="cd-secs">--</div><div class="cd-label">ثانیه</div></div>
            </div>
            @push('scripts')
            <script>
            (function(){
                var target = new Date('{{ $wd->toIso8601String() }}').getTime();
                function update(){
                    var now = Date.now(), diff = target - now;
                    if(diff <= 0) return;
                    document.getElementById('cd-days').textContent = Math.floor(diff/86400000);
                    document.getElementById('cd-hours').textContent = Math.floor((diff%86400000)/3600000);
                    document.getElementById('cd-mins').textContent = Math.floor((diff%3600000)/60000);
                    document.getElementById('cd-secs').textContent = Math.floor((diff%60000)/1000);
                }
                update(); setInterval(update, 1000);
            })();
            </script>
            @endpush
        @endif
        @endif
        @if($card->description)
            <p style="margin-top:1.25rem;font-size:.9rem;color:#8b7355;max-width:400px;margin-left:auto;margin-right:auto;line-height:1.8">{{ $card->description }}</p>
        @endif
    </div>

    <div class="wed-container">
        @foreach($sections as $section)
            @if($section->type === 'contact')
                <div class="wed-section">
                    <div class="wed-section-title"><i class="bi bi-envelope-heart"></i> {{ $section->title ?: 'اطلاعات تماس' }}</div>
                    @if($card->phone)
                        <div style="text-align:center;margin-bottom:.5rem"><a href="tel:{{ $card->phone }}" style="color:#3d2c1e;text-decoration:none;font-size:.9rem"><i class="bi bi-telephone" style="color:{{ $themeColor }}"></i> {{ $card->phone }}</a></div>
                    @endif
                    @if($card->email)
                        <div style="text-align:center;margin-bottom:.5rem"><a href="mailto:{{ $card->email }}" style="color:#3d2c1e;text-decoration:none;font-size:.9rem"><i class="bi bi-envelope" style="color:{{ $themeColor }}"></i> {{ $card->email }}</a></div>
                    @endif
                    @if($card->address)
                        <div style="text-align:center"><span style="color:#8b7355;font-size:.85rem"><i class="bi bi-geo-alt" style="color:{{ $themeColor }}"></i> {{ $card->address }}</span></div>
                    @endif
                </div>
            @endif

            @if($section->type === 'social' && $card->socialLinks->count())
                <div class="wed-section">
                    <div class="wed-section-title"><i class="bi bi-share"></i> {{ $section->title ?: 'شبکه‌های اجتماعی' }}</div>
                    <div class="wed-social-row">
                        @foreach($card->socialLinks->sortBy('sort_order') as $link)
                            @php
                                $sC = ['instagram'=>'#E4405F','telegram'=>'#0088CC','whatsapp'=>'#25D366'];
                                $sI = ['instagram'=>'instagram','telegram'=>'telegram','whatsapp'=>'whatsapp'];
                            @endphp
                            <a href="{{ $link->url }}" class="wed-social-pill" style="background:{{ $sC[$link->platform] ?? '#6c757d' }}" target="_blank">
                                <i class="bi bi-{{ $sI[$link->platform] ?? 'globe' }}"></i> {{ ucfirst($link->platform) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'gallery' && $card->galleryItems->count())
                <div class="wed-section">
                    <div class="wed-section-title"><i class="bi bi-images"></i> {{ $section->title ?: 'گالری' }}</div>
                    <div class="wed-gallery-grid">
                        @foreach($card->galleryItems->sortBy('sort_order') as $item)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->caption }}" loading="lazy">
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'custom' && $section->content)
                <div class="wed-section">
                    <div style="text-align:center;font-size:.9rem;line-height:2;color:#5a4a3a">{!! nl2br(e($section->content)) !!}</div>
                </div>
            @endif
        @endforeach

        @if($card->map_lat && $card->map_lng)
            <div class="wed-section">
                <div class="wed-section-title"><i class="bi bi-geo-alt"></i> محل برگزاری</div>
                <div class="wed-map">
                    <iframe src="https://www.openstreetmap.org/export/embed.html?bbox={{ $card->map_lng - 0.01 }}%2C{{ $card->map_lat - 0.01 }}%2C{{ $card->map_lng + 0.01 }}%2C{{ $card->map_lat + 0.01 }}&layer=mapnik&marker={{ $card->map_lat }}%2C{{ $card->map_lng }}" width="100%" height="180" style="border:0" loading="lazy"></iframe>
                </div>
            </div>
        @endif

        @if($card->qrCodes->where('is_active', true)->count())
            @php $qr = $card->qrCodes->where('is_active', true)->first(); @endphp
            @if($qr)
                @php
                    $qrOpts = new \Chillerlan\QRCode\QROptions(['outputInterface' => \Chillerlan\QRCode\Output\QRGdImagePNG::class, 'eccLevel' => \Chillerlan\QRCode\QRCode::ECC_M, 'scale' => 5, 'imageBase64' => false, 'bgColor' => '#FFFFFF']);
                    $qrGen = new \Chillerlan\QRCode\QRCode($qrOpts);
                    $qrData = base64_encode($qrGen->render(route('qr.redirect', $qr->unique_code)));
                @endphp
                <div class="wed-section" style="text-align:center">
                    <img src="data:image/png;base64,{{ $qrData }}" alt="QR" style="max-width:120px;border-radius:.5rem">
                </div>
            @endif
        @endif

        <div class="wed-footer">ساخته شده با <a href="/">کارت‌اکس</a></div>
    </div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
