<?php

namespace App\Filament\Widgets;

use App\Models\DynamicQRCode;
use App\Models\ScanLog;
use App\Models\StaticQRCode;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QRCodeStatsOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected function getStats(): array
    {
        $totalQRCodes = DynamicQRCode::count() + StaticQRCode::count();
        $dynamicQRCodes = DynamicQRCode::count();
        $staticQRCodes = StaticQRCode::count();
        $totalScans = ScanLog::count();
        
        // Calculate scan trends
        $scansToday = ScanLog::whereDate('timestamp', Carbon::today())->count();
        $scansYesterday = ScanLog::whereDate('timestamp', Carbon::yesterday())->count();
        $scansThisWeek = ScanLog::whereBetween('timestamp', [Carbon::now()->startOfWeek(), Carbon::now()])->count();
        $scansLastWeek = ScanLog::whereBetween('timestamp', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();
        
        // Calculate scan percentages
        $scanTodayDiff = $scansYesterday > 0 ? (($scansToday - $scansYesterday) / $scansYesterday) * 100 : 0;
        $scanWeekDiff = $scansLastWeek > 0 ? (($scansThisWeek - $scansLastWeek) / $scansLastWeek) * 100 : 0;
        
        // Get most popular QR codes
        $mostScannedDynamic = DynamicQRCode::orderBy('scan_count', 'desc')->first();
        $mostScannedDynamicCount = $mostScannedDynamic ? $mostScannedDynamic->scan_count : 0;
        $mostScannedDynamicName = $mostScannedDynamic ? $mostScannedDynamic->filename : 'None';
        
        return [
            Stat::make('Total QR Codes', $totalQRCodes)
                ->description('Total generated QR codes')
                ->descriptionIcon('heroicon-o-qr-code')
                ->color('primary')
                ->chart([
                    $dynamicQRCodes, $staticQRCodes
                ]),
            
            Stat::make('Dynamic QR Codes', $dynamicQRCodes)
                ->description('Updatable QR codes')
                ->descriptionIcon('heroicon-o-arrow-path')
                ->color('success'),
            
            Stat::make('Static QR Codes', $staticQRCodes)
                ->description('Fixed content QR codes')
                ->descriptionIcon('heroicon-o-document')
                ->color('warning'),
            
            Stat::make('Today\'s Scans', $scansToday)
                ->description($scanTodayDiff >= 0 ? "↑ {$scanTodayDiff}% from yesterday" : "↓ " . abs($scanTodayDiff) . "% from yesterday")
                ->descriptionIcon($scanTodayDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->color($scanTodayDiff >= 0 ? 'success' : 'danger'),
                
            Stat::make('Weekly Scans', $scansThisWeek)
                ->description($scanWeekDiff >= 0 ? "↑ {$scanWeekDiff}% from last week" : "↓ " . abs($scanWeekDiff) . "% from last week")
                ->descriptionIcon($scanWeekDiff >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->color($scanWeekDiff >= 0 ? 'success' : 'danger'),
                
            Stat::make('Total Scans', $totalScans)
                ->description('QR code scans')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color('primary'),
                
            Stat::make('Most Scanned QR', $mostScannedDynamicName)
                ->description("{$mostScannedDynamicCount} scans")
                ->descriptionIcon('heroicon-o-fire')
                ->color('danger'),
        ];
    }
}