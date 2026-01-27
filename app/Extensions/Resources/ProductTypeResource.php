<?php

namespace App\Extensions\Resources;

use Filament\Forms;
use Lunar\Admin\Support\Forms\Components\AttributeSelector;
use Lunar\Models\Product;
use Lunar\Models\ProductVariant;
use App\Models\ProductCustomisation;

class ProductTypeResource extends \Lunar\Admin\Support\Extending\ResourceExtension
{
    public function extendForm(Forms\Form $form): Forms\Form
    {
        $former = $form->getComponents(withHidden: true);
        return $form
            ->schema([
                $former[0],
                Forms\Components\Tabs::make('Attributes')->tabs([
                    Forms\Components\Tabs\Tab::make(__('lunarpanel::producttype.tabs.product_attributes.label'))
                        ->schema([
                            AttributeSelector::make('mappedAttributes')
                                ->withType(Product::morphName())
                                ->relationship(name: 'mappedAttributes')
                                ->label('')
                                ->columnSpan(2),
                        ]),
                    Forms\Components\Tabs\Tab::make(__('lunarpanel::producttype.tabs.variant_attributes.label'))
                        ->schema([
                            AttributeSelector::make('mappedAttributes')
                                ->withType(ProductVariant::morphName())
                                ->relationship(name: 'mappedAttributes')
                                ->label('')
                                ->columnSpan(2),
                        ])->visible(
                            config('lunar.panel.enable_variants', true)
                        ),
                    Forms\Components\Tabs\Tab::make(__('Customisations'))
                        ->schema([
                            AttributeSelector::make('mappedAttributes')
                                ->withType(ProductCustomisation::morphName())
                                ->relationship(name: 'mappedAttributes')
                                ->label('')
                                ->columnSpan(2),
                        ])->visible(
                            config('lunar.panel.enable_customisations', true)
                        ),

                ])->columnSpan(3),
            ]);
    }
}

// Typically placed in your AppServiceProvider file...
