<?php

namespace App\Filament\Resources\PickupCenterResource\Pages;

use App\Filament\Resources\PickupCenterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPickupCenters extends ListRecords
{
    protected static string $resource = PickupCenterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
