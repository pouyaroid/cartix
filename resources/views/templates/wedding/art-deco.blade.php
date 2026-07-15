@php
    $sections = $card->sections->where('is_visible', true)->sortBy('sort_order');
    $themeColor = $card->theme_color ?: '#c9a0dc';
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
        body{font-family:'Vazirmatn',sans-serif;background:#1a1a2e;color:#e8d5f5;min-height:100vh}
        .dec-header{text-align:center;padding:4rem 1.5rem 3rem;position:relative;border-bottom:2px solid {{ $themeColor }}30}
        .dec-header::before{content:'';position:absolute;top:0;left:50%;transform:translateX(-50%);width:200px;height:2px;background:linear-gradient(90deg,transparent,{{ $themeColor }},transparent)}
        .dec-header::after{content:'';position:absolute;bottom:-2px;left:50%;transform:translateX(-50%);width:200px;height:2px;background:linear-gradient(90deg,transparent,{{ $themeColor }},transparent)}
        .dec-pattern{font-size:1.5rem;color:{{ $themeColor }};margin-bottom:1rem;letter-spacing:.5rem}
        .dec-header h2{font-size:.75rem;font-weight:400;color:{{ $themeColor }};letter-spacing:4px;text-transform:uppercase;margin-bottom:1rem}
        .dec-header h1{font-size:2.5rem;font-weight:300;color:#fff;line-height:1.3}
        .dec-header .amp{font-size:1.8rem;color:{{ $themeColor }};margin:0 .5rem;font-style:italic}
        .dec-border{width:150px;height:1px;background:linear-gradient(90deg,transparent,{{ $themeColor }},transparent);margin:1.5rem auto}
        .dec-date{font-size:1rem;color:#b8a0c8;font-weight:300;letter-spacing:2px}
        .dec-countdown{display:flex;justify-content:center;gap:1.5rem;margin-top:2rem}
        .dec-countdown .cd-item{text-align:center;min-width:70px}
        .dec-countdown .cd-num{font-size:2rem;font-weight:200;color:{{ $themeColor }};line-height:1}
        .dec-countdown .cd-label{font-size:.6rem;color:#b8a0c8;letter-spacing:1px;margin-top:.25rem}
        .dec-countdown .cd-divider{color:{{ $themeColor }}30;font-size:1.5rem;line-height:2}
        .dec-container{max-width:500px;margin:0 auto;padding:0 1.5rem 2rem}
        .dec-section{background:rgba(255,255,255,.03);border:1px solid {{ $themeColor }}15;padding:2rem;margin-bottom:1.5rem;position:relative}
        .dec-section::before{content:'';position:absolute;top:5px;left:5px;right:5px;bottom:5px;border:1px solid {{ $themeColor }}08;pointer-events:none}
        .dec-section-title{text-align:center;font-size:.7rem;font-weight:500;color:{{ $themeColor }};letter-spacing:3px;text-transform:uppercase;margin-bottom:1.5rem}
        .dec-gallery{display:grid;grid-template-columns:repeat(2,1fr);gap:.75rem}
        .dec-gallery img{width:100%;aspect-ratio:1;object-fit:cover;border:2px solid {{ $themeColor }}20;transition:all .4s}
        .dec-gallery img:hover{border-color:{{ $themeColor }};transform:scale(1.02)}
        .dec-timeline{position:relative;padding-right:2rem}
        .dec-timeline::before{content:'';position:absolute;right:0;top:0;bottom:0;width:1px;background:{{ $themeColor }}30}
        .dec-timeline-item{position:relative;padding:1rem 0}
        .dec-timeline-item::before{content:'';position:absolute;right:-4px;top:1.2rem;width:8px;height:8px;background:{{ $themeColor }};transform:rotate(45deg)}
        .dec-timeline-item .tl-title{font-weight:600;font-size:.9rem;color:#fff}
        .dec-timeline-item .tl-desc{font-size:.8rem;color:#b8a0c8;margin-top:.25rem}
        .dec-social-row{display:flex;flex-wrap:wrap;justify-content:center;gap:.75rem}
        .dec-social-pill{display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1rem;border:1px solid {{ $themeColor }}25;color:{{ $themeColor }};font-size:.78rem;text-decoration:none;transition:all .3s;letter-spacing:.5px}
        .dec-social-pill:hover{background:{{ $themeColor }};color:#1a1a2e;border-color:{{ $themeColor }}}
        .dec-map{height:200px;border:1px solid {{ $themeColor }}15;overflow:hidden}
        .dec-footer{text-align:center;padding:3rem 1rem;color:#4a4a6a;font-size:.7rem;letter-spacing:2px;text-transform:uppercase}
        .dec-footer a{color:{{ $themeColor }};text-decoration:none}
        .dec-contact{text-align:center}
        .dec-contact a,.dec-contact span{color:#ccc;text-decoration:none;font-size:.88rem;display:block;margin-bottom:.5rem}
        .dec-contact i{color:{{ $themeColor }};margin-left:.5rem}
        .dec-qr{text-align:center;padding:1.5rem;border:1px solid {{ $themeColor }}15}
        .dec-qr img{max-width:100px}
        .dec-rsvp input,.dec-rsvp select{background:transparent;border:1px solid {{ $themeColor }}25;color:#fff;padding:.7rem 1rem;width:100%;margin-bottom:1rem;font-size:.88rem;font-family:'Vazirmatn',sans-serif}
        .dec-rsvp input::placeholder{color:#666}
        .dec-rsvp button{background:{{ $themeColor }};color:#1a1a2e;border:none;padding:.8rem 2rem;width:100%;font-size:.9rem;font-weight:600;cursor:pointer;letter-spacing:1px;text-transform:uppercase;transition:all .3s}
        .dec-rsvp button:hover{background:#fff}
    </style>
</head>
<body>
    <div class="dec-header">
        <div class="dec-pattern">◆ ◇ ◆ ◇ ◆</div>
        <h2>دعوت نامه عروسی</h2>
        <h1>
            {{ $card->title }}
            @if($meta['partner_name'] ?? false)
                <span class="amp">&</span> {{ $meta['partner_name'] }}
            @endif
        </h1>
        <div class="dec-border"></div>
        @if($meta['wedding_date'] ?? false)
            @php try { $wdCarbon = \Carbon\Carbon::parse($meta['wedding_date']); } catch (\Exception $e) { $wdCarbon = null; } @endphp
            @if($wdCarbon)
                <div class="dec-date">{{ \Morilog\Jalali\Jalalian::fromCarbon($wdCarbon)->format('l d F Y') }}</div>
            @endif
        @endif
        @if($meta['wedding_date'] ?? false)
            @php $wd = $wdCarbon ?? null; @endphp
            @if($wd)
            <div class="dec-countdown" id="countdown">
                <div class="cd-item"><div class="cd-num" id="cd-days">--</div><div class="cd-label">روز</div></div>
                <div class="cd-divider">:</div>
                <div class="cd-item"><div class="cd-num" id="cd-hours">--</div><div class="cd-label">ساعت</div></div>
                <div class="cd-divider">:</div>
                <div class="cd-item"><div class="cd-num" id="cd-mins">--</div><div class="cd-label">دقیقه</div></div>
                <div class="cd-divider">:</div>
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
            <p style="margin-top:1.5rem;font-size:.88rem;color:#b8a0c8;max-width:400px;margin-left:auto;margin-right:auto;line-height:1.8;font-style:italic">{{ $card->description }}</p>
        @endif
    </div>

    <div class="dec-container">
        @foreach($sections as $section)
            @if($section->type === 'contact')
                <div class="dec-section">
                    <div class="dec-section-title">{{ $section->title ?: 'تماس' }}</div>
                    <div class="dec-contact">
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
                <div class="dec-section">
                    <div class="dec-section-title">{{ $section->title ?: 'شبکه‌ها' }}</div>
                    <div class="dec-social-row">
                        @foreach($card->socialLinks->sortBy('sort_order') as $link)
                            <a href="{{ $link->url }}" class="dec-social-pill" target="_blank">
                                <i class="bi bi-{{ $link->platform === 'instagram' ? 'instagram' : ($link->platform === 'telegram' ? 'telegram' : 'globe') }}"></i> {{ ucfirst($link->platform) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'gallery' && $card->galleryItems->count())
                <div class="dec-section">
                    <div class="dec-section-title">{{ $section->title ?: 'گالری' }}</div>
                    <div class="dec-gallery">
                        @foreach($card->galleryItems->sortBy('sort_order') as $item)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->caption }}" loading="lazy">
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'timeline' && $section->items && $section->items->count())
                <div class="dec-section">
                    <div class="dec-section-title">{{ $section->title ?: 'داستان عشق' }}</div>
                    <div class="dec-timeline">
                        @foreach($section->items->sortBy('sort_order') as $item)
                            <div class="dec-timeline-item">
                                <div class="tl-title">{{ $item->name }}</div>
                                <div class="tl-desc">{{ $item->description }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'rsvp')
                <div class="dec-section">
                    <div class="dec-section-title">{{ $section->title ?: 'حضور' }}</div>
                    <div class="dec-rsvp">
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
                <div class="dec-section">
                    <div style="text-align:center;font-size:.9rem;line-height:2;color:#b8a0c8">{!! nl2br(e($section->content)) !!}</div>
                </div>
            @endif
        @endforeach

        @if($card->map_lat && $card->map_lng)
            <div class="dec-section">
                <div class="dec-section-title">محل برگزاری</div>
                <div class="dec-map">
                    <iframe src="https://www.openstreetmap.org/export/embed.html?bbox={{ $card->map_lng - 0.01 }}%2C{{ $card->map_lat - 0.01 }}%2C{{ $card->map_lng + 0.01 }}%2C{{ $card->map_lat + 0.01 }}&layer=mapnik&marker={{ $card->map_lat }}%2C{{ $card->map_lng }}" width="100%" height="200" style="border:0;filter:grayscale(80%)" loading="lazy"></iframe>
                </div>
            </div>
        @endif

        @if($card->qrCodes->where('is_active', true)->count())
            @php $qr = $card->qrCodes->where('is_active', true)->first(); @endphp
            @if($qr)
                @php
                    $qrOpts = new \Chillerlan\QRCode\QROptions(['outputInterface' => \Chillerlan\QRCode\Output\QRGdImagePNG::class, 'eccLevel' => \Chillerlan\QRCode\QRCode::ECC_M, 'scale' => 5, 'imageBase64' => false, 'bgColor' => '#1a1a2e', 'moduleValues' => [\Chillerlan\QRCode\Data\QRMatrix::M_DATA_DARK => '#c9a0dc', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DARK => '#c9a0dc', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DOT => '#c9a0dc', \Chillerlan\QRCode\Data\QRMatrix::M_ALIGNMENT_DARK => '#c9a0dc']]);
                    $qrGen = new \Chillerlan\QRCode\QRCode($qrOpts);
                    $qrData = base64_encode($qrGen->render(route('qr.redirect', $qr->unique_code)));
                @endphp
                <div class="dec-qr">
                    <img src="data:image/png;base64,{{ $qrData }}" alt="QR">
                </div>
            @endif
        @endif

        <div class="dec-footer">ساخته شده با <a href="/">کارت‌اکس</a></div>
    </div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
