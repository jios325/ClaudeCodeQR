<?php

namespace App\Filament\Resources\DynamicQRCodeResource\Pages;

use App\Filament\Resources\DynamicQRCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDynamicQRCodes extends ListRecords
{
    protected static string $resource = DynamicQRCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
