<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\LatestActivityWidget;
use App\Filament\Widgets\QRCodeChartWidget;
use App\Filament\Widgets\QRCodeStatsOverview;
use App\Filament\Widgets\ScanChartWidget;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    protected static ?int $navigationSort = -2;

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $navigationGroup = null;

    protected static ?string $title = 'Dashboard';
    
    protected function getHeaderWidgets(): array
    {
        return [
            QRCodeStatsOverview::class,
        ];
    }
    
    protected function getFooterWidgets(): array
    {
        return [
            QRCodeChartWidget::class,
            ScanChartWidget::class,
            LatestActivityWidget::class,
        ];
    }
    
    protected function getWidgetsColumns(): int | array
    {
        return [
            'md' => 2,
            'xl' => 3,
        ];
    }
}
