<?php

namespace App\Extensions\FieldTypes\Repeater;

use Lunar\Admin\Support\FieldTypes\BaseFieldType;
use Filament\Forms\Components;
use Lunar\Models\Attribute;
use Lunar\Models\AttributeGroup;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Component;
use Lunar\Admin\Support\Facades\AttributeData;
use Lunar\Admin\Support\FieldTypes\Dropdown;
use Lunar\Admin\Support\FieldTypes\File;
use Lunar\Admin\Support\FieldTypes\ListField;
use Lunar\Admin\Support\FieldTypes\Number;
use Lunar\Admin\Support\FieldTypes\TextField;
use Lunar\Admin\Support\FieldTypes\Toggle;
use Lunar\Admin\Support\FieldTypes\TranslatedText;
use Lunar\Admin\Support\FieldTypes\Vimeo;
use Lunar\Admin\Support\FieldTypes\YouTube;
use App\Facades\ComponentLoader;

class RepeaterType extends BaseFieldType
{
    protected static string $synthesizer = RepeaterSynth::class;

    protected static array $fieldTypes = [
        'Lunar\\FieldTypes\\Dropdown' => Dropdown::class,
        'Lunar\\FieldTypes\\ListField' => ListField::class,
        'Lunar\\FieldTypes\\TextField' => TextField::class,
        'Lunar\\FieldTypes\\TranslatedText' => TranslatedText::class,
        'Lunar\\FieldTypes\\Toggle' => Toggle::class,
        'Lunar\\FieldTypes\\Youtube' => YouTube::class,
        'Lunar\\FieldTypes\\Vimeo' => Vimeo::class,
        'Lunar\\FieldTypes\\Number' => Number::class,
        'Lunar\\FieldTypes\\File' => File::class,
    ];

    public static function getFilamentComponent(Attribute $attribute): Component
    {
        $min = (int) $attribute->configuration->get('min_length');
        $max = (int) $attribute->configuration->get('max_length');
        $type = (string) $attribute->configuration->get('type');

        $fieldType = static::$fieldTypes[$type] ?? TextField::class;
        $component = $fieldType::getFilamentComponent($attribute);

        return  Repeater::make('feature')
            ->schema([
                $component,
            ])->minItems($min)->maxItems($max); //this is to return a repeater with block component

    }

    public static function getConfigurationFields(): array
    {
        return [
            Components\Grid::make(2)->schema([
                Components\Select::make('type')
                    ->label('Type')
                    ->options(AttributeData::getFieldTypes())
                    ->searchable(),
                Components\TextInput::make('min_length'),
                Components\TextInput::make('max_length'),
            ]),
        ];
    }
}
