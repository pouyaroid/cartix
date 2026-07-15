@php
    $sections = $card->sections->where('is_visible', true)->sortBy('sort_order');
    $themeColor = $card->theme_color ?: '#d4af37';
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
        body{font-family:'Vazirmatn',sans-serif;background:linear-gradient(135deg,#fdf8e8 0%,#f5e6b8 100%);color:#4a3520;min-height:100vh}
        .gld-header{text-align:center;padding:4rem 1.5rem 3rem;position:relative}
        .gld-header::before{content:'';position:absolute;top:0;left:50%;transform:translateX(-50%);width:300px;height:2px;background:linear-gradient(90deg,transparent,{{ $themeColor }},transparent)}
        .gld-header::after{content:'';position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:300px;height:2px;background:linear-gradient(90deg,transparent,{{ $themeColor }},transparent)}
        .gld-ornament{font-size:2rem;color:{{ $themeColor }};margin-bottom:1rem}
        .gld-header h2{font-size:.75rem;font-weight:400;color:{{ $themeColor }};letter-spacing:4px;text-transform:uppercase;margin-bottom:1rem}
        .gld-header h1{font-size:2.5rem;font-weight:300;color:#3d2c1e;line-height:1.3}
        .gld-header .amp{font-size:1.8rem;color:{{ $themeColor }};margin:0 .5rem;font-style:italic}
        .gld-border{width:150px;height:1px;background:linear-gradient(90deg,transparent,{{ $themeColor }},transparent);margin:1.5rem auto}
        .gld-date{font-size:1rem;color:#8b7355;font-weight:300;letter-spacing:1px}
        .gld-countdown{display:flex;justify-content:center;gap:1.5rem;margin-top:2rem}
        .gld-countdown .cd-item{text-align:center;background:rgba(255,255,255,.6);backdrop-filter:blur(8px);padding:1rem;border:1px solid {{ $themeColor }}30;min-width:70px}
        .gld-countdown .cd-num{font-size:2rem;font-weight:200;color:{{ $themeColor }};line-height:1}
        .gld-countdown .cd-label{font-size:.6rem;color:#8b7355;margin-top:.25rem}
        .gld-container{max-width:500px;margin:0 auto;padding:0 1.5rem 2rem}
        .gld-section{background:rgba(255,255,255,.7);backdrop-filter:blur(10px);border:1px solid {{ $themeColor }}25;padding:2rem;margin-bottom:1.5rem;position:relative}
        .gld-section::before{content:'';position:absolute;top:5px;left:5px;right:5px;bottom:5px;border:1px solid {{ $themeColor }}10;pointer-events:none}
        .gld-section-title{text-align:center;font-size:.75rem;font-weight:500;color:{{ $themeColor }};letter-spacing:3px;text-transform:uppercase;margin-bottom:1.5rem}
        .gld-gallery{display:grid;grid-template-columns:repeat(2,1fr);gap:.75rem}
        .gld-gallery img{width:100%;aspect-ratio:1;object-fit:cover;border:2px solid {{ $themeColor }}30;transition:all .3s}
        .gld-gallery img:hover{border-color:{{ $themeColor }};transform:scale(1.02)}
        .gld-timeline{position:relative;padding-right:2rem}
        .gld-timeline::before{content:'';position:absolute;right:0;top:0;bottom:0;width:1px;background:{{ $themeColor }}30}
        .gld-timeline-item{position:relative;padding:1rem 0}
        .gld-timeline-item::before{content:'';position:absolute;right:-4px;top:1.2rem;width:8px;height:8px;background:{{ $themeColor }};transform:rotate(45deg)}
        .gld-timeline-item .tl-title{font-weight:600;font-size:.9rem;color:#3d2c1e}
        .gld-timeline-item .tl-desc{font-size:.8rem;color:#8b7355;margin-top:.25rem}
        .gld-social-row{display:flex;flex-wrap:wrap;justify-content:center;gap:.75rem}
        .gld-social-pill{display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1rem;background:{{ $themeColor }}10;color:{{ $themeColor }};border:1px solid {{ $themeColor }}30;font-size:.78rem;text-decoration:none;transition:all .2s}
        .gld-social-pill:hover{background:{{ $themeColor }};color:#fff;border-color:{{ $themeColor }}}
        .gld-map{height:200px;border:2px solid {{ $themeColor }}20;overflow:hidden}
        .gld-footer{text-align:center;padding:2rem 1rem;color:#b8a88a;font-size:.75rem;letter-spacing:2px}
        .gld-footer a{color:{{ $themeColor }};text-decoration:none}
        .gld-contact{text-align:center}
        .gld-contact a,.gld-contact span{color:#3d2c1e;text-decoration:none;font-size:.88rem;display:block;margin-bottom:.5rem}
        .gld-contact i{color:{{ $themeColor }};margin-left:.5rem}
        .gld-qr{text-align:center;padding:1.5rem;background:rgba(255,255,255,.5);border:1px solid {{ $themeColor }}20}
        .gld-qr img{max-width:100px}
        .gld-rsvp input,.gld-rsvp select{border:1px solid {{ $themeColor }}30;background:rgba(255,255,255,.5);padding:.7rem 1rem;width:100%;margin-bottom:1rem;font-size:.88rem;font-family:'Vazirmatn',sans-serif;color:#3d2c1e}
        .gld-rsvp button{background:{{ $themeColor }};color:#fff;border:none;padding:.7rem 2rem;width:100%;font-size:.9rem;cursor:pointer;transition:opacity .2s}
        .gld-rsvp button:hover{opacity:.9}
    </style>
</head>
<body>
    <div class="gld-header">
        <div class="gld-ornament">✧</div>
        <h2>دعوت نامه عروسی</h2>
        <h1>
            {{ $card->title }}
            @if($meta['partner_name'] ?? false)
                <span class="amp">&</span> {{ $meta['partner_name'] }}
            @endif
        </h1>
        <div class="gld-border"></div>
        @if($meta['wedding_date'] ?? false)
            @php try { $wdCarbon = \Carbon\Carbon::parse($meta['wedding_date']); } catch (\Exception $e) { $wdCarbon = null; } @endphp
            @if($wdCarbon)
                <div class="gld-date">{{ \Morilog\Jalali\Jalalian::fromCarbon($wdCarbon)->format('l d F Y') }}</div>
            @endif
        @endif
        @if($meta['wedding_date'] ?? false)
            @php $wd = $wdCarbon ?? null; @endphp
            @if($wd)
            <div class="gld-countdown" id="countdown">
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
            <p style="margin-top:1.5rem;font-size:.9rem;color:#8b7355;max-width:400px;margin-left:auto;margin-right:auto;line-height:1.8;font-style:italic">{{ $card->description }}</p>
        @endif
    </div>

    <div class="gld-container">
        @foreach($sections as $section)
            @if($section->type === 'contact')
                <div class="gld-section">
                    <div class="gld-section-title">{{ $section->title ?: 'تماس' }}</div>
                    <div class="gld-contact">
                        @if($card->phone)
                            <a href="tel:{{ $card->phone }}"><i class="bi bi-telephone"></i> {{ $card->phone }}</a>
                        @endif
                        @if($card->email)
                            <a href="mailto:{{ $card->email }}"><i class="bi bi-envelope"></i> {{ $card->email }}</a>
                        @endif
                        @if($card->address)
                            <span><i class="bi bi-geo-alt"></i> {{ $card->address }}</span>
                        @endif
                    </div>
                </div>
            @endif

            @if($section->type === 'social' && $card->socialLinks->count())
                <div class="gld-section">
                    <div class="gld-section-title">{{ $section->title ?: 'شبکه‌ها' }}</div>
                    <div class="gld-social-row">
                        @foreach($card->socialLinks->sortBy('sort_order') as $link)
                            <a href="{{ $link->url }}" class="gld-social-pill" target="_blank">
                                <i class="bi bi-{{ $link->platform === 'instagram' ? 'instagram' : ($link->platform === 'telegram' ? 'telegram' : 'globe') }}"></i> {{ ucfirst($link->platform) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'gallery' && $card->galleryItems->count())
                <div class="gld-section">
                    <div class="gld-section-title">{{ $section->title ?: 'گالری' }}</div>
                    <div class="gld-gallery">
                        @foreach($card->galleryItems->sortBy('sort_order') as $item)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->caption }}" loading="lazy">
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'timeline' && $section->items && $section->items->count())
                <div class="gld-section">
                    <div class="gld-section-title">{{ $section->title ?: 'داستان عشق' }}</div>
                    <div class="gld-timeline">
                        @foreach($section->items->sortBy('sort_order') as $item)
                            <div class="gld-timeline-item">
                                <div class="tl-title">{{ $item->name }}</div>
                                <div class="tl-desc">{{ $item->description }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'rsvp')
                <div class="gld-section">
                    <div class="gld-section-title">{{ $section->title ?: 'حضور' }}</div>
                    <div class="gld-rsvp">
                        <input type="text" placeholder="نام شما">
                        <input type="tel" placeholder="شماره تماس">
                        <select>
                            <option>حضور دارم</option>
                            <option>غایب هستم</option>
                        </select>
                        <button>ارسال</button>
                    </div>
                </div>
            @endif

            @if($section->type === 'custom' && $section->content)
                <div class="gld-section">
                    <div style="text-align:center;font-size:.9rem;line-height:2;color:#5a4a3a">{!! nl2br(e($section->content)) !!}</div>
                </div>
            @endif
        @endforeach

        @if($card->map_lat && $card->map_lng)
            <div class="gld-section">
                <div class="gld-section-title">محل برگزاری</div>
                <div class="gld-map">
                    <iframe src="https://www.openstreetmap.org/export/embed.html?bbox={{ $card->map_lng - 0.01 }}%2C{{ $card->map_lat - 0.01 }}%2C{{ $card->map_lng + 0.01 }}%2C{{ $card->map_lat + 0.01 }}&layer=mapnik&marker={{ $card->map_lat }}%2C{{ $card->map_lng }}" width="100%" height="200" style="border:0" loading="lazy"></iframe>
                </div>
            </div>
        @endif

        @if($card->qrCodes->where('is_active', true)->count())
            @php $qr = $card->qrCodes->where('is_active', true)->first(); @endphp
            @if($qr)
                @php
                    $qrOpts = new \Chillerlan\QRCode\QROptions(['outputInterface' => \Chillerlan\QRCode\Output\QRGdImagePNG::class, 'eccLevel' => \Chillerlan\QRCode\QRCode::ECC_M, 'scale' => 5, 'imageBase64' => false, 'bgColor' => '#fdf8e8', 'moduleValues' => [\Chillerlan\QRCode\Data\QRMatrix::M_DATA_DARK => '#d4af37', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DARK => '#d4af37', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DOT => '#d4af37', \Chillerlan\QRCode\Data\QRMatrix::M_ALIGNMENT_DARK => '#d4af37']]);
                    $qrGen = new \Chillerlan\QRCode\QRCode($qrOpts);
                    $qrData = base64_encode($qrGen->render(route('qr.redirect', $qr->unique_code)));
                @endphp
                <div class="gld-qr">
                    <img src="data:image/png;base64,{{ $qrData }}" alt="QR">
                </div>
            @endif
        @endif

        <div class="gld-footer">ساخته شده با <a href="/">کارت‌اکس</a></div>
    </div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
