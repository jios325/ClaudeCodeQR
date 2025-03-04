<?php

namespace App\Filament\Resources\StaticQRCodeResource\Pages;

use App\Filament\Resources\StaticQRCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStaticQRCode extends EditRecord
{
    protected static string $resource = StaticQRCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
