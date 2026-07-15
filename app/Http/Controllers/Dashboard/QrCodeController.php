<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\QrCode;
use App\Services\QrRenderer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $qrCodes = QrCode::where('user_id', auth()->id())
            ->with('card')
            ->latest()
            ->paginate(12);

        // Pre-render QR thumbnails using the same renderer as preview/export
        $qrThumbnails = [];
        foreach ($qrCodes as $qr) {
            try {
                $qrThumbnails[$qr->id] = QrRenderer::fromModel($qr)->toPngDataUri();
            } catch (\Throwable) {
                $qrThumbnails[$qr->id] = null;
            }
        }

        return view('dashboard.qr-codes.index', compact('qrCodes', 'qrThumbnails'));
    }

    public function create()
    {
        $cards = Card::where('user_id', auth()->id())->pluck('title', 'id');

        return view('dashboard.qr-codes.create', compact('cards'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:static,dynamic',
            'content' => 'required|string|max:2048',
            'card_id' => 'nullable|exists:cards,id',
            // Colors
            'foreground_color' => 'nullable|string|max:7',
            'background_color' => 'nullable|string|max:7',
            'gradient_from' => 'nullable|string|max:7',
            'gradient_to' => 'nullable|string|max:7',
            // Logo
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'logo_size' => 'nullable|integer|min:10|max:100',
            'logo_padding' => 'nullable|integer|min:0|max:20',
            // Style
            'style' => 'nullable|string|in:square,rounded,dots',
            'shape' => 'nullable|string|in:square,circle',
            'pattern' => 'nullable|string|in:default,diamond,circle,star,heart',
            'size' => 'nullable|integer|min:100|max:1000',
            'error_correction' => 'nullable|string|in:L,M,Q,H',
            // Eye
            'eye_style' => 'nullable|string|in:square,rounded,dots,circle',
            'eye_color' => 'nullable|string|max:7',
            // Frame
            'frame_style' => 'nullable|string|in:none,box,circle,bubble',
            'frame_color' => 'nullable|string|max:7',
            // Text
            'text' => 'nullable|string|max:100',
            'text_position' => 'nullable|string|in:top,bottom',
            'text_font' => 'nullable|string|max:50',
            'text_size' => 'nullable|integer|min:8|max:32',
            'text_color' => 'nullable|string|max:7',
            // Margin & Resolution
            'margin' => 'nullable|integer|min:0|max:50',
            'resolution' => 'nullable|integer|min:100|max:1000',
            // Template
            'template' => 'nullable|string|max:50',
        ]);

        $validated['user_id'] = auth()->id();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('qr-logos', 'public');
            $validated['logo_path'] = $path;
        }

        // Handle preset templates
        if (!empty($validated['template'])) {
            $preset = $this->getPresetTemplate($validated['template']);
            if ($preset) {
                $validated = array_merge($preset, $validated);
            }
        }

        // Remove template key before saving
        unset($validated['template']);

        $qrCode = QrCode::create($validated);

        if ($qrCode->type === 'dynamic') {
            $qrCode->update(['content' => url("/qr/{$qrCode->unique_code}")]);
        }

        return redirect()->route('dashboard.qr.show', $qrCode)
            ->with('success', 'کد QR با موفقیت ایجاد شد.');
    }

    public function show(QrCode $qrCode)
    {
        $this->authorize('view', $qrCode);

        $qrCode->load('card');
        $recentScans = $qrCode->scans()->latest()->take(20)->get();

        $scanStats = [
            'total' => $qrCode->scans_count,
            'today' => $qrCode->scans()->whereDate('created_at', today())->count(),
            'this_week' => $qrCode->scans()->where('created_at', '>=', now()->subWeek())->count(),
            'this_month' => $qrCode->scans()->where('created_at', '>=', now()->subMonth())->count(),
        ];

        // Pre-render using the same renderer as preview/export
        $qrImage = null;
        try {
            $qrImage = QrRenderer::fromModel($qrCode)->toPngDataUri();
        } catch (\Throwable) {
        }

        return view('dashboard.qr-codes.show', compact('qrCode', 'recentScans', 'scanStats', 'qrImage'));
    }

    public function destroy(QrCode $qrCode)
    {
        $this->authorize('delete', $qrCode);
        $qrCode->delete();

        return redirect()->route('dashboard.qr.index')
            ->with('success', 'کد QR حذف شد.');
    }

    public function download(QrCode $qrCode, string $format)
    {
        $this->authorize('view', $qrCode);

        $renderer = QrRenderer::fromModel($qrCode);
        $safeTitle = preg_replace('/[^a-zA-Z0-9_-]/', '-', $qrCode->title);

        return match ($format) {
            'png' => response($renderer->toPng() ?? '')
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'attachment; filename="qr-' . $safeTitle . '.png"'),
            'svg' => response($renderer->toSvg())
                ->header('Content-Type', 'image/svg+xml')
                ->header('Content-Disposition', 'attachment; filename="qr-' . $safeTitle . '.svg"'),
            default => abort(404),
        };
    }

    public function preview(Request $request)
    {
        try {
            $renderer = QrRenderer::fromLivewire($request->all());
            $png = $renderer->toPng();
            if ($png) {
                return response($png)->header('Content-Type', 'image/png');
            }
        } catch (\Throwable) {
        }
        return response('', 204);
    }

    public function tempDownload(string $filename)
    {
        $path = storage_path('app/qr-downloads/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $mimeType = $ext === 'svg' ? 'image/svg+xml' : 'image/png';

        $response = response()->download($path, $filename, [
            'Content-Type' => $mimeType,
        ]);

        // Delete after sending
        $response->deleteFileAfterSend(true);

        return $response;
    }

    private function getPresetTemplate(string $template): ?array
    {
        $presets = [
            'business' => [
                'foreground_color' => '#1a365d',
                'background_color' => '#FFFFFF',
                'style' => 'rounded',
                'shape' => 'square',
                'eye_style' => 'rounded',
                'eye_color' => '#1a365d',
                'frame_style' => 'box',
                'frame_color' => '#1a365d',
                'margin' => 15,
            ],
            'restaurant' => [
                'foreground_color' => '#c0392b',
                'background_color' => '#FFF5F5',
                'style' => 'dots',
                'shape' => 'circle',
                'eye_style' => 'circle',
                'eye_color' => '#c0392b',
                'margin' => 10,
            ],
            'cafe' => [
                'foreground_color' => '#6F4E37',
                'background_color' => '#F5F0E8',
                'style' => 'rounded',
                'shape' => 'square',
                'eye_style' => 'dots',
                'eye_color' => '#6F4E37',
                'margin' => 12,
            ],
            'event' => [
                'foreground_color' => '#7c3aed',
                'background_color' => '#F5F3FF',
                'style' => 'dots',
                'shape' => 'circle',
                'eye_style' => 'circle',
                'eye_color' => '#7c3aed',
                'margin' => 10,
            ],
            'product' => [
                'foreground_color' => '#059669',
                'background_color' => '#F0FDF4',
                'style' => 'rounded',
                'shape' => 'square',
                'eye_style' => 'rounded',
                'eye_color' => '#059669',
                'margin' => 12,
            ],
            'wedding' => [
                'foreground_color' => '#b8860b',
                'background_color' => '#FFFDF5',
                'style' => 'dots',
                'shape' => 'circle',
                'eye_style' => 'dots',
                'eye_color' => '#b8860b',
                'text' => 'ازدواج مبارک',
                'text_position' => 'bottom',
                'text_color' => '#b8860b',
                'margin' => 15,
            ],
            'tech' => [
                'foreground_color' => '#6366f1',
                'background_color' => '#FFFFFF',
                'style' => 'square',
                'shape' => 'square',
                'eye_style' => 'square',
                'eye_color' => '#6366f1',
                'margin' => 10,
            ],
            'minimal' => [
                'foreground_color' => '#000000',
                'background_color' => '#FFFFFF',
                'style' => 'square',
                'shape' => 'square',
                'eye_style' => 'square',
                'eye_color' => '#000000',
                'margin' => 5,
            ],
            'colorful' => [
                'foreground_color' => '#e91e63',
                'background_color' => '#E3F2FD',
                'gradient_from' => '#e91e63',
                'gradient_to' => '#9c27b0',
                'style' => 'dots',
                'shape' => 'circle',
                'eye_style' => 'circle',
                'eye_color' => '#e91e63',
                'margin' => 10,
            ],
            'dark' => [
                'foreground_color' => '#FFFFFF',
                'background_color' => '#1a1a2e',
                'style' => 'rounded',
                'shape' => 'square',
                'eye_style' => 'rounded',
                'eye_color' => '#FFFFFF',
                'margin' => 15,
            ],
        ];

        return $presets[$template] ?? null;
    }
}
