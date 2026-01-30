<?php

namespace App\Extensions\Resources;

use Filament\Forms;
use Lunar\Admin\Support\Forms\Components\AttributeSelector;
use Lunar\Models\Product;
use Lunar\Models\ProductVariant;
use App\Models\ProductCustomisation;
use App\Filament\Resources\ProductResource\Pages;

class ProductResource extends \Lunar\Admin\Support\Extending\ResourceExtension
{

    public function extendPages(array $pages) : array
    {
        return [
            ...$pages,
            'customisations' => Pages\ProductCustomisations::route('/{record}/customisations'),
        ];
    }

    public function extendSubNavigation(array $nav) : array
    {
        return [
            ...$nav,
            // This is just a standard Filament page
            // see https://filamentphp.com/docs/3.x/panels/pages#creating-a-page
            Pages\ProductCustomisations::class,
        ];
    }
}

