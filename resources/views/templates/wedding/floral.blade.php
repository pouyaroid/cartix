@php
    $sections = $card->sections->where('is_visible', true)->sortBy('sort_order');
    $themeColor = $card->theme_color ?: '#8fbc8f';
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
        body{font-family:'Vazirmatn',sans-serif;background:linear-gradient(180deg,#f0f7f0 0%,#e8f5e9 50%,#f0f7f0 100%);color:#2d4a2d;min-height:100vh}
        .floral-header{text-align:center;padding:4rem 1.5rem 3rem;position:relative;background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='40' fill='none' stroke='%238fbc8f20' stroke-width='0.5'/%3E%3Ccircle cx='50' cy='50' r='30' fill='none' stroke='%238fbc8f15' stroke-width='0.5'/%3E%3Ccircle cx='50' cy='50' r='20' fill='none' stroke='%238fbc8f10' stroke-width='0.5'/%3E%3C/svg%3E") center/200px repeat}
        .floral-ornament{font-size:2rem;color:{{ $themeColor }};margin-bottom:1rem}
        .floral-header h2{font-size:.85rem;font-weight:400;color:#5a8a5a;letter-spacing:2px;margin-bottom:1rem}
        .floral-header h1{font-size:2.2rem;font-weight:300;color:#2d4a2d;line-height:1.4}
        .floral-header .amp{font-size:1.5rem;color:{{ $themeColor }};margin:0 .5rem;font-style:italic}
        .floral-vine{width:100px;height:20px;margin:1.5rem auto;background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 20'%3E%3Cpath d='M0 10 Q25 0 50 10 Q75 20 100 10' fill='none' stroke='%238fbc8f' stroke-width='1'/%3E%3C/svg%3E") center/contain no-repeat}
        .floral-date{font-size:1rem;color:#5a8a5a;font-weight:300;letter-spacing:1px}
        .floral-countdown{display:flex;justify-content:center;gap:1rem;margin-top:2rem}
        .floral-countdown .cd-item{text-align:center;background:rgba(255,255,255,.7);backdrop-filter:blur(8px);padding:1rem;border-radius:1rem;min-width:65px;border:1px solid {{ $themeColor }}25}
        .floral-countdown .cd-num{font-size:1.6rem;font-weight:300;color:{{ $themeColor }};line-height:1}
        .floral-countdown .cd-label{font-size:.6rem;color:#5a8a5a;margin-top:.5rem}
        .floral-container{max-width:500px;margin:0 auto;padding:0 1.5rem 2rem}
        .floral-section{background:rgba(255,255,255,.7);backdrop-filter:blur(10px);border-radius:1.5rem;padding:2rem;margin-bottom:1.5rem;border:1px solid {{ $themeColor }}20;box-shadow:0 4px 20px rgba(143,188,143,.1)}
        .floral-section-title{text-align:center;font-size:.85rem;font-weight:500;color:{{ $themeColor }};margin-bottom:1.5rem;display:flex;align-items:center;justify-content:center;gap:.5rem}
        .floral-section-title::before{content:'✿';font-size:1rem}
        .floral-section-title::after{content:'✿';font-size:1rem}
        .floral-gallery{display:grid;grid-template-columns:repeat(2,1fr);gap:.75rem}
        .floral-gallery img{width:100%;aspect-ratio:1;object-fit:cover;border-radius:1rem;border:3px solid #fff;box-shadow:0 2px 12px rgba(0,0,0,.1);transition:transform .3s}
        .floral-gallery img:hover{transform:scale(1.03) rotate(1deg)}
        .floral-timeline{position:relative}
        .floral-timeline-item{display:flex;gap:1rem;padding:1rem 0;border-bottom:1px solid {{ $themeColor }}15}
        .floral-timeline-item:last-child{border-bottom:none}
        .floral-timeline-item .tl-icon{width:36px;height:36px;border-radius:50%;background:{{ $themeColor }}15;display:flex;align-items:center;justify-content:center;color:{{ $themeColor }};flex-shrink:0;font-size:.9rem}
        .floral-timeline-item .tl-title{font-weight:600;font-size:.88rem;color:#2d4a2d}
        .floral-timeline-item .tl-desc{font-size:.8rem;color:#5a8a5a;margin-top:.25rem}
        .floral-social{display:flex;flex-wrap:wrap;justify-content:center;gap:.75rem}
        .floral-social a{display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1rem;background:{{ $themeColor }}15;color:{{ $themeColor }};border-radius:2rem;font-size:.8rem;text-decoration:none;transition:all .2s}
        .floral-social a:hover{background:{{ $themeColor }};color:#fff}
        .floral-map{border-radius:1rem;overflow:hidden;height:200px;border:3px solid #fff;box-shadow:0 2px 12px rgba(0,0,0,.1)}
        .floral-footer{text-align:center;padding:2rem 1rem;color:#8fbc8f;font-size:.78rem}
        .floral-footer a{color:{{ $themeColor }};text-decoration:none}
        .floral-contact{text-align:center}
        .floral-contact a,.floral-contact span{color:#3d5c3d;text-decoration:none;font-size:.9rem;display:block;margin-bottom:.75rem}
        .floral-contact i{color:{{ $themeColor }};margin-left:.5rem}
        .floral-qr{text-align:center;padding:1.5rem;background:rgba(255,255,255,.5);border-radius:1rem}
        .floral-qr img{max-width:100px}
        .floral-rsvp input,.floral-rsvp select{border:1px solid {{ $themeColor }}30;border-radius:1rem;padding:.7rem 1.2rem;width:100%;margin-bottom:1rem;font-size:.88rem;font-family:'Vazirmatn',sans-serif;background:rgba(255,255,255,.5);color:#2d4a2d}
        .floral-rsvp button{background:{{ $themeColor }};color:#fff;border:none;border-radius:1rem;padding:.7rem 2rem;width:100%;font-size:.9rem;cursor:pointer;transition:opacity .2s}
        .floral-rsvp button:hover{opacity:.9}
    </style>
</head>
<body>
    <div class="floral-header">
        <div class="floral-ornament">🌸</div>
        <h2>دعوت نامه عروسی</h2>
        <h1>
            {{ $card->title }}
            @if($meta['partner_name'] ?? false)
                <span class="amp">&</span> {{ $meta['partner_name'] }}
            @endif
        </h1>
        <div class="floral-vine"></div>
        @if($meta['wedding_date'] ?? false)
            @php try { $wdCarbon = \Carbon\Carbon::parse($meta['wedding_date']); } catch (\Exception $e) { $wdCarbon = null; } @endphp
            @if($wdCarbon)
                <div class="floral-date">{{ \Morilog\Jalali\Jalalian::fromCarbon($wdCarbon)->format('l d F Y') }}</div>
            @endif
        @endif
        @if($meta['wedding_date'] ?? false)
            @php $wd = $wdCarbon ?? null; @endphp
            @if($wd)
            <div class="floral-countdown" id="countdown">
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
            <p style="margin-top:1.5rem;font-size:.9rem;color:#5a8a5a;max-width:400px;margin-left:auto;margin-right:auto;line-height:1.8;font-style:italic">{{ $card->description }}</p>
        @endif
    </div>

    <div class="floral-container">
        @foreach($sections as $section)
            @if($section->type === 'contact')
                <div class="floral-section">
                    <div class="floral-section-title">{{ $section->title ?: 'تماس' }}</div>
                    <div class="floral-contact">
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
                <div class="floral-section">
                    <div class="floral-section-title">{{ $section->title ?: 'شبکه‌ها' }}</div>
                    <div class="floral-social">
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
                <div class="floral-section">
                    <div class="floral-section-title">{{ $section->title ?: 'گالری' }}</div>
                    <div class="floral-gallery">
                        @foreach($card->galleryItems->sortBy('sort_order') as $item)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->caption }}" loading="lazy">
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'timeline' && $section->items && $section->items->count())
                <div class="floral-section">
                    <div class="floral-section-title">{{ $section->title ?: 'داستان عشق' }}</div>
                    <div class="floral-timeline">
                        @foreach($section->items->sortBy('sort_order') as $item)
                            <div class="floral-timeline-item">
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
                <div class="floral-section">
                    <div class="floral-section-title">{{ $section->title ?: 'حضور' }}</div>
                    <div class="floral-rsvp">
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
                <div class="floral-section">
                    <div style="text-align:center;font-size:.9rem;line-height:2;color:#3d5c3d">{!! nl2br(e($section->content)) !!}</div>
                </div>
            @endif
        @endforeach

        @if($card->map_lat && $card->map_lng)
            <div class="floral-section">
                <div class="floral-section-title">محل برگزاری</div>
                <div class="floral-map">
                    <iframe src="https://www.openstreetmap.org/export/embed.html?bbox={{ $card->map_lng - 0.01 }}%2C{{ $card->map_lat - 0.01 }}%2C{{ $card->map_lng + 0.01 }}%2C{{ $card->map_lat + 0.01 }}&layer=mapnik&marker={{ $card->map_lat }}%2C{{ $card->map_lng }}" width="100%" height="200" style="border:0" loading="lazy"></iframe>
                </div>
            </div>
        @endif

        @if($card->qrCodes->where('is_active', true)->count())
            @php $qr = $card->qrCodes->where('is_active', true)->first(); @endphp
            @if($qr)
                @php
                    $qrOpts = new \Chillerlan\QRCode\QROptions(['outputInterface' => \Chillerlan\QRCode\Output\QRGdImagePNG::class, 'eccLevel' => \Chillerlan\QRCode\QRCode::ECC_M, 'scale' => 5, 'imageBase64' => false, 'bgColor' => '#f0f7f0', 'moduleValues' => [\Chillerlan\QRCode\Data\QRMatrix::M_DATA_DARK => '#8fbc8f', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DARK => '#8fbc8f', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DOT => '#8fbc8f', \Chillerlan\QRCode\Data\QRMatrix::M_ALIGNMENT_DARK => '#8fbc8f']]);
                    $qrGen = new \Chillerlan\QRCode\QRCode($qrOpts);
                    $qrData = base64_encode($qrGen->render(route('qr.redirect', $qr->unique_code)));
                @endphp
                <div class="floral-qr">
                    <img src="data:image/png;base64,{{ $qrData }}" alt="QR">
                </div>
            @endif
        @endif

        <div class="floral-footer">ساخته شده با <a href="/">کارت‌اکس</a></div>
    </div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
