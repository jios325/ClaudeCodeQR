<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DynamicQRCodeResource\Pages;
use App\Filament\Resources\DynamicQRCodeResource\RelationManagers;
use App\Models\DynamicQRCode;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class DynamicQRCodeResource extends Resource
{
    protected static ?string $model = DynamicQRCode::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $navigationGroup = 'QR Codes';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Dynamic QR Codes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('QR Code Information')
                    ->schema([
                        Forms\Components\TextInput::make('filename')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('redirect_identifier')
                            ->default(fn () => Str::random(10))
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                        Forms\Components\TextInput::make('url')
                            ->label('Target URL')
                            ->url()
                            ->required()
                            ->maxLength(2048),
                        Forms\Components\Select::make('user_id')
                            ->label('Owner')
                            ->options(User::pluck('username', 'id'))
                            ->required()
                            ->searchable(),
                        Forms\Components\Toggle::make('status')
                            ->label('Active')
                            ->default(true),
                    ]),
                
                Forms\Components\Section::make('QR Code Appearance')
                    ->schema([
                        Forms\Components\Tabs::make('appearance_options')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('Basic')
                                    ->schema([
                                        Forms\Components\ColorPicker::make('foreground_color')
                                            ->label('QR Code Color')
                                            ->default('#000000'),
                                        Forms\Components\ColorPicker::make('background_color')
                                            ->label('Background Color')
                                            ->default('#FFFFFF'),
                                        Forms\Components\Select::make('precision')
                                            ->options([
                                                'L' => 'L - Lowest',
                                                'M' => 'M - Medium',
                                                'Q' => 'Q - High',
                                                'H' => 'H - Highest',
                                            ])
                                            ->default('L')
                                            ->required(),
                                        Forms\Components\Select::make('size')
                                            ->options([
                                                100 => '100px',
                                                200 => '200px',
                                                300 => '300px',
                                                400 => '400px',
                                                500 => '500px',
                                            ])
                                            ->default(200)
                                            ->required(),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Advanced')
                                    ->schema([
                                        Forms\Components\Toggle::make('use_gradient')
                                            ->label('Use Gradient Colors')
                                            ->default(false)
                                            ->reactive(),
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\ColorPicker::make('gradient_start_color')
                                                    ->label('Gradient Start Color')
                                                    ->default('#000000')
                                                    ->visible(fn (callable $get) => $get('use_gradient')),
                                                Forms\Components\ColorPicker::make('gradient_end_color')
                                                    ->label('Gradient End Color')
                                                    ->default('#3B82F6')
                                                    ->visible(fn (callable $get) => $get('use_gradient')),
                                            ]),
                                        Forms\Components\ColorPicker::make('eye_color')
                                            ->label('QR Code Eye Color')
                                            ->helperText('Leave empty to use the same as foreground color'),
                                        Forms\Components\Select::make('style')
                                            ->label('QR Code Style')
                                            ->options([
                                                'square' => 'Square',
                                                'round' => 'Round',
                                                'dot' => 'Dot',
                                            ])
                                            ->default('square')
                                            ->required(),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Logo')
                                    ->schema([
                                        Forms\Components\Toggle::make('has_logo')
                                            ->label('Add Logo to QR Code')
                                            ->default(false)
                                            ->reactive(),
                                        Forms\Components\FileUpload::make('logo_path')
                                            ->label('Logo Image')
                                            ->image()
                                            ->imageResizeMode('cover')
                                            ->imageCropAspectRatio('1:1')
                                            ->directory('qr-logos')
                                            ->visible(fn (callable $get) => $get('has_logo')),
                                        Forms\Components\Select::make('logo_size')
                                            ->label('Logo Size')
                                            ->options([
                                                10 => '10%',
                                                15 => '15%',
                                                20 => '20%',
                                                25 => '25%',
                                                30 => '30%',
                                                35 => '35%',
                                                40 => '40%',
                                            ])
                                            ->default(25)
                                            ->helperText('Size of logo relative to QR code (larger logos may make the code harder to scan)')
                                            ->visible(fn (callable $get) => $get('has_logo')),
                                    ]),
                            ])->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.username')
                    ->label('Owner')
                    ->sortable(),
                Tables\Columns\TextColumn::make('filename')
                    ->searchable(),
                Tables\Columns\TextColumn::make('redirect_identifier')
                    ->label('Redirect ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('Target URL')
                    ->limit(30),
                Tables\Columns\ImageColumn::make('qr_code_image')
                    ->label('QR Code')
                    ->getStateUsing(function (DynamicQRCode $record): string {
                        // For this example, we're assuming we'd generate a URL to display the QR code
                        // In a real application, you would implement the actual QR code generation
                        return "https://api.qrserver.com/v1/create-qr-code/?size={$record->size}x{$record->size}&data=" . urlencode(route('redirect.dynamic', ['identifier' => $record->redirect_identifier]));
                    })
                    ->height(50)
                    ->circular(false),
                Tables\Columns\TextColumn::make('scan_count')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('status')
                    ->label('Active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Owner')
                    ->options(User::pluck('username', 'id'))
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                    Tables\Actions\Action::make('download')
                        ->label('Download')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->url(fn (DynamicQRCode $record): string => 
                            "https://api.qrserver.com/v1/create-qr-code/?size={$record->size}x{$record->size}&data=" . urlencode(route('redirect.dynamic', ['identifier' => $record->redirect_identifier])) . "&download=1&format=png"
                        )
                        ->openUrlInNewTab(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDynamicQRCodes::route('/'),
            'create' => Pages\CreateDynamicQRCode::route('/create'),
            'edit' => Pages\EditDynamicQRCode::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
