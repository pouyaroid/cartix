<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ویرایشگر: {{ $page->title }} | CardX</title>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {!! $fontLinks !!}
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Vazirmatn',system-ui,sans-serif;overflow:hidden;height:100vh;background:#f8f9fa;color:#1f2937}
        .builder-layout{display:flex;height:100vh}
        .builder-left{width:260px;background:#fff;border-left:1px solid #e5e7eb;display:flex;flex-direction:column;flex-shrink:0}
        .panel-header{padding:14px 16px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;justify-content:space-between}
        .panel-header h6{font-size:13px;font-weight:600;color:#111827}
        .panel-header a{color:#9ca3af;text-decoration:none}
        .panel-header a:hover{color:#374151}
        .widgets-scroll{flex:1;overflow-y:auto;padding:8px}
        .widget-category{margin-bottom:6px}
        .widget-category-title{font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;padding:8px 8px 4px;display:flex;align-items:center;gap:6px}
        .widget-grid{display:grid;grid-template-columns:1fr 1fr;gap:4px}
        .widget-chip{display:flex;align-items:center;gap:6px;padding:7px 10px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;cursor:grab;transition:all .15s;font-size:11px;color:#374151;user-select:none}
        .widget-chip:hover{border-color:#6366f1;background:#eef2ff;color:#4f46e5;transform:translateY(-1px);box-shadow:0 2px 8px rgba(99,102,241,.1)}
        .widget-chip:active{cursor:grabbing;transform:scale(.97)}
        .widget-chip i{font-size:13px;color:#6366f1}
        .widget-chip span{white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .builder-center{flex:1;display:flex;flex-direction:column;overflow:hidden}
        .builder-toolbar{height:44px;background:#fff;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;justify-content:space-between;padding:0 16px;flex-shrink:0}
        .toolbar-left,.toolbar-right{display:flex;align-items:center;gap:8px}
        .toolbar-title{font-size:13px;font-weight:600;color:#111827}
        .toolbar-badge{font-size:10px;padding:2px 8px;border-radius:20px;font-weight:500}
        .toolbar-badge.published{background:#dcfce7;color:#16a34a}
        .toolbar-badge.draft{background:#f3f4f6;color:#6b7280}
        .toolbar-btn{width:32px;height:32px;display:flex;align-items:center;justify-content:center;background:#fff;border:1px solid #e5e7eb;border-radius:6px;color:#6b7280;cursor:pointer;transition:all .15s;font-size:14px;text-decoration:none}
        .toolbar-btn:hover{background:#f3f4f6;color:#111827;border-color:#d1d5db}
        .toolbar-btn.active{background:#eef2ff;color:#4f46e5;border-color:#c7d2fe}
        .toolbar-btn.primary{background:#4f46e5;color:#fff;border-color:#4f46e5}
        .toolbar-btn.primary:hover{background:#4338ca}
        .toolbar-divider{width:1px;height:20px;background:#e5e7eb;margin:0 4px}
        .save-indicator{display:flex;align-items:center;gap:6px;font-size:11px;color:#6b7280}
        .save-indicator .dot{width:6px;height:6px;border-radius:50%;background:#22c55e}
        .builder-canvas{flex:1;overflow:auto;background:#f3f4f6;display:flex;justify-content:center;padding:32px}
        .canvas-frame{width:100%;max-width:100%;background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.08),0 4px 16px rgba(0,0,0,.04);min-height:600px;transition:max-width .3s;overflow:hidden}
        .canvas-frame.tablet{max-width:768px}
        .canvas-frame.mobile{max-width:390px}
        .builder-right{width:300px;background:#fff;border-right:1px solid #e5e7eb;display:flex;flex-direction:column;flex-shrink:0}
        .panel-tabs{display:flex;border-bottom:1px solid #e5e7eb}
        .panel-tab{flex:1;padding:10px;text-align:center;font-size:11px;font-weight:500;color:#9ca3af;cursor:pointer;transition:all .15s;border-bottom:2px solid transparent;background:none;border-top:none;border-left:none;border-right:none}
        .panel-tab:hover{color:#374151}
        .panel-tab.active{color:#4f46e5;border-bottom-color:#4f46e5}
        .panel-content{flex:1;overflow-y:auto;padding:16px}
        .panel-empty{text-align:center;padding:48px 16px;color:#9ca3af}
        .panel-empty i{font-size:32px;margin-bottom:12px;display:block}
        .panel-empty p{font-size:12px}
        .style-section{margin-bottom:16px}
        .style-section-title{font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:8px;padding-bottom:4px;border-bottom:1px solid #f3f4f6}
        .style-field{margin-bottom:10px}
        .style-field label{display:block;font-size:11px;font-weight:500;color:#6b7280;margin-bottom:3px}
        .style-input{width:100%;padding:7px 10px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;color:#111827;font-size:12px;font-family:inherit;transition:all .15s;outline:none}
        .style-input:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.1)}
        .style-input::placeholder{color:#9ca3af}
        .style-select{width:100%;padding:7px 10px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;color:#111827;font-size:12px;font-family:inherit;cursor:pointer;outline:none}
        .style-select:focus{border-color:#6366f1}
        .color-field{display:flex;gap:8px;align-items:center}
        .color-picker{width:34px;height:34px;padding:2px;background:#fff;border:1px solid #e5e7eb;border-radius:6px;cursor:pointer;flex-shrink:0}
        /* Alignment Buttons */
        .align-buttons{display:flex;gap:4px}
        .align-btn{flex:1;padding:8px;display:flex;align-items:center;justify-content:center;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;cursor:pointer;transition:all .15s;color:#6b7280;font-size:16px}
        .align-btn:hover{background:#f3f4f6;color:#374151;border-color:#d1d5db}
        .align-btn.active{background:#eef2ff;color:#4f46e5;border-color:#c7d2fe}
        /* Block Wrappers */
        .block-wrapper{position:relative;border:2px solid transparent;transition:border-color .15s,box-shadow .15s;min-height:20px;border-radius:4px}
        .block-wrapper:hover{border-color:rgba(99,102,241,.35)}
        .block-wrapper.selected{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.1)}
        .block-toolbar{position:absolute;top:-36px;left:50%;transform:translateX(-50%);display:none;gap:2px;z-index:50;background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:4px;box-shadow:0 2px 8px rgba(0,0,0,.08)}
        .block-wrapper:hover .block-toolbar,.block-wrapper.selected .block-toolbar{display:flex}
        .block-toolbar .tool-btn{width:28px;height:28px;display:flex;align-items:center;justify-content:center;background:transparent;border:none;border-radius:4px;color:#6b7280;cursor:pointer;transition:all .15s;font-size:12px}
        .block-toolbar .tool-btn:hover{background:#f3f4f6;color:#111827}
        .block-toolbar .tool-btn.danger:hover{background:#fef2f2;color:#dc2626}
        .block-type-badge{position:absolute;top:-8px;right:8px;font-size:9px;padding:1px 6px;border-radius:3px;background:#6366f1;color:#fff;font-weight:500;opacity:0;transition:opacity .15s;pointer-events:none}
        .block-wrapper:hover .block-type-badge,.block-wrapper.selected .block-type-badge{opacity:1}
        .drop-zone{border:2px dashed #c7d2fe;background:#eef2ff;border-radius:12px;padding:80px 40px;text-align:center;color:#6366f1;transition:all .2s}
        .drop-zone.drag-over{background:#e0e7ff;border-color:#6366f1}
        .drop-zone i{font-size:40px;opacity:.4;margin-bottom:12px;display:block}
        .drop-zone h6{font-size:14px;font-weight:600;margin-bottom:4px}
        .drop-zone p{font-size:12px;color:#9ca3af}
        .layer-item{display:flex;align-items:center;gap:8px;padding:7px 10px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;margin-bottom:4px;cursor:pointer;transition:all .15s;font-size:11px;color:#374151}
        .layer-item:hover{background:#f3f4f6}
        .layer-item.active{background:#eef2ff;border-color:#c7d2fe;color:#4f46e5}
        .layer-item .layer-icon{width:20px;height:20px;display:flex;align-items:center;justify-content:center;background:#e5e7eb;border-radius:4px;font-size:10px}
        .layer-item.active .layer-icon{background:#c7d2fe}
        .spinner{display:inline-block;width:14px;height:14px;border:2px solid #e5e7eb;border-top-color:#6366f1;border-radius:50%;animation:spin .6s linear infinite}
        @keyframes spin{to{transform:rotate(360deg)}}
    </style>
</head>
<body>
<div class="builder-layout">
    <div class="builder-left">
        <div class="panel-header"><h6>ابزارها</h6><a href="{{ route('dashboard.landing-pages.index') }}"><i class="bi bi-x-lg"></i></a></div>
        <div class="widgets-scroll">
            @foreach($categories as $catKey => $cat)
            <div class="widget-category">
                <div class="widget-category-title"><i class="bi {{ $cat['icon'] }}"></i> {{ $cat['label'] }}</div>
                <div class="widget-grid">
                    @foreach($widgets->get($catKey, collect()) as $widget)
                    <div class="widget-chip" draggable="true" data-component="{{ $widget->component }}" data-type="{{ in_array($widget->component, ['layout-section','layout-column','layout-container']) ? 'layout' : 'widget' }}"><i class="bi {{ $widget->icon }}"></i><span>{{ $widget->name }}</span></div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="builder-center">
        <div class="builder-toolbar">
            <div class="toolbar-left">
                <span class="toolbar-title">{{ $page->title }}</span>
                <span class="toolbar-badge {{ $page->isPublished() ? 'published' : 'draft' }}">{{ $page->isPublished() ? 'منتشر' : 'پیش‌نویس' }}</span>
                <div class="save-indicator" id="saveIndicator" style="display:none"><span class="dot"></span> ذخیره</div>
            </div>
            <div class="toolbar-right">
                <div style="display:flex;gap:4px">
                    <button class="toolbar-btn resp-btn active" data-mode="desktop"><i class="bi bi-display"></i></button>
                    <button class="toolbar-btn resp-btn" data-mode="tablet"><i class="bi bi-tablet"></i></button>
                    <button class="toolbar-btn resp-btn" data-mode="mobile"><i class="bi bi-phone"></i></button>
                </div>
                <div class="toolbar-divider"></div>
                <a href="{{ route('dashboard.landing-pages.preview', $page) }}" class="toolbar-btn" target="_blank"><i class="bi bi-eye"></i></a>
                <a href="{{ route('dashboard.landing-pages.edit', $page) }}" class="toolbar-btn"><i class="bi bi-gear"></i></a>
                <form action="{{ route('dashboard.landing-pages.publish', $page) }}" method="POST" class="d-inline">@csrf<button type="submit" class="toolbar-btn {{ $page->isPublished() ? '' : 'primary' }}"><i class="bi bi-{{ $page->isPublished() ? 'eye-slash' : 'send' }}"></i></button></form>
            </div>
        </div>
        <div class="builder-canvas" id="builderCanvas">
            <div class="canvas-frame" id="canvasFrame">
                <div id="blocksContainer" style="min-height:600px;padding:0;">
                    <div class="drop-zone"><i class="bi bi-plus-circle"></i><h6>ابزاری را اینجا رها کنید</h6><p>یک ویجت را از پنل سمت راست بکشید</p></div>
                </div>
            </div>
        </div>
    </div>
    <div class="builder-right">
        <div class="panel-tabs">
            <button class="panel-tab active" data-tab="properties">محتوا</button>
            <button class="panel-tab" data-tab="style">استایل</button>
            <button class="panel-tab" data-tab="layers">لایه‌ها</button>
        </div>
        <div class="panel-content" id="propertiesPanel"><div id="noSelection" class="panel-empty"><i class="bi bi-cursor"></i><p>یک بلوک انتخاب کنید</p></div><div id="propsContent" style="display:none"></div></div>
        <div class="panel-content" id="stylePanel" style="display:none"><div id="styleContent"></div></div>
        <div class="panel-content" id="layersPanel" style="display:none"><div id="layersContent"></div></div>
    </div>
</div>
<style id="hoverStyles"></style>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
(function(){
'use strict';
const PAGE_ID={{ $page->id }};
const CSRF=document.querySelector('meta[name="csrf-token"]').content;
const H={'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'};
const FONT_OPTIONS=@json($fontOptions);
let selectedBlockId=null,sortableInstance=null,blocksCache={};

// ── Font Loader ──
const loadedFontLinks={};
function loadFont(fontName){
    if(loadedFontLinks[fontName])return;
    const font=FONT_OPTIONS.find(f=>f.value===fontName);
    if(!font)return;
    // Find the font config from PHP
    const allFonts=@json(\App\Config\PersianFonts::getAllFonts());
    const config=allFonts[fontName];
    if(!config)return;
    if(config.source==='cdn'&&config.url){
        const link=document.createElement('link');link.rel='stylesheet';link.href=config.url;document.head.appendChild(link);loadedFontLinks[fontName]=true;
    }else if(config.source==='google'){
        const weights=config.weights||[400];const url=`https://fonts.googleapis.com/css2?family=${fontName.replace(/ /g,'+')}:wght@${weights.join(';')}&display=swap`;
        const link=document.createElement('link');link.rel='stylesheet';link.href=url;document.head.appendChild(link);loadedFontLinks[fontName]=true;
    }
}
let selectedBlockId=null,sortableInstance=null,blocksCache={};

async function api(u,m,d){const o={method:m,headers:H};if(d)o.body=JSON.stringify(d);const r=await fetch(u,o);if(!r.ok){const e=await r.json().catch(()=>({}));throw new Error(e.message||e.error||'Failed');}return r.json();}
function esc(s){const d=document.createElement('div');d.textContent=s;return d.innerHTML;}
function css(o){if(!o||typeof o!=='object')return'';return Object.entries(o).filter(([,v])=>v!==''&&v!=null).map(([k,v])=>`${k}:${v}`).join(';');}

function updateHoverStyles(){const el=document.getElementById('hoverStyles');let c='';for(const[id,b]of Object.entries(blocksCache)){const hv=b.styles?.hover||{};if(Object.keys(hv).length){const sel=b.component==='widget-button'?`.block-wrapper[data-block-id="${id}"] .lp-btn`:`.block-wrapper[data-block-id="${id}"]`;c+=`${sel}:hover{${css(hv)}}\n`;}}el.textContent=c;}

// ── WRAPPER STYLE KEYS ──
const WRAPPER_KEYS=['background-color','background','background-image','background-size','background-position','background-repeat','padding','margin','width','max-width','min-width','height','max-height','min-height','border','border-radius','box-shadow','text-align','color','font-size','font-weight','font-family','line-height','letter-spacing','text-transform','text-decoration','opacity','display','flex-direction','flex-wrap','justify-content','align-items','align-self','gap','overflow','position','z-index','transform','transition'];

async function renderBlocks(){
    const ct=document.getElementById('blocksContainer');
    try{const r=await fetch(`/api/landing-pages/${PAGE_ID}/blocks/tree`,{headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF}});if(!r.ok)throw new Error('Failed');const d=await r.json();const blocks=d.blocks||[];blocksCache={};blocks.forEach(b=>{blocksCache[b.id]=b;});ct.innerHTML=blocks.length?blocks.map(b=>renderBlockHTML(b)).join(''):`<div class="drop-zone"><i class="bi bi-plus-circle"></i><h6>ابزاری را اینجا رها کنید</h6><p>یک ویجت را از پنل سمت راست بکشید</p></div>`;initBlockClicks();initSortable();updateLayers();updateHoverStyles();}catch(e){console.error(e);}
}

function renderBlockHTML(block){
    const c=block.content||{};const ds=(block.styles||{}).desktop||{};const bs=(block.styles||{}).button||{};
    const actions=`<div class="block-toolbar"><span class="block-type-badge">${block.component}</span><button class="tool-btn drag-handle"><i class="bi bi-grip-vertical"></i></button><button class="tool-btn" onclick="event.stopPropagation();LP.duplicate(${block.id})"><i class="bi bi-copy"></i></button><button class="tool-btn danger" onclick="event.stopPropagation();LP.delete(${block.id})"><i class="bi bi-trash3"></i></button></div>`;
    let inner='';const comp=block.component;

    // ── HEADING ──
    if(comp==='widget-heading'){const tag=c.tag||'h2';const sz=({h1:'48px',h2:'36px',h3:'28px',h4:'22px',h5:'18px',h6:'16px'})[tag]||'36px';inner=`<${tag} style="font-size:${sz};font-weight:700;line-height:1.2;color:#111827;margin:0">${esc(c.text||'عنوان')}</${tag}>`;}
    // ── TEXT ──
    else if(comp==='widget-text'){inner=`<p style="font-size:16px;line-height:1.7;color:#4b5563;margin:0">${esc(c.text||'متن خود را اینجا بنویسید.')}</p>`;}
    // ── BUTTON ──
    else if(comp==='widget-button'){
        const btnCSS={'background-color':bs['background-color']||'#4f46e5','color':bs['color']||'#ffffff','border':bs['border']||'none','border-radius':bs['border-radius']||'10px','padding':bs['padding']||'12px 28px','font-size':bs['font-size']||'15px','font-weight':bs['font-weight']||'600','box-shadow':bs['box-shadow']||'0 2px 8px rgba(79,70,229,.25)','transition':bs['transition']||'all .2s','cursor':bs['cursor']||'pointer','display':bs['display']||'inline-flex','align-items':bs['align-items']||'center','gap':bs['gap']||'8px','text-decoration':'none','width':bs['width']||'','min-width':bs['min-width']||'','height':bs['height']||'','opacity':bs['opacity']||'','text-transform':bs['text-transform']||'','letter-spacing':bs['letter-spacing']||''};
        if(bs['gradient']){btnCSS['background']=bs['gradient'];delete btnCSS['background-color'];}
        Object.keys(btnCSS).forEach(k=>{if(!btnCSS[k])delete btnCSS[k];});
        inner=`<div><a href="${esc(c.link||'#')}" class="lp-btn" style="${css(btnCSS)}">${esc(c.text||'کلیک کنید')}</a></div>`;
    }
    // ── IMAGE ──
    else if(comp==='widget-image'){inner=c.src?`<div style="border-radius:${ds['border-radius']||'12px'};overflow:hidden"><img src="${esc(c.src)}" alt="${esc(c.alt||'')}" style="width:100%;display:block"></div>`:`<div style="padding:40px;background:#f3f4f6;border-radius:12px;text-align:center"><i class="bi bi-image" style="font-size:28px;color:#d1d5db"></i></div>`;}
    // ── DIVIDER ──
    else if(comp==='widget-divider'){inner=`<hr style="border:none;border-top:1px solid ${c.color||'#e5e7eb'};margin:0">`;}
    // ── SPACER ──
    else if(comp==='widget-spacer'){inner=`<div style="height:${c.height||'48px'}"></div>`;}
    // ── ICON ──
    else if(comp==='widget-icon'){inner=`<div style="width:56px;height:56px;display:inline-flex;align-items:center;justify-content:center;background:#eef2ff;border-radius:14px"><i class="bi ${c.name||'bi-star'}" style="font-size:${c.size||'24px'};color:${c.color||'#4f46e5'}"></i></div>`;}
    // ── LOGO ──
    else if(comp==='widget-logo'){inner=c.src?`<img src="${esc(c.src)}" style="height:${c.height||'40px'};object-fit:contain">`:`<div style="font-size:22px;font-weight:800;color:#4f46e5">LOGO</div>`;}
    // ── SOCIAL ──
    else if(comp==='widget-social'){
        const iconMap={instagram:'bi-instagram',telegram:'bi-telegram',whatsapp:'bi-whatsapp',twitter:'bi-twitter-x',linkedin:'bi-linkedin',youtube:'bi-youtube',facebook:'bi-facebook'};
        inner=`<div style="display:flex;gap:10px;flex-wrap:wrap">${(c.links||[]).map(l=>`<a href="${esc(l.url||'#')}" style="width:40px;height:40px;display:inline-flex;align-items:center;justify-content:center;background:#f3f4f6;border-radius:10px;color:#6b7280;text-decoration:none;transition:all .2s" onmouseover="this.style.background='#4f46e5';this.style.color='#fff'" onmouseout="this.style.background='#f3f4f6';this.style.color='#6b7280'"><i class="bi ${iconMap[l.platform]||'bi-link'}"></i></a>`).join('')}</div>`;
    }
    // ── MAP ──
    else if(comp==='widget-map'){inner=`<div style="height:${c.height||'300px'};background:#e0e7ff;border-radius:12px"><i class="bi bi-geo-alt" style="font-size:36px;color:#6366f1"></i></div>`;}
    // ── STATS ──
    else if(comp==='widget-stats'){inner=`<div style="display:flex;gap:24px;flex-wrap:wrap">${(c.items||[]).map(i=>`<div style="flex:1;min-width:120px;text-align:center;padding:16px"><div style="font-size:36px;font-weight:800;color:#4f46e5;line-height:1">${esc(i.value||'0')}</div><div style="font-size:13px;color:#6b7280;margin-top:6px">${esc(i.label||'')}</div></div>`).join('')}</div>`;}
    // ── LIST ──
    else if(comp==='content-list'){inner=`<ul style="list-style:none;padding:0;margin:0">${(c.items||[]).map(i=>`<li style="padding:8px 0;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;gap:10px"><span style="width:6px;height:6px;background:#4f46e5;border-radius:50%;flex-shrink:0"></span><span style="font-size:15px;color:#374151">${esc(i)}</span></li>`).join('')}</ul>`;}
    // ── COUNTER ──
    else if(comp==='content-counter'){inner=`<div style="padding:12px 0"><div style="font-size:48px;font-weight:800;color:#4f46e5;line-height:1">${esc(c.value||'0')}${esc(c.suffix||'')}</div><div style="font-size:14px;color:#6b7280;margin-top:6px">${esc(c.label||'')}</div></div>`;}
    // ── PROGRESS ──
    else if(comp==='content-progress'){inner=`<div style="margin-bottom:6px;display:flex;justify-content:space-between"><span style="font-size:13px;color:#374151;font-weight:500">${esc(c.label||'')}</span><span style="font-size:13px;color:#9ca3af">${c.value||0}%</span></div><div style="height:8px;background:#f3f4f6;border-radius:100px;overflow:hidden"><div style="height:100%;width:${c.value||0}%;background:linear-gradient(90deg,#4f46e5,#7c3aed);border-radius:100px"></div></div>`;}
    // ── COUNTDOWN ──
    else if(comp==='advanced-countdown'){inner=`<div style="padding:16px 0"><div style="font-size:40px;font-weight:800;color:#111827;font-variant-numeric:tabular-nums">--:--:--:--</div><div style="font-size:13px;color:#6b7280;margin-top:6px">${esc(c.label||'')}</div></div>`;}
    // ── QR ──
    else if(comp==='advanced-qrcode'){inner=`<div style="padding:20px 0"><div style="width:${c.size||180}px;height:${c.size||180}px;background:#fff;border:2px solid #e5e7eb;border-radius:12px;display:inline-flex;align-items:center;justify-content:center"><i class="bi bi-qr-code" style="font-size:40px;color:#d1d5db"></i></div></div>`;}
    // ── GRADIENT ──
    else if(comp==='deco-gradient'){inner=`<div style="height:${c.height||'200px'};background:linear-gradient(${c.direction||'135deg'},${c.from||'#4f46e5'},${c.to||'#7c3aed'})"></div>`;}
    // ── SHAPE ──
    else if(comp==='deco-shape'){inner=`<div style="height:${c.height||'80px'};overflow:hidden;line-height:0"><svg viewBox="0 0 1440 100" preserveAspectRatio="none" style="width:100%;height:100%"><path fill="${c.color||'#ffffff'}" d="M0,50 C480,100 960,0 1440,50 L1440,100 L0,100 Z"></path></svg></div>`;}
    else{inner=`<div style="padding:24px;background:#f9fafb;border-radius:10px;text-align:center;border:1px dashed #d1d5db"><i class="bi bi-puzzle" style="font-size:20px;color:#d1d5db"></i><p style="font-size:11px;color:#9ca3af;margin-top:6px">${esc(comp)}</p></div>`;}

    const ws={};
    if(comp!=='widget-button'){WRAPPER_KEYS.forEach(k=>{if(ds[k])ws[k]=ds[k];});}
    return `<div class="block-wrapper" data-block-id="${block.id}" data-component="${comp}" ${css(ws)?`style="${css(ws)}"`:''}>${actions}${inner}</div>`;
}

function applyStyleToDOM(blockId,prop,val){
    const el=document.querySelector(`.block-wrapper[data-block-id="${blockId}"]`);
    if(!el)return;
    if(el.dataset.component==='widget-button'){
        const btn=el.querySelector('.lp-btn');
        if(btn){
            if(prop==='gradient'){btn.style.background=val;btn.style.removeProperty('background-color');}
            else if(['text-align','margin'].includes(prop))el.style[prop]=val;
            else btn.style[prop]=val;
        }
    }else{el.style[prop]=val;}
}
function applyContentToDOM(blockId,key,val){
    const el=document.querySelector(`.block-wrapper[data-block-id="${blockId}"]`);
    if(!el)return;
    if(key==='text'){if(el.dataset.component==='widget-button'){const b=el.querySelector('.lp-btn');if(b)b.textContent=val;}else{const t=el.querySelector('h1,h2,h3,h4,h5,h6,p');if(t)t.textContent=val;}}
}

function initDragDrop(){
    document.querySelectorAll('.widget-chip[draggable]').forEach(i=>{i.addEventListener('dragstart',function(e){e.dataTransfer.setData('text/plain',JSON.stringify({component:this.dataset.component,type:this.dataset.type}));e.dataTransfer.effectAllowed='copy';this.style.opacity='.5';});i.addEventListener('dragend',function(){this.style.opacity='1';});});
    const c=document.getElementById('blocksContainer');c.addEventListener('dragover',function(e){e.preventDefault();e.dataTransfer.dropEffect='copy';this.classList.add('drag-over');});c.addEventListener('dragleave',function(e){if(!this.contains(e.relatedTarget))this.classList.remove('drag-over');});c.addEventListener('drop',async function(e){e.preventDefault();e.stopPropagation();this.classList.remove('drag-over');try{const d=JSON.parse(e.dataTransfer.getData('text/plain'));if(d.component)await LP.add(d.component,d.type);}catch(err){console.error(err);}});
}
function initSortable(){const c=document.getElementById('blocksContainer');if(sortableInstance)sortableInstance.destroy();if(typeof Sortable==='undefined')return;sortableInstance=new Sortable(c,{handle:'.drag-handle',animation:200,ghostClass:'opacity-50',filter:'.drop-zone',onEnd:async()=>{const order=Array.from(c.querySelectorAll('.block-wrapper')).map(el=>parseInt(el.dataset.blockId));if(order.length)await api(`/api/landing-pages/${PAGE_ID}/blocks/reorder`,'POST',{order});}});}

function initBlockClicks(){document.querySelectorAll('.block-wrapper').forEach(el=>{el.addEventListener('click',function(e){e.stopPropagation();selectBlock(parseInt(this.dataset.blockId));});});}
function selectBlock(id){document.querySelectorAll('.block-wrapper').forEach(el=>el.classList.remove('selected'));const el=document.querySelector(`.block-wrapper[data-block-id="${id}"]`);if(el)el.classList.add('selected');selectedBlockId=id;loadProps(id);}
function clearSelection(){document.querySelectorAll('.block-wrapper').forEach(el=>el.classList.remove('selected'));selectedBlockId=null;document.getElementById('noSelection').style.display='block';document.getElementById('propsContent').style.display='none';}
document.getElementById('builderCanvas').addEventListener('click',function(e){if(e.target===this||e.target.id==='canvasFrame')clearSelection();});

async function loadProps(blockId){
    document.getElementById('noSelection').style.display='none';
    const ct=document.getElementById('propsContent');ct.style.display='block';
    ct.innerHTML='<div style="text-align:center;padding:32px"><span class="spinner"></span></div>';
    try{const r=await fetch(`/api/landing-pages/${PAGE_ID}/blocks/${blockId}`,{headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF}});if(r.status===404){clearSelection();await renderBlocks();return;}if(!r.ok)throw new Error('Failed');const d=await r.json();const block=d.block;if(!block){clearSelection();return;}blocksCache[blockId]=block;const content=block.content||{};let html=`<div style="margin-bottom:12px"><span style="font-size:10px;padding:2px 8px;background:#eef2ff;color:#4f46e5;border-radius:4px;font-weight:500">${block.component}</span></div>`;for(const[key,value]of Object.entries(content)){if(Array.isArray(value))continue;html+=`<div class="style-field"><label>${key}</label><input type="text" class="style-input" value="${esc(String(value||''))}" data-content-key="${key}"></div>`;}ct.innerHTML=html;ct.querySelectorAll('input[data-content-key]').forEach(inp=>{inp.addEventListener('input',function(){applyContentToDOM(blockId,this.dataset.contentKey,this.value);});inp.addEventListener('change',function(){saveContent(blockId,this.dataset.contentKey,this.value);});});document.getElementById('styleContent').innerHTML=buildStyleForm(blockId,block);}catch(e){ct.innerHTML='<p style="color:#dc2626;font-size:12px">خطا</p>';}
}

// ══════════════════════════════════════
// STYLE FORM — Universal Alignment + Widget-specific
// ══════════════════════════════════════
function buildStyleForm(blockId,block){
    const comp=block.component;const ds=(block.styles||{}).desktop||{};const bs=(block.styles||{}).button||{};const hv=(block.styles||{}).hover||{};

    // ── Universal Alignment Section (for ALL widgets) ──
    const alignVal=ds['text-align']||'';
    const alignSection=`<div class="style-section"><div class="style-section-title">تراز</div><div class="align-buttons">
        <button class="align-btn ${alignVal==='left'?'active':''}" data-align="left" onclick="LP.setAlign(${blockId},'left')" title="چپ"><i class="bi bi-text-left"></i></button>
        <button class="align-btn ${alignVal==='center'||!alignVal?'active':''}" data-align="center" onclick="LP.setAlign(${blockId},'center')" title="وسط"><i class="bi bi-text-center"></i></button>
        <button class="align-btn ${alignVal==='right'?'active':''}" data-align="right" onclick="LP.setAlign(${blockId},'right')" title="راست"><i class="bi bi-text-right"></i></button>
    </div></div>`;

    let html=alignSection;

    if(comp==='widget-button'){
        html+=renderSections([
            {t:'رنگ دکمه',f:[{k:'background-color',l:'پس‌زمینه',tp:'color',s:'button',d:'#4f46e5'},{k:'color',l:'رنگ متن',tp:'color',s:'button',d:'#ffffff'},{k:'border',l:'مرز',s:'button'},{k:'gradient',l:'گرادیانت',s:'button',p:'linear-gradient(135deg,#4f46e5,#7c3aed)'}]},
            {t:'تایپوگرافی',f:[{k:'font-size',l:'اندازه',s:'button',p:'15px'},{k:'font-weight',l:'ضخامت',tp:'select',o:['400','500','600','700'],s:'button'},{k:'text-transform',l:'تبدیل متن',tp:'select',o:['none','uppercase','lowercase','capitalize'],s:'button'}]},
            {t:'شکل',f:[{k:'padding',l:'پدینگ',s:'button',p:'12px 28px'},{k:'border-radius',l:'گوشه‌ها',s:'button',p:'10px'},{k:'width',l:'عرض',s:'button',p:'auto'},{k:'box-shadow',l:'سایه',s:'button',p:'0 2px 8px rgba(79,70,229,.25)'}]},
            {t:'چیدمان دکمه',f:[{k:'display',l:'نمایش',tp:'select',o:['inline-flex','inline-block','block','flex'],s:'button'},{k:'gap',l:'فاصله',s:'button',p:'8px'},{k:'margin',l:'حاشیه',s:'wrapper'}]},
            {t:'هاور',f:[{k:'background-color',l:'پس‌زمینه',tp:'color',s:'hover'},{k:'color',l:'رنگ متن',tp:'color',s:'hover'},{k:'box-shadow',l:'سایه',s:'hover',p:'0 4px 16px rgba(79,70,229,.35)'},{k:'transform',l:'ترنسفورم',s:'hover',p:'translateY(-2px)'},{k:'opacity',l:'شفافیت',s:'hover',p:'0.9'}]},
        ],blockId,bs,hv,ds);
    }else{
        html+=renderSections([
            {t:'پس‌زمینه',f:[{k:'background-color',l:'رنگ',tp:'color',s:'desktop'},{k:'background',l:'گرادیانت',s:'desktop',p:'linear-gradient(135deg,#667eea,#764ba2)'},{k:'background-image',l:'تصویر',s:'desktop'},{k:'background-size',l:'اندازه',tp:'select',o:['cover','contain','auto'],s:'desktop'},{k:'background-position',l:'موقعیت',tp:'select',o:['center','top','bottom','left','right'],s:'desktop'},{k:'background-repeat',l:'تکرار',tp:'select',o:['no-repeat','repeat'],s:'desktop'}]},
            {t:'فاصله',f:[{k:'padding',l:'پدینگ',s:'desktop',p:'24px'},{k:'margin',l:'حاشیه',s:'desktop'}]},
            {t:'چیدمان پیشرفته',f:[{k:'display',l:'نمایش',tp:'select',o:['block','flex','grid','inline-block','inline-flex'],s:'desktop'},{k:'flex-direction',l:'جهت فلکس',tp:'select',o:['row','row-reverse','column','column-reverse'],s:'desktop'},{k:'flex-wrap',l:'پیچش فلکس',tp:'select',o:['nowrap','wrap'],s:'desktop'},{k:'justify-content',l:'تراز افقی',tp:'select',o:['flex-start','center','flex-end','space-between','space-around'],s:'desktop'},{k:'align-items',l:'تراز عمودی',tp:'select',o:['flex-start','center','flex-end','stretch'],s:'desktop'},{k:'align-self',l:'خودترازی',tp:'select',o:['auto','flex-start','center','flex-end','stretch'],s:'desktop'},{k:'gap',l:'فاصله',s:'desktop',p:'0'}]},
            {t:'ابعاد',f:[{k:'width',l:'عرض',s:'desktop',p:'100%'},{k:'max-width',l:'حداکثر',s:'desktop',p:'1200px'},{k:'min-width',l:'حداقل',s:'desktop'},{k:'height',l:'ارتفاع',s:'desktop'},{k:'max-height',l:'حداکثر ارتفاع',s:'desktop'},{k:'min-height',l:'حداقل ارتفاع',s:'desktop'}]},
            {t:'مرز و سایه',f:[{k:'border',l:'مرز',s:'desktop'},{k:'border-radius',l:'گوشه‌ها',s:'desktop',p:'12px'},{k:'box-shadow',l:'سایه',s:'desktop'}]},
            {t:'تایپوگرافی',f:[{k:'color',l:'رنگ متن',tp:'color',s:'desktop'},{k:'font-family',l:'فونت',tp:'font',s:'desktop'},{k:'font-size',l:'اندازه',s:'desktop'},{k:'font-weight',l:'ضخامت',tp:'select',o:['300','400','500','600','700','800'],s:'desktop'},{k:'line-height',l:'ارتفاع خط',s:'desktop',p:'1.5'},{k:'letter-spacing',l:'فاصله حروف',s:'desktop'},{k:'text-transform',l:'تبدیل متن',tp:'select',o:['none','uppercase','lowercase','capitalize'],s:'desktop'},{k:'text-decoration',l:'زیرخط',tp:'select',o:['none','underline'],s:'desktop'},{k:'opacity',l:'شفافیت',s:'desktop',p:'1'}]},
            {t:'پیشرفته',f:[{k:'overflow',l:'overflow',tp:'select',o:['visible','hidden','scroll','auto'],s:'desktop'},{k:'position',l:'موقعیت',tp:'select',o:['static','relative','absolute','fixed','sticky'],s:'desktop'},{k:'z-index',l:'z-index',s:'desktop'},{k:'transform',l:'ترنسفورم',s:'desktop'},{k:'transition',l:'ترنزیشن',s:'desktop',p:'all 0.3s ease'}]},
        ],blockId,ds,hv);
    }
    return html;
}

function renderSections(sections,blockId,store,hvStore,wrapperStore){
    let html='';
    for(const sec of sections){
        html+=`<div class="style-section"><div class="style-section-title">${sec.t}</div>`;
        for(const f of sec.f){
            const isHover=f.s==='hover',isButton=f.s==='button',isWrapper=f.s==='wrapper';
            let val=isHover?(hvStore[f.k]||f.d||''):isButton?(store[f.k]||f.d||''):isWrapper?((wrapperStore||{})[f.k]||f.d||''):(store[f.k]||f.d||'');
            if(f.tp==='color'){html+=`<div class="style-field"><label>${f.l}</label><div class="color-field"><input type="color" class="color-picker" value="${val||'#000000'}" data-key="${f.k}" data-section="${f.s}"><input type="text" class="style-input" value="${esc(val)}" placeholder="${f.p||''}" data-key="${f.k}" data-section="${f.s}" data-sync="color"></div></div>`;}
            else if(f.tp==='font'){
                // Build categorized font dropdown
                const bodyFonts=FONT_OPTIONS.filter(f2=>f2.category==='body');
                const displayFonts=FONT_OPTIONS.filter(f2=>f2.category==='display');
                let opts='<option value="">پیش‌فرض (Vazirmatn)</option>';
                if(bodyFonts.length){opts+=`<optgroup label="فونت‌های متنی">${bodyFonts.map(o=>`<option value="${o.value}" ${val===o.value?'selected':''}>${o.label}</option>`).join('')}</optgroup>`;}
                if(displayFonts.length){opts+=`<optgroup label="فونت‌های عنوان">${displayFonts.map(o=>`<option value="${o.value}" ${val===o.value?'selected':''}>${o.label}</option>`).join('')}</optgroup>`;}
                html+=`<div class="style-field"><label>${f.l}</label><select class="style-select font-select" data-key="${f.k}" data-section="${f.s}">${opts}</select></div>`;
            }
            else if(f.tp==='select'){html+=`<div class="style-field"><label>${f.l}</label><select class="style-select" data-key="${f.k}" data-section="${f.s}">${f.o.map(o=>`<option value="${o}" ${val===o?'selected':''}>${o}</option>`).join('')}</select></div>`;}
            else{html+=`<div class="style-field"><label>${f.l}</label><input type="text" class="style-input" value="${esc(val)}" placeholder="${f.p||''}" data-key="${f.k}" data-section="${f.s}"></div>`;}
        }
        html+='</div>';
    }
    setTimeout(()=>{
        document.querySelectorAll('#styleContent [data-key]').forEach(inp=>{
            const h=function(){const k=this.dataset.key,s=this.dataset.section,v=this.value;if(this.dataset.sync==='color'){const p=this.parentElement.querySelector('input[type="color"]');if(p&&this!==p)p.value=v;else if(p){const t=this.parentElement.querySelector('input[type=text]');if(t)t.value=v;}}if(s==='hover'){hvStore[k]=v;updateHoverStyles();}else if(s==='button')store[k]=v;else if(s==='wrapper'){if(!wrapperStore)wrapperStore={};wrapperStore[k]=v;}else store[k]=v;applyStyleToDOM(blockId,k,v);if(k==='font-family'&&v)loadFont(v);};
            inp.addEventListener('input',h);
            inp.addEventListener('change',function(){const k=this.dataset.key,s=this.dataset.section;const sd=s==='hover'?{hover:hvStore}:s==='button'?{button:store}:{desktop:store};if(s==='wrapper')Object.assign(sd,{desktop:wrapperStore||{}});saveStyle(blockId,sd);});
        });
    },50);
    return html;
}

async function flushSave(){if(!selectedBlockId||!blocksCache[selectedBlockId])return;const b=blocksCache[selectedBlockId];try{await api(`/api/landing-pages/${PAGE_ID}/blocks/${selectedBlockId}`,'PUT',{content:b.content,styles:b.styles});}catch(e){console.error(e);}document.getElementById('saveIndicator').style.display='none';}
let saveTimer=null;function debounceSave(){clearTimeout(saveTimer);document.getElementById('saveIndicator').style.display='flex';saveTimer=setTimeout(flushSave,800);}
async function saveContent(blockId,key,value){const b=blocksCache[blockId];if(!b)return;b.content=b.content||{};b.content[key]=value;debounceSave();}
async function saveStyle(blockId,styleData){const b=blocksCache[blockId];if(!b)return;b.styles=b.styles||{};Object.assign(b.styles,styleData);debounceSave();}

function updateLayers(){const ct=document.getElementById('layersContent');const blocks=document.querySelectorAll('.block-wrapper');if(!blocks.length){ct.innerHTML='<div class="panel-empty"><p>خالی</p></div>';return;}ct.innerHTML=Array.from(blocks).map(el=>`<div class="layer-item ${parseInt(el.dataset.blockId)===selectedBlockId?'active':''}" onclick="LP.select(${el.dataset.blockId})"><div class="layer-icon"><i class="bi bi-${el.dataset.component.includes('button')?'hand-index':el.dataset.component.includes('heading')?'type-h1':el.dataset.component.includes('image')?'image':'puzzle'}"></i></div><span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${el.dataset.component}</span></div>`).join('');}

document.querySelectorAll('.panel-tab').forEach(b=>{b.addEventListener('click',function(){document.querySelectorAll('.panel-tab').forEach(x=>x.classList.remove('active'));this.classList.add('active');const t=this.dataset.tab;document.getElementById('propertiesPanel').style.display=t==='properties'?'block':'none';document.getElementById('stylePanel').style.display=t==='style'?'block':'none';document.getElementById('layersPanel').style.display=t==='layers'?'block':'none';});});
document.querySelectorAll('.resp-btn').forEach(b=>{b.addEventListener('click',function(){document.querySelectorAll('.resp-btn').forEach(x=>x.classList.remove('active'));this.classList.add('active');document.getElementById('canvasFrame').className='canvas-frame'+(this.dataset.mode!=='desktop'?' '+this.dataset.mode:'');});});

window.LP={
    async add(c,t){try{document.getElementById('saveIndicator').style.display='flex';await api(`/api/landing-pages/${PAGE_ID}/blocks`,'POST',{component:c,type:t});await renderBlocks();document.getElementById('saveIndicator').style.display='none';}catch(e){document.getElementById('saveIndicator').style.display='none';alert('خطا: '+e.message);}},
    async delete(id){if(!confirm('حذف شود؟'))return;try{document.getElementById('saveIndicator').style.display='flex';await api(`/api/landing-pages/${PAGE_ID}/blocks/${id}`,'DELETE');if(selectedBlockId===id)clearSelection();await renderBlocks();document.getElementById('saveIndicator').style.display='none';}catch(e){document.getElementById('saveIndicator').style.display='none';alert('خطا: '+e.message);}},
    async duplicate(id){try{document.getElementById('saveIndicator').style.display='flex';await api(`/api/landing-pages/${PAGE_ID}/blocks/${id}/duplicate`,'POST');await renderBlocks();document.getElementById('saveIndicator').style.display='none';}catch(e){document.getElementById('saveIndicator').style.display='none';alert('خطا: '+e.message);}},
    select(id){selectBlock(id);document.querySelectorAll('.panel-tab').forEach(b=>b.classList.remove('active'));document.querySelector('.panel-tab[data-tab="properties"]').classList.add('active');document.getElementById('propertiesPanel').style.display='block';document.getElementById('stylePanel').style.display='none';document.getElementById('layersPanel').style.display='none';},
    setAlign(blockId,align){
        // Update wrapper style instantly
        const el=document.querySelector(`.block-wrapper[data-block-id="${blockId}"]`);
        if(el)el.style['text-align']=align;
        // Update cache
        const b=blocksCache[blockId];if(b){b.styles=b.styles||{};b.styles.desktop=b.styles.desktop||{};b.styles.desktop['text-align']=align;}
        // Update button states
        document.querySelectorAll('.align-btn').forEach(btn=>{btn.classList.toggle('active',btn.dataset.align===align);});
        // Save to server
        debounceSave();
    },
};
document.addEventListener('keydown',e=>{if(e.key==='Delete'&&selectedBlockId)LP.delete(selectedBlockId);if(e.ctrlKey&&e.key==='d'&&selectedBlockId){e.preventDefault();LP.duplicate(selectedBlockId);}});
document.addEventListener('DOMContentLoaded',()=>{initDragDrop();renderBlocks();});
if(document.readyState!=='loading'){initDragDrop();renderBlocks();}
})();
</script>
</body>
</html>
