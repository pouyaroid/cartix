@php
    $sections = $card->sections->where('is_visible', true)->sortBy('sort_order');
    $themeColor = $card->theme_color ?: '#2d2d2d';
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
        body{font-family:'Vazirmatn',sans-serif;background:#fafafa;color:#333;min-height:100vh}
        .min-header{text-align:center;padding:5rem 1.5rem 3rem}
        .min-header h2{font-size:.7rem;font-weight:500;color:#999;letter-spacing:4px;text-transform:uppercase;margin-bottom:2rem}
        .min-header h1{font-size:2rem;font-weight:300;color:{{ $themeColor }};line-height:1.5}
        .min-header .amp{font-weight:600;margin:0 .25rem}
        .min-divider{width:40px;height:1px;background:{{ $themeColor }};margin:2rem auto}
        .min-date{font-size:1rem;color:#666;font-weight:300;letter-spacing:1px}
        .min-countdown{display:flex;justify-content:center;gap:2rem;margin-top:2rem}
        .min-countdown .cd-item{text-align:center}
        .min-countdown .cd-num{font-size:2.5rem;font-weight:200;color:{{ $themeColor }};line-height:1}
        .min-countdown .cd-label{font-size:.65rem;color:#999;letter-spacing:1px;margin-top:.5rem}
        .min-container{max-width:480px;margin:0 auto;padding:0 2rem 3rem}
        .min-section{margin-bottom:3rem}
        .min-section-title{text-align:center;font-size:.7rem;font-weight:500;color:#999;letter-spacing:3px;text-transform:uppercase;margin-bottom:1.5rem}
        .min-gallery{display:grid;grid-template-columns:1fr;gap:1px;background:#eee}
        .min-gallery img{width:100%;aspect-ratio:4/3;object-fit:cover;display:block}
        .min-timeline{text-align:center}
        .min-timeline-item{padding:1.5rem 0;border-bottom:1px solid #f0f0f0}
        .min-timeline-item:last-child{border-bottom:none}
        .min-timeline-item .tl-year{font-size:.7rem;color:#999;letter-spacing:2px;margin-bottom:.5rem}
        .min-timeline-item .tl-title{font-size:1rem;font-weight:500;color:{{ $themeColor }}}
        .min-timeline-item .tl-desc{font-size:.85rem;color:#888;margin-top:.25rem}
        .min-social{display:flex;flex-wrap:wrap;justify-content:center;gap:1rem}
        .min-social a{color:#999;font-size:.8rem;text-decoration:none;transition:color .2s;letter-spacing:.5px}
        .min-social a:hover{color:{{ $themeColor }}}
        .min-map{height:200px;background:#f0f0f0}
        .min-footer{text-align:center;padding:3rem 1rem;color:#ccc;font-size:.7rem;letter-spacing:2px;text-transform:uppercase}
        .min-footer a{color:#999;text-decoration:none}
        .min-contact{text-align:center;margin-bottom:1rem}
        .min-contact a,.min-contact span{color:#666;text-decoration:none;font-size:.9rem;display:block;margin-bottom:.5rem;font-weight:300}
        .min-contact i{color:#999;margin-left:.5rem}
        .min-qr{text-align:center;margin-top:2rem}
        .min-qr img{max-width:80px;opacity:.8}
        .min-rsvp input,.min-rsvp select{border:none;border-bottom:1px solid #ddd;background:transparent;padding:.8rem 0;width:100%;margin-bottom:1.5rem;font-size:.9rem;font-family:'Vazirmatn',sans-serif;color:#333;text-align:center}
        .min-rsvp input:focus,.min-rsvp select:focus{outline:none;border-color:{{ $themeColor }}}
        .min-rsvp button{background:{{ $themeColor }};color:#fff;border:none;padding:.8rem 3rem;font-size:.85rem;cursor:pointer;letter-spacing:1px;transition:opacity .2s}
        .min-rsvp button:hover{opacity:.8}
    </style>
</head>
<body>
    <div class="min-header">
        <h2>دعوت نامه عروسی</h2>
        <h1>
            {{ $card->title }}
            @if($meta['partner_name'] ?? false)
                <span class="amp">&</span> {{ $meta['partner_name'] }}
            @endif
        </h1>
        <div class="min-divider"></div>
        @if($meta['wedding_date'] ?? false)
            @php try { $wdCarbon = \Carbon\Carbon::parse($meta['wedding_date']); } catch (\Exception $e) { $wdCarbon = null; } @endphp
            @if($wdCarbon)
                <div class="min-date">{{ \Morilog\Jalali\Jalalian::fromCarbon($wdCarbon)->format('l d F Y') }}</div>
            @endif
        @endif
        @if($meta['wedding_date'] ?? false)
            @php $wd = $wdCarbon ?? null; @endphp
            @if($wd)
            <div class="min-countdown" id="countdown">
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
            <p style="margin-top:2rem;font-size:.9rem;color:#999;max-width:350px;margin-left:auto;margin-right:auto;line-height:2;font-weight:300">{{ $card->description }}</p>
        @endif
    </div>

    <div class="min-container">
        @foreach($sections as $section)
            @if($section->type === 'contact')
                <div class="min-section">
                    <div class="min-section-title">{{ $section->title ?: 'تماس' }}</div>
                    <div class="min-contact">
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
                <div class="min-section">
                    <div class="min-section-title">{{ $section->title ?: 'شبکه‌ها' }}</div>
                    <div class="min-social">
                        @foreach($card->socialLinks->sortBy('sort_order') as $link)
                            <a href="{{ $link->url }}" target="_blank">{{ ucfirst($link->platform) }}</a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'gallery' && $card->galleryItems->count())
                <div class="min-section">
                    <div class="min-section-title">{{ $section->title ?: 'گالری' }}</div>
                    <div class="min-gallery">
                        @foreach($card->galleryItems->sortBy('sort_order') as $item)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->caption }}" loading="lazy">
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'timeline' && $section->items && $section->items->count())
                <div class="min-section">
                    <div class="min-section-title">{{ $section->title ?: 'داستان ما' }}</div>
                    <div class="min-timeline">
                        @foreach($section->items->sortBy('sort_order') as $item)
                            <div class="min-timeline-item">
                                <div class="tl-title">{{ $item->name }}</div>
                                <div class="tl-desc">{{ $item->description }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'rsvp')
                <div class="min-section">
                    <div class="min-section-title">{{ $section->title ?: 'حضور' }}</div>
                    <div class="min-rsvp">
                        <input type="text" placeholder="نام شما">
                        <select>
                            <option>حضور دارم</option>
                            <option>غایب هستم</option>
                        </select>
                        <button>تأیید</button>
                    </div>
                </div>
            @endif

            @if($section->type === 'custom' && $section->content)
                <div class="min-section">
                    <div style="text-align:center;font-size:.9rem;line-height:2.2;color:#888;font-weight:300">{!! nl2br(e($section->content)) !!}</div>
                </div>
            @endif
        @endforeach

        @if($card->map_lat && $card->map_lng)
            <div class="min-section">
                <div class="min-section-title">محل برگزاری</div>
                <div class="min-map">
                    <iframe src="https://www.openstreetmap.org/export/embed.html?bbox={{ $card->map_lng - 0.01 }}%2C{{ $card->map_lat - 0.01 }}%2C{{ $card->map_lng + 0.01 }}%2C{{ $card->map_lat + 0.01 }}&layer=mapnik&marker={{ $card->map_lat }}%2C{{ $card->map_lng }}" width="100%" height="200" style="border:0;filter:grayscale(100%)" loading="lazy"></iframe>
                </div>
            </div>
        @endif

        @if($card->qrCodes->where('is_active', true)->count())
            @php $qr = $card->qrCodes->where('is_active', true)->first(); @endphp
            @if($qr)
                @php
                    $qrOpts = new \Chillerlan\QRCode\QROptions(['outputInterface' => \Chillerlan\QRCode\Output\QRGdImagePNG::class, 'eccLevel' => \Chillerlan\QRCode\QRCode::ECC_M, 'scale' => 5, 'imageBase64' => false, 'bgColor' => '#fafafa', 'moduleValues' => [\Chillerlan\QRCode\Data\QRMatrix::M_DATA_DARK => '#2d2d2d', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DARK => '#2d2d2d', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DOT => '#2d2d2d', \Chillerlan\QRCode\Data\QRMatrix::M_ALIGNMENT_DARK => '#2d2d2d']]);
                    $qrGen = new \Chillerlan\QRCode\QRCode($qrOpts);
                    $qrData = base64_encode($qrGen->render(route('qr.redirect', $qr->unique_code)));
                @endphp
                <div class="min-qr">
                    <img src="data:image/png;base64,{{ $qrData }}" alt="QR">
                </div>
            @endif
        @endif

        <div class="min-footer">ساخته شده با <a href="/">کارت‌اکس</a></div>
    </div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
