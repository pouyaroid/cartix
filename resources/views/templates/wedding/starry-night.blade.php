@php
    $sections = $card->sections->where('is_visible', true)->sortBy('sort_order');
    $themeColor = $card->theme_color ?: '#4a90d9';
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
        body{font-family:'Vazirmatn',sans-serif;background:linear-gradient(180deg,#0a0a2e 0%,#1a1a4e 50%,#0a0a2e 100%);color:#e0e8ff;min-height:100vh;position:relative;overflow-x:hidden}
        body::before{content:'';position:fixed;top:0;left:0;right:0;bottom:0;background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='20' cy='20' r='0.5' fill='%23ffffff30'/%3E%3Ccircle cx='80' cy='40' r='0.3' fill='%23ffffff20'/%3E%3Ccircle cx='50' cy='80' r='0.4' fill='%23ffffff25'/%3E%3Ccircle cx='10' cy='60' r='0.2' fill='%23ffffff15'/%3E%3Ccircle cx='90' cy='10' r='0.3' fill='%23ffffff20'/%3E%3Ccircle cx='30' cy='90' r='0.4' fill='%23ffffff25'/%3E%3Ccircle cx='70' cy='70' r='0.2' fill='%23ffffff15'/%3E%3Ccircle cx='40' cy='30' r='0.3' fill='%23ffffff20'/%3E%3C/svg%3E") center/100px repeat;pointer-events:none;z-index:0}
        .str-header{text-align:center;padding:4rem 1.5rem 3rem;position:relative;z-index:1}
        .str-header h2{font-size:.8rem;font-weight:400;color:{{ $themeColor }};letter-spacing:3px;margin-bottom:1rem}
        .str-header h1{font-size:2.2rem;font-weight:300;color:#fff;line-height:1.4}
        .str-header .amp{font-size:1.5rem;color:{{ $themeColor }};margin:0 .5rem;font-style:italic}
        .str-stars{display:flex;justify-content:center;gap:.5rem;margin:1.5rem 0}
        .str-stars span{color:{{ $themeColor }};font-size:1rem;animation:twinkle 2s infinite}
        .str-stars span:nth-child(2){animation-delay:.3s}
        .str-stars span:nth-child(3){animation-delay:.6s}
        @keyframes twinkle{0%,100%{opacity:1}50%{opacity:.3}}
        .str-date{font-size:1rem;color:#8ba0c0;font-weight:300;letter-spacing:1px}
        .str-countdown{display:flex;justify-content:center;gap:1rem;margin-top:2rem}
        .str-countdown .cd-item{text-align:center;background:rgba(255,255,255,.05);backdrop-filter:blur(8px);padding:1rem;border-radius:1rem;min-width:65px;border:1px solid {{ $themeColor }}25}
        .str-countdown .cd-num{font-size:1.6rem;font-weight:300;color:{{ $themeColor }};line-height:1}
        .str-countdown .cd-label{font-size:.6rem;color:#8ba0c0;margin-top:.5rem}
        .str-container{max-width:500px;margin:0 auto;padding:0 1.5rem 2rem;position:relative;z-index:1}
        .str-section{background:rgba(255,255,255,.05);backdrop-filter:blur(10px);border:1px solid {{ $themeColor }}15;padding:2rem;margin-bottom:1.5rem;border-radius:1.5rem}
        .str-section-title{text-align:center;font-size:.85rem;font-weight:500;color:{{ $themeColor }};margin-bottom:1.5rem;display:flex;align-items:center;justify-content:center;gap:.5rem}
        .str-section-title::before{content:'✦';font-size:.9rem}
        .str-gallery{display:grid;grid-template-columns:repeat(2,1fr);gap:.75rem}
        .str-gallery img{width:100%;aspect-ratio:1;object-fit:cover;border-radius:1rem;border:2px solid {{ $themeColor }}20;transition:transform .3s}
        .str-gallery img:hover{transform:scale(1.03)}
        .str-timeline{position:relative}
        .str-timeline-item{display:flex;gap:1rem;padding:1rem 0;border-bottom:1px solid rgba(255,255,255,.05)}
        .str-timeline-item:last-child{border-bottom:none}
        .str-timeline-item .tl-icon{width:36px;height:36px;border-radius:50%;background:{{ $themeColor }}15;display:flex;align-items:center;justify-content:center;color:{{ $themeColor }};flex-shrink:0;font-size:.9rem}
        .str-timeline-item .tl-title{font-weight:600;font-size:.88rem;color:#fff}
        .str-timeline-item .tl-desc{font-size:.8rem;color:#8ba0c0;margin-top:.25rem}
        .str-social{display:flex;flex-wrap:wrap;justify-content:center;gap:.75rem}
        .str-social a{display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1rem;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);border-radius:2rem;font-size:.8rem;text-decoration:none;transition:all .2s;color:#fff}
        .str-social a:hover{background:{{ $themeColor }};border-color:{{ $themeColor }};transform:translateY(-2px)}
        .str-map{border-radius:1rem;overflow:hidden;height:200px;border:2px solid {{ $themeColor }}20}
        .str-footer{text-align:center;padding:2rem 1rem;color:#4a6080;font-size:.78rem}
        .str-footer a{color:{{ $themeColor }};text-decoration:none}
        .str-contact{text-align:center}
        .str-contact a,.str-contact span{color:#ccc;text-decoration:none;font-size:.88rem;display:block;margin-bottom:.75rem}
        .str-contact i{color:{{ $themeColor }};margin-left:.5rem}
        .str-qr{text-align:center;padding:1.5rem;background:rgba(255,255,255,.05);border-radius:1rem;border:1px solid {{ $themeColor }}15}
        .str-qr img{max-width:100px}
        .str-rsvp input,.str-rsvp select{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.15);color:#fff;padding:.7rem 1rem;width:100%;margin-bottom:1rem;font-size:.88rem;font-family:'Vazirmatn',sans-serif;border-radius:1rem}
        .str-rsvp input::placeholder{color:rgba(255,255,255,.4)}
        .str-rsvp button{background:{{ $themeColor }};color:#fff;border:none;padding:.7rem 2rem;width:100%;font-size:.9rem;cursor:pointer;border-radius:1rem;transition:all .2s}
        .str-rsvp button:hover{opacity:.9;transform:translateY(-2px)}
    </style>
</head>
<body>
    <div class="str-header">
        <h2>دعوت نامه عروسی</h2>
        <h1>
            {{ $card->title }}
            @if($meta['partner_name'] ?? false)
                <span class="amp">&</span> {{ $meta['partner_name'] }}
            @endif
        </h1>
        <div class="str-stars"><span>✦</span><span>✦</span><span>✦</span></div>
        @if($meta['wedding_date'] ?? false)
            @php try { $wdCarbon = \Carbon\Carbon::parse($meta['wedding_date']); } catch (\Exception $e) { $wdCarbon = null; } @endphp
            @if($wdCarbon)
                <div class="str-date">{{ \Morilog\Jalali\Jalalian::fromCarbon($wdCarbon)->format('l d F Y') }}</div>
            @endif
        @endif
        @if($meta['wedding_date'] ?? false)
            @php $wd = $wdCarbon ?? null; @endphp
            @if($wd)
            <div class="str-countdown" id="countdown">
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
            <p style="margin-top:1.5rem;font-size:.9rem;color:#8ba0c0;max-width:400px;margin-left:auto;margin-right:auto;line-height:1.8;font-style:italic">{{ $card->description }}</p>
        @endif
    </div>

    <div class="str-container">
        @foreach($sections as $section)
            @if($section->type === 'contact')
                <div class="str-section">
                    <div class="str-section-title">{{ $section->title ?: 'تماس' }}</div>
                    <div class="str-contact">
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
                <div class="str-section">
                    <div class="str-section-title">{{ $section->title ?: 'شبکه‌ها' }}</div>
                    <div class="str-social">
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
                <div class="str-section">
                    <div class="str-section-title">{{ $section->title ?: 'گالری' }}</div>
                    <div class="str-gallery">
                        @foreach($card->galleryItems->sortBy('sort_order') as $item)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->caption }}" loading="lazy">
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'timeline' && $section->items && $section->items->count())
                <div class="str-section">
                    <div class="str-section-title">{{ $section->title ?: 'داستان عشق' }}</div>
                    <div class="str-timeline">
                        @foreach($section->items->sortBy('sort_order') as $item)
                            <div class="str-timeline-item">
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
                <div class="str-section">
                    <div class="str-section-title">{{ $section->title ?: 'حضور' }}</div>
                    <div class="str-rsvp">
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
                <div class="str-section">
                    <div style="text-align:center;font-size:.9rem;line-height:2;color:#a0b0d0">{!! nl2br(e($section->content)) !!}</div>
                </div>
            @endif
        @endforeach

        @if($card->map_lat && $card->map_lng)
            <div class="str-section">
                <div class="str-section-title">محل برگزاری</div>
                <div class="str-map">
                    <iframe src="https://www.openstreetmap.org/export/embed.html?bbox={{ $card->map_lng - 0.01 }}%2C{{ $card->map_lat - 0.01 }}%2C{{ $card->map_lng + 0.01 }}%2C{{ $card->map_lat + 0.01 }}&layer=mapnik&marker={{ $card->map_lat }}%2C{{ $card->map_lng }}" width="100%" height="200" style="border:0;filter:grayscale(80%) brightness(0.8)" loading="lazy"></iframe>
                </div>
            </div>
        @endif

        @if($card->qrCodes->where('is_active', true)->count())
            @php $qr = $card->qrCodes->where('is_active', true)->first(); @endphp
            @if($qr)
                @php
                    $qrOpts = new \Chillerlan\QRCode\QROptions(['outputInterface' => \Chillerlan\QRCode\Output\QRGdImagePNG::class, 'eccLevel' => \Chillerlan\QRCode\QRCode::ECC_M, 'scale' => 5, 'imageBase64' => false, 'bgColor' => '#0a0a2e', 'moduleValues' => [\Chillerlan\QRCode\Data\QRMatrix::M_DATA_DARK => '#4a90d9', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DARK => '#4a90d9', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DOT => '#4a90d9', \Chillerlan\QRCode\Data\QRMatrix::M_ALIGNMENT_DARK => '#4a90d9']]);
                    $qrGen = new \Chillerlan\QRCode\QRCode($qrOpts);
                    $qrData = base64_encode($qrGen->render(route('qr.redirect', $qr->unique_code)));
                @endphp
                <div class="str-qr">
                    <img src="data:image/png;base64,{{ $qrData }}" alt="QR">
                </div>
            @endif
        @endif

        <div class="str-footer">ساخته شده با <a href="/">کارت‌اکس</a></div>
    </div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
