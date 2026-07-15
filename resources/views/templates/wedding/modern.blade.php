@php
    $sections = $card->sections->where('is_visible', true)->sortBy('sort_order');
    $themeColor = $card->theme_color ?: '#6366f1';
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
        body{font-family:'Vazirmatn',sans-serif;background:#fafafa;color:#1a1a2e;min-height:100vh}
        .mod-header{text-align:center;padding:4rem 1.5rem 3rem;background:linear-gradient(135deg,{{ $themeColor }} 0%,{{ $themeColor }}cc 100%);color:#fff}
        .mod-header h2{font-size:.75rem;font-weight:400;letter-spacing:4px;text-transform:uppercase;margin-bottom:1rem;opacity:.8}
        .mod-header h1{font-size:2.5rem;font-weight:300;line-height:1.3}
        .mod-header .amp{font-size:1.5rem;margin:0 .5rem;font-weight:600}
        .mod-divider{width:60px;height:3px;background:rgba(255,255,255,.5);margin:1.5rem auto;border-radius:2px}
        .mod-date{font-size:1rem;font-weight:300;letter-spacing:1px;opacity:.9}
        .mod-countdown{display:flex;justify-content:center;gap:1rem;margin-top:2rem}
        .mod-countdown .cd-item{text-align:center;background:rgba(255,255,255,.15);backdrop-filter:blur(10px);padding:1rem;border-radius:1rem;min-width:65px}
        .mod-countdown .cd-num{font-size:1.8rem;font-weight:300;line-height:1}
        .mod-countdown .cd-label{font-size:.6rem;opacity:.7;margin-top:.5rem}
        .mod-container{max-width:500px;margin:0 auto;padding:0 1.5rem 2rem}
        .mod-section{background:#fff;border-radius:1.5rem;padding:2rem;margin-bottom:1.5rem;box-shadow:0 4px 24px rgba(0,0,0,.06)}
        .mod-section-title{text-align:center;font-size:.85rem;font-weight:600;color:{{ $themeColor }};margin-bottom:1.5rem;display:flex;align-items:center;justify-content:center;gap:.5rem}
        .mod-section-title::before{content:'';width:20px;height:2px;background:{{ $themeColor }};border-radius:1px}
        .mod-section-title::after{content:'';width:20px;height:2px;background:{{ $themeColor }};border-radius:1px}
        .mod-gallery{display:grid;grid-template-columns:repeat(2,1fr);gap:.75rem}
        .mod-gallery img{width:100%;aspect-ratio:1;object-fit:cover;border-radius:1rem;transition:transform .3s}
        .mod-gallery img:hover{transform:scale(1.03)}
        .mod-timeline{position:relative}
        .mod-timeline-item{display:flex;gap:1rem;padding:1rem 0;border-bottom:1px solid #f0f0f0}
        .mod-timeline-item:last-child{border-bottom:none}
        .mod-timeline-item .tl-icon{width:36px;height:36px;border-radius:50%;background:{{ $themeColor }}15;display:flex;align-items:center;justify-content:center;color:{{ $themeColor }};flex-shrink:0;font-size:.9rem}
        .mod-timeline-item .tl-title{font-weight:600;font-size:.88rem}
        .mod-timeline-item .tl-desc{font-size:.8rem;color:#666;margin-top:.25rem}
        .mod-social{display:flex;flex-wrap:wrap;justify-content:center;gap:.75rem}
        .mod-social a{display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1rem;background:{{ $themeColor }}10;color:{{ $themeColor }};border-radius:.75rem;font-size:.8rem;text-decoration:none;transition:all .2s}
        .mod-social a:hover{background:{{ $themeColor }};color:#fff}
        .mod-map{border-radius:1rem;overflow:hidden;height:200px}
        .mod-footer{text-align:center;padding:2rem 1rem;color:#ccc;font-size:.75rem}
        .mod-footer a{color:{{ $themeColor }};text-decoration:none}
        .mod-contact{text-align:center}
        .mod-contact a,.mod-contact span{color:#333;text-decoration:none;font-size:.88rem;display:block;margin-bottom:.75rem}
        .mod-contact i{color:{{ $themeColor }};margin-left:.5rem}
        .mod-qr{text-align:center;padding:1.5rem;background:#f8f8f8;border-radius:1rem}
        .mod-qr img{max-width:100px}
        .mod-rsvp input,.mod-rsvp select{border:1px solid #e0e0e0;border-radius:.75rem;padding:.7rem 1rem;width:100%;margin-bottom:1rem;font-size:.88rem;font-family:'Vazirmatn',sans-serif}
        .mod-rsvp button{background:{{ $themeColor }};color:#fff;border:none;border-radius:.75rem;padding:.7rem 2rem;width:100%;font-size:.9rem;cursor:pointer;transition:opacity .2s}
        .mod-rsvp button:hover{opacity:.9}
    </style>
</head>
<body>
    <div class="mod-header">
        <h2>دعوت نامه عروسی</h2>
        <h1>
            {{ $card->title }}
            @if($meta['partner_name'] ?? false)
                <span class="amp">&</span> {{ $meta['partner_name'] }}
            @endif
        </h1>
        <div class="mod-divider"></div>
        @if($meta['wedding_date'] ?? false)
            @php try { $wdCarbon = \Carbon\Carbon::parse($meta['wedding_date']); } catch (\Exception $e) { $wdCarbon = null; } @endphp
            @if($wdCarbon)
                <div class="mod-date">{{ \Morilog\Jalali\Jalalian::fromCarbon($wdCarbon)->format('l d F Y') }}</div>
            @endif
        @endif
        @if($meta['wedding_date'] ?? false)
            @php $wd = $wdCarbon ?? null; @endphp
            @if($wd)
            <div class="mod-countdown" id="countdown">
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
            <p style="margin-top:1.5rem;font-size:.9rem;opacity:.8;max-width:400px;margin-left:auto;margin-right:auto;line-height:1.8">{{ $card->description }}</p>
        @endif
    </div>

    <div class="mod-container">
        @foreach($sections as $section)
            @if($section->type === 'contact')
                <div class="mod-section">
                    <div class="mod-section-title">{{ $section->title ?: 'تماس' }}</div>
                    <div class="mod-contact">
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
                <div class="mod-section">
                    <div class="mod-section-title">{{ $section->title ?: 'شبکه‌ها' }}</div>
                    <div class="mod-social">
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
                <div class="mod-section">
                    <div class="mod-section-title">{{ $section->title ?: 'گالری' }}</div>
                    <div class="mod-gallery">
                        @foreach($card->galleryItems->sortBy('sort_order') as $item)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->caption }}" loading="lazy">
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'timeline' && $section->items && $section->items->count())
                <div class="mod-section">
                    <div class="mod-section-title">{{ $section->title ?: 'داستان ما' }}</div>
                    <div class="mod-timeline">
                        @foreach($section->items->sortBy('sort_order') as $item)
                            <div class="mod-timeline-item">
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
                <div class="mod-section">
                    <div class="mod-section-title">{{ $section->title ?: 'حضور' }}</div>
                    <div class="mod-rsvp">
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
                <div class="mod-section">
                    <div style="text-align:center;font-size:.9rem;line-height:2;color:#555">{!! nl2br(e($section->content)) !!}</div>
                </div>
            @endif
        @endforeach

        @if($card->map_lat && $card->map_lng)
            <div class="mod-section">
                <div class="mod-section-title">محل برگزاری</div>
                <div class="mod-map">
                    <iframe src="https://www.openstreetmap.org/export/embed.html?bbox={{ $card->map_lng - 0.01 }}%2C{{ $card->map_lat - 0.01 }}%2C{{ $card->map_lng + 0.01 }}%2C{{ $card->map_lat + 0.01 }}&layer=mapnik&marker={{ $card->map_lat }}%2C{{ $card->map_lng }}" width="100%" height="200" style="border:0" loading="lazy"></iframe>
                </div>
            </div>
        @endif

        @if($card->qrCodes->where('is_active', true)->count())
            @php $qr = $card->qrCodes->where('is_active', true)->first(); @endphp
            @if($qr)
                @php
                    $qrOpts = new \Chillerlan\QRCode\QROptions(['outputInterface' => \Chillerlan\QRCode\Output\QRGdImagePNG::class, 'eccLevel' => \Chillerlan\QRCode\QRCode::ECC_M, 'scale' => 5, 'imageBase64' => false, 'bgColor' => '#ffffff', 'moduleValues' => [\Chillerlan\QRCode\Data\QRMatrix::M_DATA_DARK => '#6366f1', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DARK => '#6366f1', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DOT => '#6366f1', \Chillerlan\QRCode\Data\QRMatrix::M_ALIGNMENT_DARK => '#6366f1']]);
                    $qrGen = new \Chillerlan\QRCode\QRCode($qrOpts);
                    $qrData = base64_encode($qrGen->render(route('qr.redirect', $qr->unique_code)));
                @endphp
                <div class="mod-qr">
                    <img src="data:image/png;base64,{{ $qrData }}" alt="QR">
                </div>
            @endif
        @endif

        <div class="mod-footer">ساخته شده با <a href="/">کارت‌اکس</a></div>
    </div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
