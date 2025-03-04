<?php

namespace App\Filament\Resources\StaticQRCodeResource\Pages;

use App\Filament\Resources\StaticQRCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStaticQRCodes extends ListRecords
{
    protected static string $resource = StaticQRCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
