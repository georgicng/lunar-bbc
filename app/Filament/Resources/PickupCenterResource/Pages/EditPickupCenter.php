<?php

namespace App\Filament\Resources\PickupCenterResource\Pages;

use App\Filament\Resources\PickupCenterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPickupCenter extends EditRecord
{
    protected static string $resource = PickupCenterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
