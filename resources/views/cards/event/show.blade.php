@php
    $sections = $card->sections->where('is_visible', true)->sortBy('sort_order');
    $themeColor = $card->theme_color ?: '#7c3aed';
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
        body{font-family:'Vazirmatn',sans-serif;background:#0f0f0f;color:#f5f5f5}
        .evt-banner{position:relative;height:280px;overflow:hidden}
        .evt-banner img{width:100%;height:100%;object-fit:cover}
        .evt-banner .evt-overlay{position:absolute;inset:0;background:linear-gradient(transparent 30%,rgba(0,0,0,.9))}
        .evt-banner .evt-badge{position:absolute;top:1rem;right:1rem;background:{{ $themeColor }};color:#fff;padding:.35rem .85rem;border-radius:2rem;font-size:.78rem;font-weight:600}
        .evt-banner .evt-content{position:absolute;bottom:0;left:0;right:0;padding:1.5rem}
        .evt-banner h1{font-size:1.6rem;font-weight:700}
        .evt-banner .evt-sub{color:rgba(255,255,255,.7);font-size:.88rem;margin-top:.25rem}
        .evt-container{max-width:560px;margin:0 auto;padding:0 1rem 2rem}
        .evt-countdown{display:flex;justify-content:center;gap:.75rem;margin:-1.5rem auto 1.5rem;position:relative;z-index:1}
        .evt-countdown .cd-box{text-align:center;background:{{ $themeColor }};border-radius:.75rem;padding:.6rem 1rem;min-width:65px;box-shadow:0 4px 20px {{ $themeColor }}40}
        .evt-countdown .cd-num{font-size:1.5rem;font-weight:700;line-height:1}
        .evt-countdown .cd-label{font-size:.6rem;opacity:.8}
        .evt-card{background:#1a1a1a;border-radius:1rem;padding:1.25rem;margin-bottom:1rem;border:1px solid #2a2a2a}
        .evt-card-title{font-size:.95rem;font-weight:700;color:{{ $themeColor }};margin-bottom:1rem;display:flex;align-items:center;gap:.5rem}
        .evt-speaker{display:flex;align-items:center;gap:.75rem;padding:.6rem 0;border-bottom:1px solid #2a2a2a}
        .evt-speaker:last-child{border-bottom:none}
        .evt-speaker img{width:50px;height:50px;border-radius:50%;object-fit:cover;border:2px solid {{ $themeColor }}40}
        .evt-speaker .sp-name{font-weight:600;font-size:.9rem}
        .evt-speaker .sp-role{font-size:.78rem;color:#888}
        .evt-schedule{display:flex;align-items:flex-start;gap:.75rem;padding:.6rem 0;border-bottom:1px solid #2a2a2a}
        .evt-schedule:last-child{border-bottom:none}
        .evt-schedule .sch-time{color:{{ $themeColor }};font-weight:700;font-size:.85rem;white-space:nowrap;min-width:65px}
        .evt-schedule .sch-title{font-weight:600;font-size:.88rem}
        .evt-schedule .sch-desc{font-size:.78rem;color:#888;margin-top:.15rem}
        .evt-social{display:flex;flex-wrap:wrap;gap:.5rem;justify-content:center}
        .evt-social a{display:inline-flex;align-items:center;gap:.35rem;padding:.4rem .85rem;border-radius:2rem;color:#fff;font-size:.78rem;text-decoration:none;transition:transform .2s}
        .evt-social a:hover{transform:translateY(-2px);color:#fff}
        .evt-register-btn{display:block;text-align:center;padding:.85rem;border-radius:.75rem;background:{{ $themeColor }};color:#fff;text-decoration:none;font-weight:600;font-size:1rem;transition:all .2s;box-shadow:0 4px 20px {{ $themeColor }}40}
        .evt-register-btn:hover{opacity:.9;color:#fff}
        .evt-contact-row{display:flex;align-items:center;gap:.75rem;padding:.6rem 0;border-bottom:1px solid #2a2a2a}
        .evt-contact-row:last-child{border-bottom:none}
        .evt-contact-row i{color:{{ $themeColor }};width:20px;text-align:center}
        .evt-contact-row a,.evt-contact-row span{color:#ccc;text-decoration:none;font-size:.88rem}
        .evt-map{border-radius:1rem;overflow:hidden;height:180px}
        .evt-gallery{display:grid;grid-template-columns:repeat(3,1fr);gap:.5rem}
        .evt-gallery img{width:100%;aspect-ratio:1;object-fit:cover;border-radius:.75rem}
        .evt-footer{text-align:center;padding:2rem 1rem;color:#555;font-size:.78rem}
        .evt-footer a{color:{{ $themeColor }};text-decoration:none}
    </style>
</head>
<body>
    <div class="evt-banner">
        @if($card->cover_image)
            <img src="{{ asset('storage/' . $card->cover_image) }}" alt="{{ $card->title }}">
        @else
            <div style="width:100%;height:100%;background:linear-gradient(135deg,{{ $themeColor }}40,#0f0f0f)"></div>
        @endif
        <div class="evt-overlay"></div>
        <div class="evt-badge"><i class="bi bi-calendar-event ms-1"></i> {{ $card->description ?: 'رویداد' }}</div>
        <div class="evt-content">
            @if($card->logo)
                <img src="{{ asset('storage/' . $card->logo) }}" alt="لوگو" style="max-height:35px;margin-bottom:.5rem">
            @endif
            <h1>{{ $card->title }}</h1>
            @if($meta['event_date'] ?? false)
                @php
                    try { $edCarbon = \Carbon\Carbon::parse($meta['event_date']); } catch (\Exception $e) { $edCarbon = null; }
                @endphp
                @if($edCarbon)
                <div class="evt-sub"><i class="bi bi-clock ms-1"></i> {{ \Morilog\Jalali\Jalalian::fromCarbon($edCarbon)->format('Y/m/d H:i') }}</div>
                @endif
            @endif
        </div>
    </div>

    @if($meta['event_date'] ?? false)
        @php $ed = $edCarbon ?? null; @endphp
        @if($ed)
        <div class="evt-countdown" id="evtCountdown">
            <div class="cd-box"><div class="cd-num" id="ecd-days">--</div><div class="cd-label">روز</div></div>
            <div class="cd-box"><div class="cd-num" id="ecd-hours">--</div><div class="cd-label">ساعت</div></div>
            <div class="cd-box"><div class="cd-num" id="ecd-mins">--</div><div class="cd-label">دقیقه</div></div>
            <div class="cd-box"><div class="cd-num" id="ecd-secs">--</div><div class="cd-label">ثانیه</div></div>
        </div>
        @push('scripts')
        <script>
        (function(){
            var t=new Date('{{ $ed->toIso8601String() }}').getTime();
            function u(){var n=Date.now(),d=t-n;if(d<=0)return;document.getElementById('ecd-days').textContent=Math.floor(d/864e5);document.getElementById('ecd-hours').textContent=Math.floor(d%864e5/36e5);document.getElementById('ecd-mins').textContent=Math.floor(d%36e5/6e4);document.getElementById('ecd-secs').textContent=Math.floor(d%6e4/1e3)}
            u();setInterval(u,1000);
        })();
        </script>
        @endpush
    @endif
    @endif

    <div class="evt-container">
        @if($meta['registration_url'] ?? false)
            <a href="{{ $meta['registration_url'] }}" class="evt-register-btn" target="_blank"><i class="bi bi-ticket-perforated ms-1"></i> ثبت‌نام در رویداد</a>
        @endif

        @foreach($sections as $section)
            @if($section->type === 'services' && $card->services->count())
                <div class="evt-card">
                    <div class="evt-card-title"><i class="bi bi-mic"></i> {{ $section->title ?: 'سخنرانان' }}</div>
                    @foreach($card->services->sortBy('sort_order') as $service)
                        <div class="evt-speaker">
                            @if($service->icon)
                                <i class="bi bi-{{ $service->icon }}" style="font-size:1.5rem;color:{{ $themeColor }}"></i>
                            @endif
                            <div>
                                <div class="sp-name">{{ $service->name }}</div>
                                <div class="sp-role">{{ $service->description }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($section->type === 'products' && $card->products->count())
                <div class="evt-card">
                    <div class="evt-card-title"><i class="bi bi-clock-history"></i> {{ $section->title ?: 'برنامه زمانی' }}</div>
                    @foreach($card->products->sortBy('sort_order') as $product)
                        <div class="evt-schedule">
                            <div class="sch-time">{{ $product->name }}</div>
                            <div>
                                <div class="sch-title">{{ $product->description }}</div>
                                @if($product->price)
                                    <div class="sch-desc">{{ number_format($product->price) }} تومان</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($section->type === 'gallery' && $card->galleryItems->count())
                <div class="evt-card">
                    <div class="evt-card-title"><i class="bi bi-images"></i> {{ $section->title ?: 'گالری' }}</div>
                    <div class="evt-gallery">
                        @foreach($card->galleryItems->sortBy('sort_order') as $item)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="" loading="lazy">
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'contact')
                <div class="evt-card">
                    <div class="evt-card-title"><i class="bi bi-info-circle"></i> {{ $section->title ?: 'اطلاعات' }}</div>
                    @if($card->phone)
                        <div class="evt-contact-row"><i class="bi bi-telephone"></i><a href="tel:{{ $card->phone }}">{{ $card->phone }}</a></div>
                    @endif
                    @if($card->email)
                        <div class="evt-contact-row"><i class="bi bi-envelope"></i><a href="mailto:{{ $card->email }}">{{ $card->email }}</a></div>
                    @endif
                    @if($card->address)
                        <div class="evt-contact-row"><i class="bi bi-geo-alt"></i><span>{{ $card->address }}</span></div>
                    @endif
                </div>
            @endif

            @if($section->type === 'social' && $card->socialLinks->count())
                <div class="evt-card" style="text-align:center">
                    <div class="evt-card-title" style="justify-content:center"><i class="bi bi-share"></i> {{ $section->title ?: 'شبکه‌ها' }}</div>
                    <div class="evt-social">
                        @foreach($card->socialLinks->sortBy('sort_order') as $link)
                            @php
                                $sC = ['instagram'=>'#E4405F','telegram'=>'#0088CC','twitter'=>'#1DA1F2','linkedin'=>'#0A66C2'];
                                $sI = ['instagram'=>'instagram','telegram'=>'telegram','twitter'=>'twitter-x','linkedin'=>'linkedin'];
                            @endphp
                            <a href="{{ $link->url }}" style="background:{{ $sC[$link->platform] ?? '#6c757d' }}" target="_blank">
                                <i class="bi bi-{{ $sI[$link->platform] ?? 'globe' }}"></i> {{ ucfirst($link->platform) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($section->type === 'map' && $card->map_lat && $card->map_lng)
                <div class="evt-card" style="padding:0;overflow:hidden">
                    <div class="evt-map">
                        <iframe src="https://www.openstreetmap.org/export/embed.html?bbox={{ $card->map_lng - 0.01 }}%2C{{ $card->map_lat - 0.01 }}%2C{{ $card->map_lng + 0.01 }}%2C{{ $card->map_lat + 0.01 }}&layer=mapnik&marker={{ $card->map_lat }}%2C{{ $card->map_lng }}" width="100%" height="180" style="border:0" loading="lazy"></iframe>
                    </div>
                </div>
            @endif

            @if($section->type === 'custom' && $section->content)
                <div class="evt-card">
                    <div style="font-size:.88rem;line-height:1.8;color:#ccc">{!! nl2br(e($section->content)) !!}</div>
                </div>
            @endif
        @endforeach

        @if($card->qrCodes->where('is_active', true)->count())
            @php $qr = $card->qrCodes->where('is_active', true)->first(); @endphp
            @if($qr)
                @php
                    $qrOpts = new \Chillerlan\QRCode\QROptions(['outputInterface' => \Chillerlan\QRCode\Output\QRGdImagePNG::class, 'eccLevel' => \Chillerlan\QRCode\QRCode::ECC_M, 'scale' => 5, 'imageBase64' => false, 'bgColor' => '#1a1a1a']);
                    $qrGen = new \Chillerlan\QRCode\QRCode($qrOpts);
                    $qrData = base64_encode($qrGen->render(route('qr.redirect', $qr->unique_code)));
                @endphp
                <div class="evt-card" style="text-align:center">
                    <img src="data:image/png;base64,{{ $qrData }}" alt="QR" style="max-width:120px;border-radius:.5rem">
                </div>
            @endif
        @endif

        <div class="evt-footer">ساخته شده با <a href="/">کارت‌اکس</a></div>
    </div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
