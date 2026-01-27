<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lunar\Admin\Support\Facades\LunarPanel;
use Lunar\Facades\AttributeManifest;
use Lunar\Facades\ModelManifest;
use App\Models\Contracts\ProductCustomisation as ProductCustomisationInterface;
use Lunar\Models\Product;
use Lunar\Models\ProductType;

use App\Models\ProductCustomisation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        LunarPanel::extensions([
            \Lunar\Admin\Filament\Resources\ProductTypeResource::class => \App\Extensions\Resources\ProductTypeResource::class,
            \Lunar\Admin\Filament\Resources\ProductResource::class => \App\Extensions\Resources\ProductResource::class,
        ]);
        LunarPanel::register();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*  ModelManifest::add(
            ProductCustomisationInterface::class,
            ProductCustomisation::class,
        ); */
        AttributeManifest::addtype(ProductCustomisation::class);

        Product::resolveRelationUsing('customisations', function ($productModel) {
            //return $productModel->hasMany(ProductCustomisation::class, 'customisation_id');
            return $productModel->hasMany(ProductCustomisation::modelClass());
        });

        ProductType::resolveRelationUsing('customisationAttributes', function ($productTypeModel) {
            return $productTypeModel->mappedAttributes()->whereAttributeType(
                ProductCustomisation::morphName()
            );
        });
    }
}
