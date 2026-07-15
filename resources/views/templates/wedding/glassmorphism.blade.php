@php
    $sections = $card->sections->where('is_visible', true)->sortBy('sort_order');
    $themeColor = $card->theme_color ?: '#667eea';
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
        body{font-family:'Vazirmatn',sans-serif;background:linear-gradient(135deg,#667eea 0%,#764ba2 50%,#f093fb 100%);color:#fff;min-height:100vh}
        .glm-header{text-align:center;padding:4rem 1.5rem 3rem;position:relative}
        .glm-header h2{font-size:.8rem;font-weight:400;letter-spacing:4px;text-transform:uppercase;margin-bottom:1rem;opacity:.8}
        .glm-header h1{font-size:2.5rem;font-weight:300;line-height:1.3}
        .glm-header .amp{font-size:1.8rem;margin:0 .5rem;font-weight:600}
        .glm-divider{width:80px;height:2px;background:rgba(255,255,255,.5);margin:1.5rem auto;border-radius:1px}
        .glm-date{font-size:1rem;font-weight:300;letter-spacing:1px;opacity:.9}
        .glm-countdown{display:flex;justify-content:center;gap:1rem;margin-top:2rem}
        .glm-countdown .cd-item{text-align:center;background:rgba(255,255,255,.1);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);padding:1rem;border-radius:1rem;min-width:65px;border:1px solid rgba(255,255,255,.2)}
        .glm-countdown .cd-num{font-size:1.8rem;font-weight:300;line-height:1}
        .glm-countdown .cd-label{font-size:.6rem;opacity:.7;margin-top:.5rem}
        .glm-container{max-width:500px;margin:0 auto;padding:0 1.5rem 2rem}
        .glm-section{background:rgba(255,255,255,.1);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.2);border-radius:1.5rem;padding:2rem;margin-bottom:1.5rem}
        .glm-section-title{text-align:center;font-size:.85rem;font-weight:500;margin-bottom:1.5rem;display:flex;align-items:center;justify-content:center;gap:.5rem}
        .glm-section-title::before{content:'✦';font-size:.9rem;opacity:.8}
        .glm-gallery{display:grid;grid-template-columns:repeat(2,1fr);gap:.75rem}
        .glm-gallery img{width:100%;aspect-ratio:1;object-fit:cover;border-radius:1rem;border:2px solid rgba(255,255,255,.2);transition:transform .3s}
        .glm-gallery img:hover{transform:scale(1.03)}
        .glm-timeline{position:relative}
        .glm-timeline-item{display:flex;gap:1rem;padding:1rem 0;border-bottom:1px solid rgba(255,255,255,.1)}
        .glm-timeline-item:last-child{border-bottom:none}
        .glm-timeline-item .tl-icon{width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.9rem}
        .glm-timeline-item .tl-title{font-weight:600;font-size:.88rem}
        .glm-timeline-item .tl-desc{font-size:.8rem;opacity:.7;margin-top:.25rem}
        .glm-social{display:flex;flex-wrap:wrap;justify-content:center;gap:.75rem}
        .glm-social a{display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1rem;background:rgba(255,255,255,.15);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.2);border-radius:2rem;font-size:.8rem;text-decoration:none;transition:all .2s;color:#fff}
        .glm-social a:hover{background:rgba(255,255,255,.3);transform:translateY(-2px)}
        .glm-map{border-radius:1rem;overflow:hidden;height:200px;border:2px solid rgba(255,255,255,.2)}
        .glm-footer{text-align:center;padding:2rem 1rem;opacity:.5;font-size:.75rem}
        .glm-footer a{color:#fff;text-decoration:none}
        .glm-contact{text-align:center}
        .glm-contact a,.glm-contact span{color:#fff;text-decoration:none;font-size:.88rem;display:block;margin-bottom:.75rem;opacity:.9}
        .glm-contact i{opacity:.7;margin-left:.5rem}
        .glm-qr{text-align:center;padding:1.5rem;background:rgba(255,255,255,.1);backdrop-filter:blur(20px);border-radius:1rem;border:1px solid rgba(255,255,255,.2)}
        .glm-qr img{max-width:100px}
        .glm-rsvp input,.glm-rsvp select{background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);color:#fff;padding:.7rem 1rem;width:100%;margin-bottom:1rem;font-size:.88rem;font-family:'Vazirmatn',sans-serif;border-radius:1rem}
        .glm-rsvp input::placeholder{color:rgba(255,255,255,.5)}
        .glm-rsvp button{background:rgba(255,255,255,.2);color:#fff;border:none;padding:.7rem 2rem;width:100%;font-size:.9rem;cursor:pointer;border-radius:1rem;backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.3);transition:all .2s}
        .glm-rsvp button:hover{background:rgba(255,255,255,.3)}
    </style>
</head>
<body>
    <div class="glm-header">
        <h2>دعوت نامه عروسی</h2>
        <h1>
            {{ $card->title }}
            @if($meta['partner_name'] ?? false)
                <span class="amp">&</span> {{ $meta['partner_name'] }}
            @endif
        </h1>
        <div class="glm-divider"></div>
        @if($meta['wedding_date'] ?? false)
            @php try { $wdCarbon = \Carbon\Carbon::parse($meta['wedding_date']); } catch (\Exception $e) { $wdCarbon = null; } @endphp
            @if($wdCarbon)
                <div class="glm-date">{{ \Morilog\Jalali\Jalalian::fromCarbon($wdCarbon)->format('l d F Y') }}</div>
            @endif
        @endif
        @if($meta['wedding_date'] ?? false)
            @php $wd = $wdCarbon ?? null; @endphp
            @if($wd)
            <div class="glm-countdown" id="countdown">
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
            <p style="margin-top:1.5rem;font-size:.9rem;opacity:.8;max-width:400px;margin-left:auto;margin-right:auto;line-height:1.8;font-style:italic">{{ $card->description }}</p>
        @endif
    </div>

    <div class="glm-container">
        @foreach($sections as $section)
            @if($section->type === 'contact')
                <div class="glm-section">
                    <div class="glm-section-title">{{ $section->title ?: 'تماس' }}</div>
                    <div class="glm-contact">
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
                <div class="glm-section">
                    <div class="glm-section-title">{{ $section->title ?: 'شبکه‌ها' }}</div>
                    <div class="glm-social">
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
                <div class="glm-section">
                    <div class="glm-section-title">{{ $section->title ?: 'گالری' }}</div>
                    <div class="glm-gallery">
                        @foreach($card->galleryItems->sortBy('sort_order') as $item)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->caption }}" loading="lazy">
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'timeline' && $section->items && $section->items->count())
                <div class="glm-section">
                    <div class="glm-section-title">{{ $section->title ?: 'داستان عشق' }}</div>
                    <div class="glm-timeline">
                        @foreach($section->items->sortBy('sort_order') as $item)
                            <div class="glm-timeline-item">
                                <div class="tl-icon">✦</div>
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
                <div class="glm-section">
                    <div class="glm-section-title">{{ $section->title ?: 'حضور' }}</div>
                    <div class="glm-rsvp">
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
                <div class="glm-section">
                    <div style="text-align:center;font-size:.9rem;line-height:2;opacity:.9">{!! nl2br(e($section->content)) !!}</div>
                </div>
            @endif
        @endforeach

        @if($card->map_lat && $card->map_lng)
            <div class="glm-section">
                <div class="glm-section-title">محل برگزاری</div>
                <div class="glm-map">
                    <iframe src="https://www.openstreetmap.org/export/embed.html?bbox={{ $card->map_lng - 0.01 }}%2C{{ $card->map_lat - 0.01 }}%2C{{ $card->map_lng + 0.01 }}%2C{{ $card->map_lat + 0.01 }}&layer=mapnik&marker={{ $card->map_lat }}%2C{{ $card->map_lng }}" width="100%" height="200" style="border:0" loading="lazy"></iframe>
                </div>
            </div>
        @endif

        @if($card->qrCodes->where('is_active', true)->count())
            @php $qr = $card->qrCodes->where('is_active', true)->first(); @endphp
            @if($qr)
                @php
                    $qrOpts = new \Chillerlan\QRCode\QROptions(['outputInterface' => \Chillerlan\QRCode\Output\QRGdImagePNG::class, 'eccLevel' => \Chillerlan\QRCode\QRCode::ECC_M, 'scale' => 5, 'imageBase64' => false, 'bgColor' => '#667eea', 'moduleValues' => [\Chillerlan\QRCode\Data\QRMatrix::M_DATA_DARK => '#ffffff', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DARK => '#ffffff', \Chillerlan\QRCode\Data\QRMatrix::M_FINDER_DOT => '#ffffff', \Chillerlan\QRCode\Data\QRMatrix::M_ALIGNMENT_DARK => '#ffffff']]);
                    $qrGen = new \Chillerlan\QRCode\QRCode($qrOpts);
                    $qrData = base64_encode($qrGen->render(route('qr.redirect', $qr->unique_code)));
                @endphp
                <div class="glm-qr">
                    <img src="data:image/png;base64,{{ $qrData }}" alt="QR">
                </div>
            @endif
        @endif

        <div class="glm-footer">ساخته شده با <a href="/">کارت‌اکس</a></div>
    </div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
