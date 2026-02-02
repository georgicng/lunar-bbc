<?php

namespace App\Filament\Resources\CityShippingResource\Pages;

use App\Filament\Resources\CityShippingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCityShipping extends EditRecord
{
    protected static string $resource = CityShippingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
