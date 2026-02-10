<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lunar\Admin\Support\Facades\LunarPanel;
use Lunar\Facades\AttributeManifest;
use Lunar\Facades\ModelManifest;
use App\Models\Contracts\ProductCustomisation as ProductCustomisationInterface;
//use Lunar\Models\Product;
use Lunar\Models\ProductType;
use App\Models\Product;
use App\Models\Page;
use App\Models\ProductCustomisation;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Extensions\Modifiers\CityShippingModifier;
use App\Extensions\Modifiers\PickupShippingModifier;
use App\Extensions\PaymentTypes\Paystack;
use App\Extensions\PaymentTypes\BankTransfer;
use Lunar\Facades\Payments;
use Outerweb\FilamentSettings\Filament\Plugins\FilamentSettingsPlugin;
use App\Filament\Resources;
use Datlechin\FilamentMenuBuilder\FilamentMenuBuilderPlugin;
use Datlechin\FilamentMenuBuilder\MenuPanel\StaticMenuPanel;
use Lunar\Admin\Support\Facades\AttributeData;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        LunarPanel::extensions([
            \Lunar\Admin\Filament\Resources\ProductResource::class => \App\Extensions\Resources\ProductResource::class,
            \Lunar\Admin\Filament\Resources\ProductTypeResource::class => \App\Extensions\Resources\ProductTypeResource::class,
        ]);
        LunarPanel::panel(fn($panel) => $panel
            ->resources([
                // Register new Filament Resources
                Resources\CityResource::class,
                Resources\PickupCenterResource::class,
                Resources\CityShippingResource::class,
                Resources\PageResource::class
            ])
            ->plugins(
                [
                    FilamentSettingsPlugin::make()
                        ->pages([
                            \App\Filament\Pages\GeneralSettings::class,
                        ]),
                    FilamentMenuBuilderPlugin::make()
                        ->addLocations([
                            'header' => 'Header',
                            'footer' => 'Footer',
                        ])
                        ->showCustomTextPanel()
                        ->addMenuPanels([
                            StaticMenuPanel::make()
                                ->add('Home', url('/'))
                                ->add('Blog', url('/blog')),
                        ])
                ]
            ))
            ->register();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(\Lunar\Base\ShippingModifiers $shippingModifiers): void
    {
        //override morph map to include product_customisation
        //TODO: find better way to merge with existing morph map and confirm nothing breaks
        Relation::enforceMorphMap([
            'product' => \Lunar\Models\Product::class,
            'product_variant' => \Lunar\Models\ProductVariant::class,
            'product_option' => \Lunar\Models\ProductOption::class,
            'product_option_value' => \Lunar\Models\ProductOptionValue::class,
            'collection' => \Lunar\Models\Collection::class,
            'customer' => \Lunar\Models\Customer::class,
            'cart' => \Lunar\Models\Cart::class,
            'cart_line' => \Lunar\Models\CartLine::class,
            'order' => \Lunar\Models\Order::class,
            'order_line' => \Lunar\Models\OrderLine::class,
            'product_customisation' => ProductCustomisation::class,
            'page' => Page::class,
        ]);

        ModelManifest::replace(
            \Lunar\Models\Contracts\Product::class,
            Product::class,
        );
        ModelManifest::add(
            ProductCustomisationInterface::class,
            ProductCustomisation::class,
        );

        AttributeManifest::addtype(ProductCustomisation::class, 'product_customisation');
        AttributeManifest::addtype(Page::class, 'page');
        AttributeData::registerFieldType(\App\Extensions\FieldTypes\Repeater::class, \App\Extensions\FieldTypes\Repeater\RepeaterType::class);
        AttributeData::registerFieldType(\App\Extensions\FieldTypes\RepeaterGroup::class, \App\Extensions\FieldTypes\RepeaterGroup\RepeaterGroupType::class);

        ProductType::resolveRelationUsing('customisationAttributes', function ($productTypeModel) {
            return $productTypeModel->mappedAttributes()->whereAttributeType(
                ProductCustomisation::morphName()
            );
        });

        $shippingModifiers->add(
            PickupShippingModifier::class
        );

        $shippingModifiers->add(
            CityShippingModifier::class
        );

        Payments::extend('paystack', function ($app) {
            return $app->make(Paystack::class);
        });

        Payments::extend('bank-transfer', function ($app) {
            return $app->make(BankTransfer::class);
        });
    }
}
