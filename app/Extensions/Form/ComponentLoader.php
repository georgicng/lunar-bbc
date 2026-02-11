<?php

namespace App\Extensions\Form;

use Lunar\Models\Attribute;
use Filament\Forms\Components\Component;
use Lunar\Admin\Support\FieldTypes\TextField;
class ComponentLoader extends \Lunar\Admin\Support\Forms\AttributeData
{
    public function getFilamentComponentWOState(Attribute $attribute): Component
    {
        $fieldType = $this->fieldTypes[
        $attribute->type
        ] ?? TextField::class;

        /** @var Component $component */
        $component = $fieldType::getFilamentComponent($attribute);

        return $component
            ->label(
                $attribute->translate('name')
            )
            ->required($attribute->required)
            ->default($attribute->default_value);
    }
}
