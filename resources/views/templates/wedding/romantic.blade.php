@php
    $sections = $card->sections->where('is_visible', true)->sortBy('sort_order');
    $themeColor = $card->theme_color ?: '#e91e63';
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
        body{font-family:'Vazirmatn',sans-serif;background:linear-gradient(180deg,#fff5f5 0%,#ffe4ec 100%);color:#4a2040;min-height:100vh}
        .rom-header{text-align:center;padding:4rem 1.5rem 3rem;position:relative}
        .rom-header::before{content:'♥';position:absolute;top:1rem;left:50%;transform:translateX(-50%);font-size:3rem;color:{{ $themeColor }}15}
        .rom-header h2{font-size:.8rem;font-weight:400;color:{{ $themeColor }};letter-spacing:3px;margin-bottom:1rem}
        .rom-header h1{font-size:2.2rem;font-weight:300;color:#4a2040;line-height:1.4}
        .rom-header .amp{font-size:1.5rem;color:{{ $themeColor }};margin:0 .5rem;font-style:italic}
        .rom-hearts{display:flex;justify-content:center;gap:.5rem;margin:1.5rem 0}
        .rom-hearts span{color:{{ $themeColor }};font-size:1rem;opacity:.5}
        .rom-hearts span:nth-child(2){opacity:1;font-size:1.2rem}
        .rom-date{font-size:1rem;color:#8b5a6b;font-weight:300;letter-spacing:1px}
        .rom-countdown{display:flex;justify-content:center;gap:1rem;margin-top:2rem}
        .rom-countdown .cd-item{text-align:center;background:rgba(255,255,255,.7);backdrop-filter:blur(8px);padding:1rem;border-radius:1.5rem;min-width:65px;border:1px solid {{ $themeColor }}25}
        .rom-countdown .cd-num{font-size:1.6rem;font-weight:300;color:{{ $themeColor }};line-height:1}
        .rom-countdown .cd-label{font-size:.6rem;color:#8b5a6b;margin-top:.5rem}
        .rom-container{max-width:500px;margin:0 auto;padding:0 1.5rem 2rem}
        .rom-section{background:rgba(255,255,255,.7);backdrop-filter:blur(10px);border-radius:1.5rem;padding:2rem;margin-bottom:1.5rem;border:1px solid {{ $themeColor }}15;box-shadow:0 4px 20px rgba(233,30,99,.05)}
        .rom-section-title{text-align:center;font-size:.85rem;font-weight:500;color:{{ $themeColor }};margin-bottom:1.5rem;display:flex;align-items:center;justify-content:center;gap:.5rem}
        .rom-section-title::before{content:'♥';font-size:.9rem}
        .rom-gallery{display:grid;grid-template-columns:repeat(2,1fr);gap:.75rem}
        .rom-gallery img{width:100%;aspect-ratio:1;object-fit:cover;border-radius:1rem;border:3px solid #fff;box-shadow:0 4px 16px rgba(233,30,99,.1);transition:transform .3s}
        .rom-gallery img:hover{transform:scale(1.03)}
        .rom-timeline{position:relative}
        .rom-timeline-item{display:flex;gap:1rem;padding:1rem 0;border-bottom:1px solid {{ $themeColor }}10}
        .rom-timeline-item:last-child{border-bottom:none}
        .rom-timeline-item .tl-icon{width:36px;height:36px;border-radius:50%;background:{{ $themeColor }}15;display:flex;align-items:center;justify-content:center;color:{{ $themeColor }};flex-shrink:0;font-size:.9rem}
        .rom-timeline-item .tl-title{font-weight:600;font-size:.88rem;color:#4a2040}
        .rom-timeline-item .tl-desc{font-size:.8rem;color:#8b5a6b;margin-top:.25rem}
        .rom-social{display:flex;flex-wrap:wrap;justify-content:center;gap:.75rem}
        .rom-social a{display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1rem;background:{{ $themeColor }}10;color:{{ $themeColor }};border-radius:2rem;font-size:.8rem;text-decoration:none;transition:all .2s}
        .rom-social a:hover{background:{{ $themeColor }};color:#fff}
        .rom-map{border-radius:1rem;overflow:hidden;height:200px;border:3px solid #fff;box-shadow:0 4px 16px rgba(233,30,99,.1)}
        .rom-footer{text-align:center;padding:2rem 1rem;color:#d4a0b0;font-size:.78rem}
        .rom-footer a{color:{{ $themeColor }};text-decoration:none}
        .rom-contact{text-align:center}
        .rom-contact a,.rom-contact span{color:#4a2040;text-decoration:none;font-size:.88rem;display:block;margin-bottom:.75rem}
        .rom-contact i{color:{{ $themeColor }};margin-left:.5rem}
        .rom-qr{text-align:center;padding:1.5rem;background:rgba(255,255,255,.5);border-radius:1rem}
        .rom-qr img{max-width:100px}
        .rom-rsvp input,.rom-rsvp select{border:1px solid {{ $themeColor }}30;border-radius:1.5rem;padding:.7rem 1.2rem;width:100%;margin-bottom:1rem;font-size:.88rem;font-family:'Vazirmatn',sans-serif;background:rgba(255,255,255,.5);color:#4a2040}
        .rom-rsvp button{background:{{ $themeColor }};color:#fff;border:none;border-radius:1.5rem;padding:.7rem 2rem;width:100%;font-size:.9rem;cursor:pointer;transition:opacity .2s}
        .rom-rsvp button:hover{opacity:.9}
    </style>
</head>
<body>
    <div class="rom-header">
        <h2>دعوت نامه عروسی</h2>
        <h1>
            {{ $card->title }}
            @if($meta['partner_name'] ?? false)
                <span class="amp">&</span> {{ $meta['partner_name'] }}
            @endif
        </h1>
        <div class="rom-hearts"><span>♥</span><span>♥</span><span>♥</span></div>
        @if($meta['wedding_date'] ?? false)
            @php try { $wdCarbon = \Carbon\Carbon::parse($meta['wedding_date']); } catch (\Exception $e) { $wdCarbon = null; } @endphp
            @if($wdCarbon)
                <div class="rom-date">{{ \Morilog\Jalali\Jalalian::fromCarbon($wdCarbon)->format('l d F Y') }}</div>
            @endif
        @endif
        @if($meta['wedding_date'] ?? false)
            @php $wd = $wdCarbon ?? null; @endphp
            @if($wd)
            <div class="rom-countdown" id="countdown">
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
            <p style="margin-top:1.5rem;font-size:.9rem;color:#8b5a6b;max-width:400px;margin-left:auto;margin-right:auto;line-height:1.8;font-style:italic">{{ $card->description }}</p>
        @endif
    </div>

    <div class="rom-container">
        @foreach($sections as $section)
            @if($section->type === 'contact')
                <div class="rom-section">
                    <div class="rom-section-title">{{ $section->title ?: 'تماس' }}</div>
                    <div class="rom-contact">
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
                <div class="rom-section">
                    <div class="rom-section-title">{{ $section->title ?: 'شبکه‌ها' }}</div>
                    <div class="rom-social">
                        @foreach($card->socialLinks->sortBy('sort_order') as $link)
                            <a href="{{ $link->url }}" target="_blank">
                                <i class="bi bi-{{ $link->platform === 'instagram' ? 'instagram' : ($link->platform === 'telegram' ? 'telegram' : 'globe') }}"></i>
                                {{ ucfirst($link->platform) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'gallery' && $card->galleryItems->count())
                <div class="rom-section">
                    <div class="rom-section-title">{{ $section->title ?: 'گالری' }}</div>
                    <div class="rom-gallery">
                        @foreach($card->galleryItems->sortBy('sort_order') as $item)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->caption }}" loading="lazy">
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'timeline' && $section->items && $section->items->count())
                <div class="rom-section">
                    <div class="rom-section-title">{{ $section->title ?: 'داستان عشق' }}</div>
                    <div class="rom-timeline">
                        @foreach($section->items->sortBy('sort_order') as $item)
                            <div class="rom-timeline-item">
                                <div class="tl-icon"><i class="bi bi-heart"></i></div>
                                <div>
                                    <div class="tl-title">{{ $item->name }}</div>
                                    <div class="tl-desc">{{ $item->description }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'rsvp')
                <div class="rom-section">
                    <div class="rom-section-title">{{ $section->title ?: 'حضور' }}</div>
                    <div class="rom-rsvp">
                        <input type="text" placeholder="نام و نام خانوادگی">
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
                <div class="rom-section">
                    <div style="text-align:center;font-size:.9rem;line-height:2;color:#6b4a5a">{!! nl2br(e($section->content)) !!}</div>
                </div>
            @endif
        @endforeach

        @if($card->map_lat && $card->map_lng)
            <div class="rom-section">
                <div class="rom-section-title">محل برگزاری</div>
                <div class="rom-map">
                    <iframe src="https://www.openstreetmap.org/export/embed.html?bbox={{ $card->map_lng - 0.01 }}%2C{{ $card->map_lat - 0.01 }}%2C{{ $card->map_lng + 0.01 }}%2C{{ $card->map_lat + 0.01 }}&layer=mapnik&marker={{ $card->map_lat }}%2C{{ $card->map_lng }}" width="100%" height="200" style="border:0" loading="lazy"></iframe>
                </div>
            </div>
        @endif

        @if($card->qrCodes->where('is_active', true)->count())
            @php $qr = $card->qrCodes->where('is_active', true)->first(); @endphp
            @if($qr)
                @php
                    $qrOpts = new \Chillerlan\QRCode\QROptions(['outputInterface' => \Chillerlan\QRCode\Output\QRGdImagePNG::class, 'eccLevel' => \Chillerlan\QRCode\QRCode::ECC_M, 'scale' => 5, 'imageBase64' => false, 'bgColor' => '#fff5f5', 'moduleValues' => [\Chillerlan\QRCode\Data\QRMatrix::M_DATA_DARK => '#e91e63', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DARK => '#e91e63', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DOT => '#e91e63', \Chillerlan\QRCode\Data\QRMatrix::M_ALIGNMENT_DARK => '#e91e63']]);
                    $qrGen = new \Chillerlan\QRCode\QRCode($qrOpts);
                    $qrData = base64_encode($qrGen->render(route('qr.redirect', $qr->unique_code)));
                @endphp
                <div class="rom-qr">
                    <img src="data:image/png;base64,{{ $qrData }}" alt="QR">
                </div>
            @endif
        @endif

        <div class="rom-footer">ساخته شده با <a href="/">کارت‌اکس</a></div>
    </div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
