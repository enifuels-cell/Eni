<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    /**
     * Generate QR code with Eni logo in the center
     */
    public static function generateWithLogo($data, $size = 300)
    {
        // Try PNG logo first, then fallback to SVG  
        $logoPaths = [
            public_path('images/eni-logo.png'),
            public_path('images/eni-logo.svg')
        ];
        
        $logoPath = null;
        foreach ($logoPaths as $path) {
            if (file_exists($path)) {
                $logoPath = $path;
                break;
            }
        }
        
        // If no logo found, return basic QR code
        if (!$logoPath) {
            return self::generateBasic($data, $size);
        }
        
        try {
            // Try the merge method that should work now
            $qrCode = QrCode::size($size)->merge($logoPath, 0.2, true)->generate($data);
            return $qrCode;
            
        } catch (\Exception $e) {
            // Try without absolute path flag
            try {
                $qrCode = QrCode::size($size)->merge($logoPath, 0.2, false)->generate($data);
                return $qrCode;
            } catch (\Exception $e2) {
                // If merge fails completely, return basic QR code
                return self::generateBasic($data, $size);
            }
        }
    }
    
    /**
     * Generate QR code without logo (fallback)
     */
    public static function generateBasic($data, $size = 300)
    {
        return QrCode::size($size)->generate($data);
    }
    
    /**
     * Generate QR code as SVG with logo
     */
    public static function generateSvgWithLogo($data, $size = 300)
    {
        $logoPath = public_path('images/eni-logo.svg');
        
        if (!file_exists($logoPath)) {
            return QrCode::size($size)->format('svg')->generate($data);
        }
        
        try {
            return QrCode::size($size)
                ->format('svg')
                ->merge($logoPath, 0.25, true)
                ->generate($data);
                
        } catch (\Exception $e) {
            // Fallback to basic SVG QR code
            return QrCode::size($size)->format('svg')->generate($data);
        }
    }
}
