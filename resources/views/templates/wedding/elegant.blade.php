@php
    $sections = $card->sections->where('is_visible', true)->sortBy('sort_order');
    $themeColor = $card->theme_color ?: '#c9a87c';
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
        body{font-family:'Vazirmatn',sans-serif;background:linear-gradient(135deg,#faf6f1 0%,#f5ede4 100%);color:#4a3f35;min-height:100vh}
        .ela-header{text-align:center;padding:4rem 1.5rem 3rem;position:relative;background:linear-gradient(180deg,rgba(255,255,255,.8),transparent)}
        .ela-header::before{content:'❦';position:absolute;top:1.5rem;left:50%;transform:translateX(-50%);font-size:1.5rem;color:{{ $themeColor }}40}
        .ela-header h2{font-size:.8rem;font-weight:400;color:{{ $themeColor }};letter-spacing:3px;margin-bottom:1.5rem}
        .ela-header h1{font-size:2.2rem;font-weight:300;color:#3d3228;line-height:1.4}
        .ela-header .amp{font-size:1.5rem;color:{{ $themeColor }};margin:0 .5rem;font-style:italic}
        .ela-ornament{width:60px;height:1px;background:linear-gradient(90deg,transparent,{{ $themeColor }},transparent);margin:1.5rem auto}
        .ela-date{font-size:1rem;color:#8b7d6b;font-weight:300;letter-spacing:1px}
        .ela-countdown{display:flex;justify-content:center;gap:1.5rem;margin-top:2rem}
        .ela-countdown .cd-item{text-align:center;background:rgba(255,255,255,.6);backdrop-filter:blur(10px);padding:1rem 1.25rem;border-radius:1rem;min-width:70px;border:1px solid {{ $themeColor }}20}
        .ela-countdown .cd-num{font-size:1.8rem;font-weight:300;color:{{ $themeColor }};line-height:1}
        .ela-countdown .cd-label{font-size:.65rem;color:#8b7d6b;margin-top:.5rem}
        .ela-container{max-width:500px;margin:0 auto;padding:0 1.5rem 2rem}
        .ela-section{background:rgba(255,255,255,.7);backdrop-filter:blur(10px);border-radius:1.5rem;padding:2rem;margin-bottom:1.5rem;border:1px solid {{ $themeColor }}15;box-shadow:0 4px 24px rgba(0,0,0,.03)}
        .ela-section-title{text-align:center;font-size:.85rem;font-weight:500;color:{{ $themeColor }};margin-bottom:1.5rem;display:flex;align-items:center;justify-content:center;gap:.75rem}
        .ela-section-title::before,.ela-section-title::after{content:'·';font-size:1.5rem;color:{{ $themeColor }}40}
        .ela-gallery{display:grid;grid-template-columns:repeat(2,1fr);gap:.75rem}
        .ela-gallery img{width:100%;aspect-ratio:1;object-fit:cover;border-radius:1rem;transition:transform .3s}
        .bla-gallery img:hover{transform:scale(1.03)}
        .ela-timeline{position:relative}
        .ela-timeline-item{display:flex;gap:1rem;padding:1rem 0;border-bottom:1px solid {{ $themeColor }}10}
        .ela-timeline-item:last-child{border-bottom:none}
        .ela-timeline-item .tl-icon{width:40px;height:40px;border-radius:50%;background:{{ $themeColor }}10;display:flex;align-items:center;justify-content:center;color:{{ $themeColor }};flex-shrink:0}
        .ela-timeline-item .tl-title{font-weight:600;font-size:.9rem;color:#3d3228}
        .ela-timeline-item .tl-desc{font-size:.8rem;color:#8b7d6b;margin-top:.25rem}
        .ela-social{display:flex;flex-wrap:wrap;justify-content:center;gap:.75rem}
        .ela-social a{display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1rem;background:{{ $themeColor }}10;color:{{ $themeColor }};border-radius:2rem;font-size:.8rem;text-decoration:none;transition:all .2s}
        .ela-social a:hover{background:{{ $themeColor }};color:#fff}
        .ela-map{border-radius:1rem;overflow:hidden;height:200px;border:1px solid {{ $themeColor }}15}
        .ela-footer{text-align:center;padding:2rem 1rem;color:#b8a89a;font-size:.78rem}
        .ela-footer a{color:{{ $themeColor }};text-decoration:none}
        .ela-contact{text-align:center}
        .ela-contact a,.ela-contact span{color:#5a4f42;text-decoration:none;font-size:.9rem;display:block;margin-bottom:.75rem}
        .ela-contact i{color:{{ $themeColor }};margin-left:.5rem}
        .ela-qr{text-align:center;padding:1.5rem;background:rgba(255,255,255,.5);border-radius:1rem}
        .ela-qr img{max-width:100px}
        .ela-rsvp input,.ela-rsvp select{border:1px solid {{ $themeColor }}30;border-radius:1rem;padding:.7rem 1.2rem;width:100%;margin-bottom:1rem;font-size:.88rem;font-family:'Vazirmatn',sans-serif;background:rgba(255,255,255,.5);color:#4a3f35}
        .ela-rsvp button{background:{{ $themeColor }};color:#fff;border:none;border-radius:1rem;padding:.7rem 2rem;width:100%;font-size:.9rem;cursor:pointer;transition:opacity .2s}
        .ela-rsvp button:hover{opacity:.9}
    </style>
</head>
<body>
    <div class="ela-header">
        <h2>دعوت نامه عروسی</h2>
        <h1>
            {{ $card->title }}
            @if($meta['partner_name'] ?? false)
                <span class="amp">&</span> {{ $meta['partner_name'] }}
            @endif
        </h1>
        <div class="ela-ornament"></div>
        @if($meta['wedding_date'] ?? false)
            @php try { $wdCarbon = \Carbon\Carbon::parse($meta['wedding_date']); } catch (\Exception $e) { $wdCarbon = null; } @endphp
            @if($wdCarbon)
                <div class="ela-date">{{ \Morilog\Jalali\Jalalian::fromCarbon($wdCarbon)->format('l d F Y') }}</div>
            @endif
        @endif
        @if($meta['wedding_date'] ?? false)
            @php $wd = $wdCarbon ?? null; @endphp
            @if($wd)
            <div class="ela-countdown" id="countdown">
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
            <p style="margin-top:1.5rem;font-size:.9rem;color:#8b7d6b;max-width:400px;margin-left:auto;margin-right:auto;line-height:1.8;font-style:italic">{{ $card->description }}</p>
        @endif
    </div>

    <div class="ela-container">
        @foreach($sections as $section)
            @if($section->type === 'contact')
                <div class="ela-section">
                    <div class="ela-section-title">{{ $section->title ?: 'تماس' }}</div>
                    <div class="ela-contact">
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
                <div class="ela-section">
                    <div class="ela-section-title">{{ $section->title ?: 'شبکه‌ها' }}</div>
                    <div class="ela-social">
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
                <div class="ela-section">
                    <div class="ela-section-title">{{ $section->title ?: 'گالری' }}</div>
                    <div class="ela-gallery">
                        @foreach($card->galleryItems->sortBy('sort_order') as $item)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->caption }}" loading="lazy">
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'timeline' && $section->items && $section->items->count())
                <div class="ela-section">
                    <div class="ela-section-title">{{ $section->title ?: 'داستان عشق' }}</div>
                    <div class="ela-timeline">
                        @foreach($section->items->sortBy('sort_order') as $item)
                            <div class="ela-timeline-item">
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
                <div class="ela-section">
                    <div class="ela-section-title">{{ $section->title ?: 'حضور' }}</div>
                    <div class="ela-rsvp">
                        <input type="text" placeholder="نام و نام خانوادگی">
                        <input type="tel" placeholder="شماره تماس">
                        <select>
                            <option>با کمال میل حاضر می‌شوم</option>
                            <option>متأسفانه نمی‌توانم</option>
                        </select>
                        <button>ارسال پاسخ</button>
                    </div>
                </div>
            @endif

            @if($section->type === 'custom' && $section->content)
                <div class="ela-section">
                    <div style="text-align:center;font-size:.9rem;line-height:2;color:#6b5e50">{!! nl2br(e($section->content)) !!}</div>
                </div>
            @endif
        @endforeach

        @if($card->map_lat && $card->map_lng)
            <div class="ela-section">
                <div class="ela-section-title">محل برگزاری</div>
                <div class="ela-map">
                    <iframe src="https://www.openstreetmap.org/export/embed.html?bbox={{ $card->map_lng - 0.01 }}%2C{{ $card->map_lat - 0.01 }}%2C{{ $card->map_lng + 0.01 }}%2C{{ $card->map_lat + 0.01 }}&layer=mapnik&marker={{ $card->map_lat }}%2C{{ $card->map_lng }}" width="100%" height="200" style="border:0" loading="lazy"></iframe>
                </div>
            </div>
        @endif

        @if($card->qrCodes->where('is_active', true)->count())
            @php $qr = $card->qrCodes->where('is_active', true)->first(); @endphp
            @if($qr)
                @php
                    $qrOpts = new \Chillerlan\QRCode\QROptions(['outputInterface' => \Chillerlan\QRCode\Output\QRGdImagePNG::class, 'eccLevel' => \Chillerlan\QRCode\QRCode::ECC_M, 'scale' => 5, 'imageBase64' => false, 'bgColor' => '#faf6f1', 'moduleValues' => [\Chillerlan\QRCode\Data\QRMatrix::M_DATA_DARK => '#c9a87c', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DARK => '#c9a87c', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DOT => '#c9a87c', \Chillerlan\QRCode\Data\QRMatrix::M_ALIGNMENT_DARK => '#c9a87c']]);
                    $qrGen = new \Chillerlan\QRCode\QRCode($qrOpts);
                    $qrData = base64_encode($qrGen->render(route('qr.redirect', $qr->unique_code)));
                @endphp
                <div class="ela-qr">
                    <img src="data:image/png;base64,{{ $qrData }}" alt="QR">
                </div>
            @endif
        @endif

        <div class="ela-footer">ساخته شده با <a href="/">کارت‌اکس</a></div>
    </div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
