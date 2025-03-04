<?php

namespace App\Filament\Widgets;

use App\Models\ScanLog;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class ScanChartWidget extends ChartWidget
{
    protected static ?string $heading = 'QR Code Scans (Last 7 Days)';

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(function ($day) {
            return Carbon::now()->subDays($day)->format('Y-m-d');
        });

        $scans = $days->map(function ($day) {
            return ScanLog::whereDate('timestamp', $day)->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Scans',
                    'data' => $scans,
                    'backgroundColor' => '#EF4444',
                    'borderColor' => '#EF4444',
                ],
            ],
            'labels' => $days->map(function ($day) {
                return Carbon::parse($day)->format('M d');
            }),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}