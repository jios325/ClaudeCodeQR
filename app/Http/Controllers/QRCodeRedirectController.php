<?php

namespace App\Http\Controllers;

use App\Models\DynamicQRCode;
use App\Models\ScanLog;
use Illuminate\Http\Request;

class QRCodeRedirectController extends Controller
{
    /**
     * Redirect from a dynamic QR code scan
     */
    public function dynamicRedirect(Request $request, $identifier)
    {
        // Find the QR code by its identifier
        $qrCode = DynamicQRCode::where('redirect_identifier', $identifier)
                              ->where('status', true)
                              ->first();

        if (!$qrCode) {
            return redirect('/')->with('error', 'QR Code not found or disabled');
        }

        // Log the scan
        ScanLog::create([
            'qr_code_id' => $qrCode->id,
            'qr_code_type' => 'dynamic',
            'timestamp' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Increment the scan counter
        $qrCode->increment('scan_count');

        // Redirect to the target URL
        return redirect($qrCode->url);
    }
}