<?php

namespace App\Extensions\FieldTypes\Repeater;

use Lunar\Admin\Support\FieldTypes\BaseFieldType;
use Filament\Forms\Components;
use Lunar\Models\Attribute;
use Lunar\Models\AttributeGroup;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Component;
use Lunar\Admin\Support\Facades\AttributeData;

class RepeaterType extends BaseFieldType
{
    protected static string $synthesizer = RepeaterSynth::class;

    public static function getFilamentComponent(Attribute $attribute): Component
    {
        $min = (int) $attribute->configuration->get('min_length');
        $max = (int) $attribute->configuration->get('max_length');

        return  Repeater::make('feature')
            ->schema([
                Components\TextInput::make($attribute->handle),
            ])->minItems($min)->maxItems($max); //this is to return a repeater with block component

    }

    public static function getConfigurationFields(): array
    {
        return [
            Components\Grid::make(2)->schema([
                Components\Select::make('group_id')
                    ->label('Group')
                    ->options(AttributeData::getFieldTypes())
                    ->searchable(),
                Components\TextInput::make('min_length'),
                Components\TextInput::make('max_length'),
            ]),
        ];
    }
}
