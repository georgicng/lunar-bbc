<?php

namespace App\Filament\Resources\CityShippingResource\Pages;

use App\Filament\Resources\CityShippingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCityShippings extends ListRecords
{
    protected static string $resource = CityShippingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
