@php
    $sections = $card->sections->where('is_visible', true)->sortBy('sort_order');
    $themeColor = $card->theme_color ?: '#8b4513';
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
        body{font-family:'Vazirmatn',sans-serif;background:#f5f0e8;color:#4a3728;min-height:100vh}
        .vintage-header{text-align:center;padding:4rem 1.5rem 3rem;background:linear-gradient(180deg,#e8dcc8 0%,#f5f0e8 100%);border-bottom:2px solid #d4c4a8}
        .vintage-header::before{content:'❦';display:block;font-size:1.5rem;color:{{ $themeColor }}40;margin-bottom:1rem}
        .vintage-header h2{font-size:.8rem;font-weight:400;color:#8b7355;letter-spacing:3px;margin-bottom:1rem}
        .vintage-header h1{font-size:2.2rem;font-weight:300;color:#3d2c1e;line-height:1.4}
        .vintage-header .amp{font-size:1.5rem;color:{{ $themeColor }};margin:0 .5rem;font-style:italic}
        .vintage-border{width:150px;height:2px;margin:1.5rem auto;background:repeating-linear-gradient(90deg,{{ $themeColor }} 0px,{{ $themeColor }} 5px,transparent 5px,transparent 10px)}
        .vintage-date{font-size:1rem;color:#8b7355;font-weight:300;letter-spacing:1px}
        .vintage-countdown{display:flex;justify-content:center;gap:1.5rem;margin-top:2rem}
        .vintage-countdown .cd-item{text-align:center;background:rgba(255,255,255,.5);padding:1rem;border:1px solid #d4c4a8;min-width:65px}
        .vintage-countdown .cd-num{font-size:1.8rem;font-weight:300;color:{{ $themeColor }};line-height:1}
        .vintage-countdown .cd-label{font-size:.6rem;color:#8b7355;margin-top:.5rem}
        .vintage-container{max-width:500px;margin:0 auto;padding:0 1.5rem 2rem}
        .vintage-section{background:rgba(255,255,255,.5);border:1px solid #d4c4a8;padding:2rem;margin-bottom:1.5rem;position:relative}
        .vintage-section::before{content:'';position:absolute;top:5px;left:5px;right:5px;bottom:5px;border:1px solid #d4c4a850;pointer-events:none}
        .vintage-section-title{text-align:center;font-size:.85rem;font-weight:500;color:{{ $themeColor }};margin-bottom:1.5rem;letter-spacing:2px}
        .vintage-gallery{display:grid;grid-template-columns:repeat(2,1fr);gap:.75rem}
        .vintage-gallery img{width:100%;aspect-ratio:1;object-fit:cover;border:3px solid #d4c4a8;filter:sepia(20%);transition:filter .3s}
        .vintage-gallery img:hover{filter:sepia(0%)}
        .vintage-timeline{position:relative;padding-right:2rem}
        .vintage-timeline::before{content:'';position:absolute;right:0;top:0;bottom:0;width:1px;background:#d4c4a8}
        .vintage-timeline-item{position:relative;padding:1rem 0}
        .vintage-timeline-item::before{content:'◆';position:absolute;right:-6px;top:1rem;color:{{ $themeColor }};font-size:.6rem}
        .vintage-timeline-item .tl-title{font-weight:600;font-size:.88rem;color:#3d2c1e}
        .vintage-timeline-item .tl-desc{font-size:.8rem;color:#8b7355;margin-top:.25rem}
        .vintage-social-row{display:flex;flex-wrap:wrap;justify-content:center;gap:.75rem}
        .vintage-social-pill{display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1rem;background:{{ $themeColor }}10;color:{{ $themeColor }};border:1px solid {{ $themeColor }}30;font-size:.78rem;text-decoration:none;transition:all .2s}
        .vintage-social-pill:hover{background:{{ $themeColor }};color:#fff;border-color:{{ $themeColor }}}
        .vintage-map{height:200px;border:3px solid #d4c4a8;overflow:hidden}
        .vintage-footer{text-align:center;padding:2rem 1rem;color:#b8a88a;font-size:.75rem;letter-spacing:2px}
        .vintage-footer a{color:{{ $themeColor }};text-decoration:none}
        .vintage-contact{text-align:center}
        .vintage-contact a,.vintage-contact span{color:#4a3728;text-decoration:none;font-size:.88rem;display:block;margin-bottom:.5rem}
        .vintage-contact i{color:{{ $themeColor }};margin-left:.5rem}
        .vintage-qr{text-align:center;padding:1.5rem;background:rgba(255,255,255,.5);border:1px solid #d4c4a8}
        .vintage-qr img{max-width:100px}
        .vintage-rsvp input,.vintage-rsvp select{border:1px solid #d4c4a8;background:rgba(255,255,255,.5);padding:.7rem 1rem;width:100%;margin-bottom:1rem;font-size:.88rem;font-family:'Vazirmatn',sans-serif;color:#4a3728}
        .vintage-rsvp button{background:{{ $themeColor }};color:#fff;border:none;padding:.7rem 2rem;width:100%;font-size:.9rem;cursor:pointer;transition:opacity .2s}
        .vintage-rsvp button:hover{opacity:.9}
    </style>
</head>
<body>
    <div class="vintage-header">
        <h2>دعوت نامه عروسی</h2>
        <h1>
            {{ $card->title }}
            @if($meta['partner_name'] ?? false)
                <span class="amp">&</span> {{ $meta['partner_name'] }}
            @endif
        </h1>
        <div class="vintage-border"></div>
        @if($meta['wedding_date'] ?? false)
            @php try { $wdCarbon = \Carbon\Carbon::parse($meta['wedding_date']); } catch (\Exception $e) { $wdCarbon = null; } @endphp
            @if($wdCarbon)
                <div class="vintage-date">{{ \Morilog\Jalali\Jalalian::fromCarbon($wdCarbon)->format('l d F Y') }}</div>
            @endif
        @endif
        @if($meta['wedding_date'] ?? false)
            @php $wd = $wdCarbon ?? null; @endphp
            @if($wd)
            <div class="vintage-countdown" id="countdown">
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

    <div class="vintage-container">
        @foreach($sections as $section)
            @if($section->type === 'contact')
                <div class="vintage-section">
                    <div class="vintage-section-title">{{ $section->title ?: 'تماس' }}</div>
                    <div class="vintage-contact">
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
                <div class="vintage-section">
                    <div class="vintage-section-title">{{ $section->title ?: 'شبکه‌ها' }}</div>
                    <div class="vintage-social-row">
                        @foreach($card->socialLinks->sortBy('sort_order') as $link)
                            <a href="{{ $link->url }}" class="vintage-social-pill" target="_blank">
                                <i class="bi bi-{{ $link->platform === 'instagram' ? 'instagram' : ($link->platform === 'telegram' ? 'telegram' : 'globe') }}"></i> {{ ucfirst($link->platform) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'gallery' && $card->galleryItems->count())
                <div class="vintage-section">
                    <div class="vintage-section-title">{{ $section->title ?: 'گالری' }}</div>
                    <div class="vintage-gallery">
                        @foreach($card->galleryItems->sortBy('sort_order') as $item)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->caption }}" loading="lazy">
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'timeline' && $section->items && $section->items->count())
                <div class="vintage-section">
                    <div class="vintage-section-title">{{ $section->title ?: 'خاطرات' }}</div>
                    <div class="vintage-timeline">
                        @foreach($section->items->sortBy('sort_order') as $item)
                            <div class="vintage-timeline-item">
                                <div class="tl-title">{{ $item->name }}</div>
                                <div class="tl-desc">{{ $item->description }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'rsvp')
                <div class="vintage-section">
                    <div class="vintage-section-title">{{ $section->title ?: 'حضور' }}</div>
                    <div class="vintage-rsvp">
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
                <div class="vintage-section">
                    <div style="text-align:center;font-size:.9rem;line-height:2;color:#5a4a3a">{!! nl2br(e($section->content)) !!}</div>
                </div>
            @endif
        @endforeach

        @if($card->map_lat && $card->map_lng)
            <div class="vintage-section">
                <div class="vintage-section-title">محل برگزاری</div>
                <div class="vintage-map">
                    <iframe src="https://www.openstreetmap.org/export/embed.html?bbox={{ $card->map_lng - 0.01 }}%2C{{ $card->map_lat - 0.01 }}%2C{{ $card->map_lng + 0.01 }}%2C{{ $card->map_lat + 0.01 }}&layer=mapnik&marker={{ $card->map_lat }}%2C{{ $card->map_lng }}" width="100%" height="200" style="border:0;filter:sepia(40%)" loading="lazy"></iframe>
                </div>
            </div>
        @endif

        @if($card->qrCodes->where('is_active', true)->count())
            @php $qr = $card->qrCodes->where('is_active', true)->first(); @endphp
            @if($qr)
                @php
                    $qrOpts = new \Chillerlan\QRCode\QROptions(['outputInterface' => \Chillerlan\QRCode\Output\QRGdImagePNG::class, 'eccLevel' => \Chillerlan\QRCode\QRCode::ECC_M, 'scale' => 5, 'imageBase64' => false, 'bgColor' => '#f5f0e8', 'moduleValues' => [\Chillerlan\QRCode\Data\QRMatrix::M_DATA_DARK => '#8b4513', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DARK => '#8b4513', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DOT => '#8b4513', \Chillerlan\QRCode\Data\QRMatrix::M_ALIGNMENT_DARK => '#8b4513']]);
                    $qrGen = new \Chillerlan\QRCode\QRCode($qrOpts);
                    $qrData = base64_encode($qrGen->render(route('qr.redirect', $qr->unique_code)));
                @endphp
                <div class="vintage-qr">
                    <img src="data:image/png;base64,{{ $qrData }}" alt="QR">
                </div>
            @endif
        @endif

        <div class="vintage-footer">ساخته شده با <a href="/">کارت‌اکس</a></div>
    </div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
