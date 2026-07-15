@php
    $sections = $card->sections->where('is_visible', true)->sortBy('sort_order');
    $themeColor = $card->theme_color ?: '#4a0080';
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
        body{font-family:'Vazirmatn',sans-serif;background:linear-gradient(180deg,#1a0a2e 0%,#0d0521 100%);color:#e8d5f5;min-height:100vh}
        .royal-header{text-align:center;padding:4rem 1.5rem 3rem;position:relative;border-bottom:1px solid #ffd70030}
        .royal-header::before{content:'';position:absolute;top:0;left:50%;transform:translateX(-50%);width:300px;height:1px;background:linear-gradient(90deg,transparent,#ffd700,transparent)}
        .royal-crown{font-size:2rem;color:#ffd700;margin-bottom:1rem}
        .royal-header h2{font-size:.8rem;font-weight:400;color:#ffd700;letter-spacing:4px;text-transform:uppercase;margin-bottom:1rem}
        .royal-header h1{font-size:2.5rem;font-weight:300;color:#fff;line-height:1.3}
        .royal-header .amp{font-size:1.8rem;color:#ffd700;margin:0 .5rem;font-style:italic}
        .royal-ornament{width:80px;height:1px;background:linear-gradient(90deg,transparent,#ffd700,transparent);margin:1.5rem auto}
        .royal-date-box{display:inline-block;border:1px solid #ffd70040;padding:1rem 2.5rem;margin-top:1.5rem;background:rgba(255,215,0,.05)}
        .royal-date-box .day{font-size:2rem;font-weight:300;color:#ffd700;line-height:1}
        .royal-date-box .month{font-size:.8rem;color:#b8860b;letter-spacing:2px;margin-top:.25rem}
        .royal-countdown{display:flex;justify-content:center;gap:1.5rem;margin-top:2rem}
        .royal-countdown .cd-item{text-align:center;min-width:70px}
        .royal-countdown .cd-num{font-size:2rem;font-weight:200;color:#ffd700;line-height:1}
        .royal-countdown .cd-label{font-size:.65rem;color:#b8860b;letter-spacing:1px;margin-top:.25rem}
        .royal-countdown .cd-divider{color:#ffd70040;font-size:1.5rem;line-height:2}
        .royal-container{max-width:500px;margin:0 auto;padding:0 1.5rem 2rem}
        .royal-section{background:rgba(255,255,255,.03);border:1px solid #ffd70015;padding:2rem;margin-bottom:1.5rem;position:relative}
        .royal-section::before{content:'';position:absolute;top:-1px;left:20%;right:20%;height:1px;background:linear-gradient(90deg,transparent,#ffd70030,transparent)}
        .royal-section-title{text-align:center;font-size:.75rem;font-weight:500;color:#ffd700;letter-spacing:3px;text-transform:uppercase;margin-bottom:1.5rem}
        .royal-gallery{display:grid;grid-template-columns:repeat(2,1fr);gap:.75rem}
        .royal-gallery img{width:100%;aspect-ratio:1;object-fit:cover;border:2px solid #ffd70020;transition:all .4s}
        .royal-gallery img:hover{border-color:#ffd700;transform:scale(1.02)}
        .royal-timeline{position:relative;padding-right:2rem}
        .royal-timeline::before{content:'';position:absolute;right:0;top:0;bottom:0;width:1px;background:#ffd70030}
        .royal-timeline-item{position:relative;padding:1rem 0}
        .royal-timeline-item::before{content:'';position:absolute;right:-4px;top:1.2rem;width:8px;height:8px;background:#ffd700;transform:rotate(45deg)}
        .royal-timeline-item .tl-title{font-weight:600;font-size:.9rem;color:#fff}
        .royal-timeline-item .tl-desc{font-size:.8rem;color:#b8860b;margin-top:.25rem}
        .royal-social-row{display:flex;flex-wrap:wrap;justify-content:center;gap:.75rem}
        .royal-social-pill{display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1rem;border:1px solid #ffd70030;color:#ffd700;font-size:.78rem;text-decoration:none;transition:all .3s;letter-spacing:.5px}
        .royal-social-pill:hover{background:#ffd700;color:#1a0a2e;border-color:#ffd700}
        .royal-map{height:200px;border:1px solid #ffd70020;overflow:hidden}
        .royal-footer{text-align:center;padding:3rem 1rem;color:#4a0080;font-size:.75rem;letter-spacing:2px;text-transform:uppercase}
        .royal-footer a{color:#ffd700;text-decoration:none}
        .royal-contact{text-align:center}
        .royal-contact a,.royal-contact span{color:#ccc;text-decoration:none;font-size:.88rem;display:block;margin-bottom:.5rem}
        .royal-contact i{color:#ffd700;margin-left:.5rem}
        .royal-qr{text-align:center;padding:1.5rem;border:1px solid #ffd70020}
        .royal-qr img{max-width:100px}
        .royal-rsvp input,.royal-rsvp select{background:transparent;border:1px solid #ffd70030;color:#fff;padding:.7rem 1rem;width:100%;margin-bottom:1rem;font-size:.88rem;font-family:'Vazirmatn',sans-serif}
        .royal-rsvp input::placeholder{color:#666}
        .royal-rsvp button{background:#ffd700;color:#1a0a2e;border:none;padding:.8rem 2rem;width:100%;font-size:.9rem;font-weight:600;cursor:pointer;letter-spacing:1px;text-transform:uppercase;transition:all .3s}
        .royal-rsvp button:hover{background:#fff}
    </style>
</head>
<body>
    <div class="royal-header">
        <div class="royal-crown">👑</div>
        <h2>دعوت نامه عروسی</h2>
        <h1>
            {{ $card->title }}
            @if($meta['partner_name'] ?? false)
                <span class="amp">&</span> {{ $meta['partner_name'] }}
            @endif
        </h1>
        <div class="royal-ornament"></div>
        @if($meta['wedding_date'] ?? false)
            @php try { $wdCarbon = \Carbon\Carbon::parse($meta['wedding_date']); } catch (\Exception $e) { $wdCarbon = null; } @endphp
            @if($wdCarbon)
            <div class="royal-date-box">
                <div class="day">{{ \Morilog\Jalali\Jalalian::fromCarbon($wdCarbon)->format('d') }}</div>
                <div class="month">{{ \Morilog\Jalali\Jalalian::fromCarbon($wdCarbon)->format('F Y') }}</div>
            </div>
            @endif
        @endif
        @if($meta['wedding_date'] ?? false)
            @php $wd = $wdCarbon ?? null; @endphp
            @if($wd)
            <div class="royal-countdown" id="countdown">
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
            <p style="margin-top:1.5rem;font-size:.88rem;color:#b8860b;max-width:400px;margin-left:auto;margin-right:auto;line-height:1.8;font-style:italic">{{ $card->description }}</p>
        @endif
    </div>

    <div class="royal-container">
        @foreach($sections as $section)
            @if($section->type === 'contact')
                <div class="royal-section">
                    <div class="royal-section-title">{{ $section->title ?: 'اطلاعات تماس' }}</div>
                    <div class="royal-contact">
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
                <div class="royal-section">
                    <div class="royal-section-title">{{ $section->title ?: 'شبکه‌های اجتماعی' }}</div>
                    <div class="royal-social-row">
                        @foreach($card->socialLinks->sortBy('sort_order') as $link)
                            <a href="{{ $link->url }}" class="royal-social-pill" target="_blank">
                                <i class="bi bi-{{ $link->platform === 'instagram' ? 'instagram' : ($link->platform === 'telegram' ? 'telegram' : 'globe') }}"></i> {{ ucfirst($link->platform) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'gallery' && $card->galleryItems->count())
                <div class="royal-section">
                    <div class="royal-section-title">{{ $section->title ?: 'گالری' }}</div>
                    <div class="royal-gallery">
                        @foreach($card->galleryItems->sortBy('sort_order') as $item)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->caption }}" loading="lazy">
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'timeline' && $section->items && $section->items->count())
                <div class="royal-section">
                    <div class="royal-section-title">{{ $section->title ?: 'داستان عشق' }}</div>
                    <div class="royal-timeline">
                        @foreach($section->items->sortBy('sort_order') as $item)
                            <div class="royal-timeline-item">
                                <div class="tl-title">{{ $item->name }}</div>
                                <div class="tl-desc">{{ $item->description }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'rsvp')
                <div class="royal-section">
                    <div class="royal-section-title">{{ $section->title ?: 'تأیید حضور' }}</div>
                    <div class="royal-rsvp">
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
                <div class="royal-section">
                    <div style="text-align:center;font-size:.9rem;line-height:2;color:#b8860b">{!! nl2br(e($section->content)) !!}</div>
                </div>
            @endif
        @endforeach

        @if($card->map_lat && $card->map_lng)
            <div class="royal-section">
                <div class="royal-section-title">محل برگزاری</div>
                <div class="royal-map">
                    <iframe src="https://www.openstreetmap.org/export/embed.html?bbox={{ $card->map_lng - 0.01 }}%2C{{ $card->map_lat - 0.01 }}%2C{{ $card->map_lng + 0.01 }}%2C{{ $card->map_lat + 0.01 }}&layer=mapnik&marker={{ $card->map_lat }}%2C{{ $card->map_lng }}" width="100%" height="200" style="border:0;filter:grayscale(80%)" loading="lazy"></iframe>
                </div>
            </div>
        @endif

        @if($card->qrCodes->where('is_active', true)->count())
            @php $qr = $card->qrCodes->where('is_active', true)->first(); @endphp
            @if($qr)
                @php
                    $qrOpts = new \Chillerlan\QRCode\QROptions(['outputInterface' => \Chillerlan\QRCode\Output\QRGdImagePNG::class, 'eccLevel' => \Chillerlan\QRCode\QRCode::ECC_M, 'scale' => 5, 'imageBase64' => false, 'bgColor' => '#1a0a2e', 'moduleValues' => [\Chillerlan\QRCode\Data\QRMatrix::M_DATA_DARK => '#ffd700', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DARK => '#ffd700', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DOT => '#ffd700', \Chillerlan\QRCode\Data\QRMatrix::M_ALIGNMENT_DARK => '#ffd700']]);
                    $qrGen = new \Chillerlan\QRCode\QRCode($qrOpts);
                    $qrData = base64_encode($qrGen->render(route('qr.redirect', $qr->unique_code)));
                @endphp
                <div class="royal-qr">
                    <img src="data:image/png;base64,{{ $qrData }}" alt="QR">
                </div>
            @endif
        @endif

        <div class="royal-footer">ساخته شده با <a href="/">کارت‌اکس</a></div>
    </div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
