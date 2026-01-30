<?php

namespace App\Services;

use Lunar\Models\Product;
use Lunar\Models\Collection;

class ProductService
{
    public function getCategories()
    {
        return Collection::all()->map(function ($item) {
            //$item->media = $item->getMedia('images');
            $item->media = $item->getMedia('images')[0];
            return $item;
        });
    }

    public function getProducts($id)
    {
        return Product::when($id, fn ($query) => $query->with('collections')->where('id', $id))->with([
            'images',
            'variants.basePrices',
            'collections',
        ])->get();
        /*  return $collection->load('products');
        return Collection::where('id', $id)->with(['products', 'products.images', 'products.variants.basePrices.currency',
        'products.variants.basePrices.priceable',])->first();  */
    }

    public function getProduct($id)
    {
        return Product::findOrFail($id)->with([
            'images',
            'variants.basePrices.currency',
            'variants.basePrices.priceable',
            'collections',
        ])->first();
    }
}
