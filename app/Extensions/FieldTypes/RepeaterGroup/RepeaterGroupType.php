<?php

namespace App\Extensions\FieldTypes\RepeaterGroup;

use Lunar\Admin\Support\FieldTypes\BaseFieldType;
use Filament\Forms\Components;
use Lunar\Models\Attribute;
use Lunar\Models\AttributeGroup;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Component;
use Lunar\Admin\Support\Facades\AttributeData;

class RepeaterGroupType extends BaseFieldType
{
    protected static string $synthesizer = RepeaterGroupSynth::class;

    public static function getFilamentComponent(Attribute $attribute): Component
    {
        $min = (int) $attribute->configuration->get('min_length');
        $max = (int) $attribute->configuration->get('max_length');
        $group = (int) $attribute->configuration->get('group_id');

        return  static::getBlock($group, $min, $max); //this is to return a repeater with block component

    }

    public static function getConfigurationFields(): array
    {
        $options = AttributeGroup::where(
            'attributable_type',
            'page'
        )->get()->pluck('name.en', 'id');

        logger()->info(json_encode($options));

        return [
            Components\Grid::make(2)->schema([
                Components\Select::make('group_id')
                    ->label('Group')
                    ->options($options)
                    ->searchable(),
                Components\TextInput::make('min_length'),
                Components\TextInput::make('max_length'),
            ]),
        ];
    }

    protected static function getBlock($id, $min, $max): Component
    {
        $group = AttributeGroup::findOrFail($id);
        $components = $group->attributes->map(function ($field) {
            return AttributeData::getFilamentComponent($field);
        })->toArray();

        return Repeater::make($group->handle)
            ->schema($components)->minItems($min)->maxItems($max);
    }
}
