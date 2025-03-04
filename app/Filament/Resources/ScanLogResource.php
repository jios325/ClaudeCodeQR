<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScanLogResource\Pages;
use App\Filament\Resources\ScanLogResource\RelationManagers;
use App\Models\DynamicQRCode;
use App\Models\ScanLog;
use App\Models\StaticQRCode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ScanLogResource extends Resource
{
    protected static ?string $model = ScanLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Analytics';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Scan Logs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Scan Information')
                    ->schema([
                        Forms\Components\DateTimePicker::make('timestamp')
                            ->required(),
                        Forms\Components\Select::make('qr_code_type')
                            ->options([
                                'dynamic' => 'Dynamic',
                                'static' => 'Static',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('qr_code_id')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('ip_address'),
                        Forms\Components\TextInput::make('user_agent')
                            ->columnSpan('full'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('timestamp')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('qr_code_type')
                    ->colors([
                        'success' => 'dynamic',
                        'warning' => 'static',
                    ]),
                Tables\Columns\TextColumn::make('qr_code_info')
                    ->label('QR Code')
                    ->getStateUsing(function (ScanLog $record): string {
                        if ($record->qr_code_type === 'dynamic') {
                            $qrCode = DynamicQRCode::find($record->qr_code_id);
                            return $qrCode ? $qrCode->filename : 'Unknown';
                        } else {
                            $qrCode = StaticQRCode::find($record->qr_code_id);
                            return $qrCode ? $qrCode->filename : 'Unknown';
                        }
                    }),
                Tables\Columns\TextColumn::make('ip_address')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_agent')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('qr_code_type')
                    ->options([
                        'dynamic' => 'Dynamic',
                        'static' => 'Static',
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('timestamp', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('timestamp', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListScanLogs::route('/'),
            'create' => Pages\CreateScanLog::route('/create'),
            'edit' => Pages\EditScanLog::route('/{record}/edit'),
        ];
    }
}
