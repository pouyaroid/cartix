<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Events\QrCodeScanned;
use App\Http\Controllers\Controller;
use App\Models\QrCode;
use App\Models\QrScan;
use Illuminate\Http\Request;

class QrRedirectController extends Controller
{
    public function redirect(string $code, Request $request)
    {
        $qrCode = QrCode::where('unique_code', $code)->where('is_active', true)->firstOrFail();

        // Log the scan
        $scan = QrScan::create([
            'qr_code_id' => $qrCode->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device_type' => $this->detectDevice($request->userAgent()),
            'browser' => $this->detectBrowser($request->userAgent()),
            'os' => $this->detectOS($request->userAgent()),
            'referrer' => $request->headers->get('referer'),
        ]);

        event(new QrCodeScanned($scan, $qrCode));

        return redirect($qrCode->content, 302);
    }

    private function detectDevice(string $ua): string
    {
        if (preg_match('/mobile|android|iphone|ipad/i', $ua)) {
            return preg_match('/ipad|tablet/i', $ua) ? 'tablet' : 'mobile';
        }
        return 'desktop';
    }

    private function detectBrowser(string $ua): string
    {
        if (str_contains($ua, 'Firefox')) return 'Firefox';
        if (str_contains($ua, 'Edg')) return 'Edge';
        if (str_contains($ua, 'Chrome')) return 'Chrome';
        if (str_contains($ua, 'Safari')) return 'Safari';
        return 'Other';
    }

    private function detectOS(string $ua): string
    {
        if (str_contains($ua, 'Windows')) return 'Windows';
        if (str_contains($ua, 'Mac OS')) return 'macOS';
        if (str_contains($ua, 'Linux')) return 'Linux';
        if (str_contains($ua, 'Android')) return 'Android';
        if (str_contains($ua, 'iPhone') || str_contains($ua, 'iPad')) return 'iOS';
        return 'Other';
    }
}
