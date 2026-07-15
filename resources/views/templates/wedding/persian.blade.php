@php
    $sections = $card->sections->where('is_visible', true)->sortBy('sort_order');
    $themeColor = $card->theme_color ?: '#b8860b';
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
        body{font-family:'Vazirmatn',sans-serif;background:linear-gradient(180deg,#fdf6e3 0%,#f5e6c8 100%);color:#3d2c1e;min-height:100vh}
        .prs-header{text-align:center;padding:4rem 1.5rem 3rem;position:relative;background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cpath d='M50 10 L60 40 L90 40 L65 60 L75 90 L50 70 L25 90 L35 60 L10 40 L40 40 Z' fill='none' stroke='%23b8860b10' stroke-width='0.5'/%3E%3C/svg%3E") center/150px repeat}
        .prs-ornament{font-size:2rem;color:{{ $themeColor }};margin-bottom:1rem}
        .prs-header h2{font-size:.85rem;font-weight:400;color:#8b7355;letter-spacing:3px;margin-bottom:1rem}
        .prs-header h1{font-size:2.2rem;font-weight:300;color:#3d2c1e;line-height:1.4}
        .prs-header .amp{font-size:1.5rem;color:{{ $themeColor }};margin:0 .5rem;font-style:italic}
        .prs-pattern{width:200px;height:30px;margin:1.5rem auto;background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 30'%3E%3Cpath d='M0 15 Q25 0 50 15 Q75 30 100 15 Q125 0 150 15 Q175 30 200 15' fill='none' stroke='%23b8860b' stroke-width='0.5'/%3E%3C/svg%3E") center/contain no-repeat}
        .prs-date{font-size:1rem;color:#8b7355;font-weight:300;letter-spacing:1px}
        .prs-countdown{display:flex;justify-content:center;gap:1rem;margin-top:2rem}
        .prs-countdown .cd-item{text-align:center;background:rgba(255,255,255,.6);backdrop-filter:blur(8px);padding:1rem;border-radius:1rem;min-width:65px;border:1px solid {{ $themeColor }}25}
        .prs-countdown .cd-num{font-size:1.6rem;font-weight:300;color:{{ $themeColor }};line-height:1}
        .prs-countdown .cd-label{font-size:.6rem;color:#8b7355;margin-top:.5rem}
        .prs-container{max-width:500px;margin:0 auto;padding:0 1.5rem 2rem}
        .prs-section{background:rgba(255,255,255,.6);backdrop-filter:blur(10px);border-radius:1.5rem;padding:2rem;margin-bottom:1.5rem;border:1px solid {{ $themeColor }}20;box-shadow:0 4px 20px rgba(184,134,11,.08)}
        .prs-section-title{text-align:center;font-size:.85rem;font-weight:500;color:{{ $themeColor }};margin-bottom:1.5rem;display:flex;align-items:center;justify-content:center;gap:.5rem}
        .prs-section-title::before{content:'◆';font-size:.6rem}
        .prs-section-title::after{content:'◆';font-size:.6rem}
        .prs-gallery{display:grid;grid-template-columns:repeat(2,1fr);gap:.75rem}
        .prs-gallery img{width:100%;aspect-ratio:1;object-fit:cover;border-radius:1rem;border:2px solid {{ $themeColor }}20;transition:all .3s}
        .prs-gallery img:hover{border-color:{{ $themeColor }};transform:scale(1.02)}
        .prs-timeline{position:relative}
        .prs-timeline-item{display:flex;gap:1rem;padding:1rem 0;border-bottom:1px solid {{ $themeColor }}10}
        .prs-timeline-item:last-child{border-bottom:none}
        .prs-timeline-item .tl-icon{width:36px;height:36px;border-radius:50%;background:{{ $themeColor }}15;display:flex;align-items:center;justify-content:center;color:{{ $themeColor }};flex-shrink:0;font-size:.9rem}
        .prs-timeline-item .tl-title{font-weight:600;font-size:.88rem;color:#3d2c1e}
        .prs-timeline-item .tl-desc{font-size:.8rem;color:#8b7355;margin-top:.25rem}
        .prs-social{display:flex;flex-wrap:wrap;justify-content:center;gap:.75rem}
        .prs-social a{display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1rem;background:{{ $themeColor }}10;color:{{ $themeColor }};border-radius:2rem;font-size:.8rem;text-decoration:none;transition:all .2s}
        .prs-social a:hover{background:{{ $themeColor }};color:#fff}
        .prs-map{border-radius:1rem;overflow:hidden;height:200px;border:2px solid {{ $themeColor }}20}
        .prs-footer{text-align:center;padding:2rem 1rem;color:#b8a88a;font-size:.78rem}
        .prs-footer a{color:{{ $themeColor }};text-decoration:none}
        .prs-contact{text-align:center}
        .prs-contact a,.prs-contact span{color:#3d2c1e;text-decoration:none;font-size:.88rem;display:block;margin-bottom:.75rem}
        .prs-contact i{color:{{ $themeColor }};margin-left:.5rem}
        .prs-qr{text-align:center;padding:1.5rem;background:rgba(255,255,255,.5);border-radius:1rem}
        .prs-qr img{max-width:100px}
        .prs-rsvp input,.prs-rsvp select{border:1px solid {{ $themeColor }}30;border-radius:1rem;padding:.7rem 1.2rem;width:100%;margin-bottom:1rem;font-size:.88rem;font-family:'Vazirmatn',sans-serif;background:rgba(255,255,255,.5);color:#3d2c1e}
        .prs-rsvp button{background:{{ $themeColor }};color:#fff;border:none;border-radius:1rem;padding:.7rem 2rem;width:100%;font-size:.9rem;cursor:pointer;transition:opacity .2s}
        .prs-rsvp button:hover{opacity:.9}
    </style>
</head>
<body>
    <div class="prs-header">
        <div class="prs-ornament">✿</div>
        <h2>دعوت نامه عروسی</h2>
        <h1>
            {{ $card->title }}
            @if($meta['partner_name'] ?? false)
                <span class="amp">&</span> {{ $meta['partner_name'] }}
            @endif
        </h1>
        <div class="prs-pattern"></div>
        @if($meta['wedding_date'] ?? false)
            @php try { $wdCarbon = \Carbon\Carbon::parse($meta['wedding_date']); } catch (\Exception $e) { $wdCarbon = null; } @endphp
            @if($wdCarbon)
                <div class="prs-date">{{ \Morilog\Jalali\Jalalian::fromCarbon($wdCarbon)->format('l d F Y') }}</div>
            @endif
        @endif
        @if($meta['wedding_date'] ?? false)
            @php $wd = $wdCarbon ?? null; @endphp
            @if($wd)
            <div class="prs-countdown" id="countdown">
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

    <div class="prs-container">
        @foreach($sections as $section)
            @if($section->type === 'contact')
                <div class="prs-section">
                    <div class="prs-section-title">{{ $section->title ?: 'تماس' }}</div>
                    <div class="prs-contact">
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
                <div class="prs-section">
                    <div class="prs-section-title">{{ $section->title ?: 'شبکه‌ها' }}</div>
                    <div class="prs-social">
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
                <div class="prs-section">
                    <div class="prs-section-title">{{ $section->title ?: 'گالری' }}</div>
                    <div class="prs-gallery">
                        @foreach($card->galleryItems->sortBy('sort_order') as $item)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->caption }}" loading="lazy">
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'timeline' && $section->items && $section->items->count())
                <div class="prs-section">
                    <div class="prs-section-title">{{ $section->title ?: 'داستان عشق' }}</div>
                    <div class="prs-timeline">
                        @foreach($section->items->sortBy('sort_order') as $item)
                            <div class="prs-timeline-item">
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
                <div class="prs-section">
                    <div class="prs-section-title">{{ $section->title ?: 'حضور' }}</div>
                    <div class="prs-rsvp">
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
                <div class="prs-section">
                    <div style="text-align:center;font-size:.9rem;line-height:2;color:#5a4a3a">{!! nl2br(e($section->content)) !!}</div>
                </div>
            @endif
        @endforeach

        @if($card->map_lat && $card->map_lng)
            <div class="prs-section">
                <div class="prs-section-title">محل برگزاری</div>
                <div class="prs-map">
                    <iframe src="https://www.openstreetmap.org/export/embed.html?bbox={{ $card->map_lng - 0.01 }}%2C{{ $card->map_lat - 0.01 }}%2C{{ $card->map_lng + 0.01 }}%2C{{ $card->map_lat + 0.01 }}&layer=mapnik&marker={{ $card->map_lat }}%2C{{ $card->map_lng }}" width="100%" height="200" style="border:0" loading="lazy"></iframe>
                </div>
            </div>
        @endif

        @if($card->qrCodes->where('is_active', true)->count())
            @php $qr = $card->qrCodes->where('is_active', true)->first(); @endphp
            @if($qr)
                @php
                    $qrOpts = new \Chillerlan\QRCode\QROptions(['outputInterface' => \Chillerlan\QRCode\Output\QRGdImagePNG::class, 'eccLevel' => \Chillerlan\QRCode\QRCode::ECC_M, 'scale' => 5, 'imageBase64' => false, 'bgColor' => '#fdf6e3', 'moduleValues' => [\Chillerlan\QRCode\Data\QRMatrix::M_DATA_DARK => '#b8860b', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DARK => '#b8860b', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DOT => '#b8860b', \Chillerlan\QRCode\Data\QRMatrix::M_ALIGNMENT_DARK => '#b8860b']]);
                    $qrGen = new \Chillerlan\QRCode\QRCode($qrOpts);
                    $qrData = base64_encode($qrGen->render(route('qr.redirect', $qr->unique_code)));
                @endphp
                <div class="prs-qr">
                    <img src="data:image/png;base64,{{ $qrData }}" alt="QR">
                </div>
            @endif
        @endif

        <div class="prs-footer">ساخته شده با <a href="/">کارت‌اکس</a></div>
    </div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
