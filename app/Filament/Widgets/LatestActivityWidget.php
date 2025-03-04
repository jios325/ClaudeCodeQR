<?php

namespace App\Filament\Widgets;

use App\Models\DynamicQRCode;
use App\Models\ScanLog;
use App\Models\StaticQRCode;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestActivityWidget extends BaseWidget
{
    protected static ?string $heading = 'Latest Activity';
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ScanLog::query()
                    ->latest('timestamp')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('timestamp')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('qr_code_type')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'dynamic' => 'success',
                        'static' => 'warning',
                    }),
                TextColumn::make('qr_code_id')
                    ->label('QR Code')
                    ->formatStateUsing(function ($state, ScanLog $record) {
                        if ($record->qr_code_type === 'dynamic') {
                            $qrCode = DynamicQRCode::find($state);
                            return $qrCode ? $qrCode->filename : 'Unknown';
                        } else {
                            $qrCode = StaticQRCode::find($state);
                            return $qrCode ? $qrCode->filename : 'Unknown';
                        }
                    }),
                TextColumn::make('ip_address'),
                TextColumn::make('user_agent')
                    ->limit(30),
            ])
            ->paginated(false);
    }
}