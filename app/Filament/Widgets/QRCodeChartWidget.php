<?php

namespace App\Filament\Widgets;

use App\Models\DynamicQRCode;
use App\Models\ScanLog;
use App\Models\StaticQRCode;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class QRCodeChartWidget extends ChartWidget
{
    protected static ?string $heading = 'QR Codes Created (Last 7 Days)';

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(function ($day) {
            return Carbon::now()->subDays($day)->format('Y-m-d');
        });

        $dynamicData = $this->getQRCodesDataForDays($days, DynamicQRCode::class);
        $staticData = $this->getQRCodesDataForDays($days, StaticQRCode::class);

        return [
            'datasets' => [
                [
                    'label' => 'Dynamic QR Codes',
                    'data' => $dynamicData,
                    'backgroundColor' => '#10B981',
                    'borderColor' => '#10B981',
                ],
                [
                    'label' => 'Static QR Codes',
                    'data' => $staticData,
                    'backgroundColor' => '#F59E0B',
                    'borderColor' => '#F59E0B',
                ],
            ],
            'labels' => $days->map(function ($day) {
                return Carbon::parse($day)->format('M d');
            }),
        ];
    }

    protected function getQRCodesDataForDays($days, $model)
    {
        return $days->map(function ($day) use ($model) {
            return $model::whereDate('created_at', $day)->count();
        });
    }

    protected function getType(): string
    {
        return 'line';
    }
}