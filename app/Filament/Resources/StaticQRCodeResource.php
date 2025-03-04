<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StaticQRCodeResource\Pages;
use App\Filament\Resources\StaticQRCodeResource\RelationManagers;
use App\Models\StaticQRCode;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StaticQRCodeResource extends Resource
{
    protected static ?string $model = StaticQRCode::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationGroup = 'QR Codes';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Static QR Codes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('QR Code Information')
                    ->schema([
                        Forms\Components\TextInput::make('filename')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('user_id')
                            ->label('Owner')
                            ->options(User::pluck('username', 'id'))
                            ->required()
                            ->searchable(),
                        Forms\Components\Select::make('format')
                            ->options([
                                'png' => 'PNG',
                                'svg' => 'SVG',
                                'eps' => 'EPS',
                            ])
                            ->default('png')
                            ->required(),
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

                Forms\Components\Tabs::make('Content Type')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Text')
                            ->schema([
                                Forms\Components\Textarea::make('content')
                                    ->label('Text Content')
                                    ->required()
                                    ->rows(4)
                                    ->when(fn ($livewire) => 
                                        $livewire instanceof Pages\CreateStaticQRCode || 
                                        ($livewire instanceof Pages\EditStaticQRCode && $livewire->record->content_type === 'text')
                                    )
                                    ->afterStateHydrated(function (Forms\Components\Textarea $component, $state, $record) {
                                        if ($record && $record->content_type === 'text') {
                                            $component->state($record->content);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('content_type', 'text');
                                    }),
                            ]),
                        Forms\Components\Tabs\Tab::make('Email')
                            ->schema([
                                Forms\Components\TextInput::make('email_address')
                                    ->label('Email Address')
                                    ->email()
                                    ->required()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('content', "mailto:$state");
                                        $set('content_type', 'email');
                                    }),
                                Forms\Components\TextInput::make('email_subject')
                                    ->label('Subject (Optional)')
                                    ->afterStateUpdated(function ($state, $old, callable $set, $get) {
                                        $email = $get('email_address');
                                        $content = "mailto:$email";
                                        if ($state) $content .= "?subject=" . urlencode($state);
                                        $set('content', $content);
                                    }),
                            ]),
                        Forms\Components\Tabs\Tab::make('Phone')
                            ->schema([
                                Forms\Components\TextInput::make('phone_number')
                                    ->label('Phone Number')
                                    ->tel()
                                    ->required()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('content', "tel:$state");
                                        $set('content_type', 'phone');
                                    }),
                            ]),
                        Forms\Components\Tabs\Tab::make('SMS')
                            ->schema([
                                Forms\Components\TextInput::make('sms_number')
                                    ->label('Phone Number')
                                    ->tel()
                                    ->required()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $message = $get('sms_message') ?? '';
                                        $content = "sms:$state";
                                        if ($message) $content .= "?body=" . urlencode($message);
                                        $set('content', $content);
                                        $set('content_type', 'sms');
                                    }),
                                Forms\Components\Textarea::make('sms_message')
                                    ->label('Message (Optional)')
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $number = $get('sms_number');
                                        if ($number) {
                                            $content = "sms:$number";
                                            if ($state) $content .= "?body=" . urlencode($state);
                                            $set('content', $content);
                                        }
                                    }),
                            ]),
                        Forms\Components\Tabs\Tab::make('WhatsApp')
                            ->schema([
                                Forms\Components\TextInput::make('whatsapp_number')
                                    ->label('Phone Number (with country code)')
                                    ->tel()
                                    ->required()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $message = $get('whatsapp_message') ?? '';
                                        $content = "https://wa.me/" . preg_replace('/[^0-9]/', '', $state);
                                        if ($message) $content .= "?text=" . urlencode($message);
                                        $set('content', $content);
                                        $set('content_type', 'whatsapp');
                                    }),
                                Forms\Components\Textarea::make('whatsapp_message')
                                    ->label('Message (Optional)')
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $number = $get('whatsapp_number');
                                        if ($number) {
                                            $content = "https://wa.me/" . preg_replace('/[^0-9]/', '', $number);
                                            if ($state) $content .= "?text=" . urlencode($state);
                                            $set('content', $content);
                                        }
                                    }),
                            ]),
                        Forms\Components\Tabs\Tab::make('Location')
                            ->schema([
                                Forms\Components\TextInput::make('latitude')
                                    ->label('Latitude')
                                    ->numeric()
                                    ->required()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $longitude = $get('longitude');
                                        if ($longitude) {
                                            $set('content', "geo:$state,$longitude");
                                            $set('content_type', 'location');
                                        }
                                    }),
                                Forms\Components\TextInput::make('longitude')
                                    ->label('Longitude')
                                    ->numeric()
                                    ->required()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $latitude = $get('latitude');
                                        if ($latitude) {
                                            $set('content', "geo:$latitude,$state");
                                            $set('content_type', 'location');
                                        }
                                    }),
                            ]),
                        Forms\Components\Tabs\Tab::make('WiFi')
                            ->schema([
                                Forms\Components\TextInput::make('wifi_ssid')
                                    ->label('Network Name (SSID)')
                                    ->required()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $this->updateWifiContent($state, $get, $set);
                                    }),
                                Forms\Components\TextInput::make('wifi_password')
                                    ->label('Password')
                                    ->password()
                                    ->revealable()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $this->updateWifiContent($get('wifi_ssid'), $get, $set);
                                    }),
                                Forms\Components\Select::make('wifi_encryption')
                                    ->label('Encryption')
                                    ->options([
                                        'WPA' => 'WPA/WPA2',
                                        'WEP' => 'WEP',
                                        'nopass' => 'None',
                                    ])
                                    ->default('WPA')
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $this->updateWifiContent($get('wifi_ssid'), $get, $set);
                                    }),
                                Forms\Components\Toggle::make('wifi_hidden')
                                    ->label('Hidden Network')
                                    ->default(false)
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $this->updateWifiContent($get('wifi_ssid'), $get, $set);
                                    }),
                            ]),
                    ])
                    ->columnSpanFull(),

                // Hidden field to store the content type
                Forms\Components\Hidden::make('content_type')
                    ->default('text'),
            ]);
    }

    protected static function updateWifiContent($ssid, $get, $set)
    {
        if (!$ssid) return;
        
        $encryption = $get('wifi_encryption') ?? 'WPA';
        $password = $get('wifi_password') ?? '';
        $hidden = $get('wifi_hidden') ? 'H:true' : 'H:false';
        
        $content = "WIFI:S:$ssid;T:$encryption;P:$password;$hidden;;";
        $set('content', $content);
        $set('content_type', 'wifi');
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
                Tables\Columns\BadgeColumn::make('content_type')
                    ->colors([
                        'primary' => 'text',
                        'secondary' => 'email',
                        'success' => 'phone',
                        'danger' => 'sms',
                        'warning' => 'whatsapp',
                        'info' => fn ($state) => in_array($state, ['location', 'vcard', 'wifi']),
                    ]),
                Tables\Columns\TextColumn::make('content')
                    ->label('Content')
                    ->limit(30),
                Tables\Columns\ImageColumn::make('qr_code_image')
                    ->label('QR Code')
                    ->getStateUsing(function (StaticQRCode $record): string {
                        // For this example, we're assuming we'd generate a URL to display the QR code
                        return "https://api.qrserver.com/v1/create-qr-code/?size={$record->size}x{$record->size}&data=" . urlencode($record->content);
                    })
                    ->height(50)
                    ->circular(false),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('content_type')
                    ->options([
                        'text' => 'Text',
                        'email' => 'Email',
                        'phone' => 'Phone',
                        'sms' => 'SMS',
                        'whatsapp' => 'WhatsApp',
                        'skype' => 'Skype',
                        'location' => 'Location',
                        'vcard' => 'vCard',
                        'event' => 'Event',
                        'bookmark' => 'Bookmark',
                        'wifi' => 'WiFi',
                        'paypal' => 'PayPal',
                        'bitcoin' => 'Bitcoin',
                        '2fa' => '2FA',
                    ]),
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
                        ->url(fn (StaticQRCode $record): string => 
                            "https://api.qrserver.com/v1/create-qr-code/?size={$record->size}x{$record->size}&data=" . urlencode($record->content) . "&download=1&format={$record->format}"
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
            'index' => Pages\ListStaticQRCodes::route('/'),
            'create' => Pages\CreateStaticQRCode::route('/create'),
            'edit' => Pages\EditStaticQRCode::route('/{record}/edit'),
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
