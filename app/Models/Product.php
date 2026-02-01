<?php

namespace App\Models;

use Lunar\Base\Casts\AsAttributeData;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Lunar\Models\Attribute as LunarAttribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Lunar\Models\ProductType;

class Product extends \Lunar\Models\Product
{

    /**
     * Define which attributes should be
     * fillable during mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'attribute_data',
        'product_type_id',
        'status',
        'brand_id',
        'meta',
    ];

    /**
     * Define which attributes should be cast.
     *
     * @var array
     */
    protected $casts = [
        'attribute_data' => AsAttributeData::class,
        'meta' => 'array',
    ];

    public function customisations(): HasMany
    {
        return $this->hasMany(ProductCustomisation::modelClass());
    }

    /**
     * Get the user's first name.
     */
    protected function customisableAttributes(): Attribute
    {

        return Attribute::make(
            get: fn(mixed $value, array $attributes) => !$attributes['product_type_id']
                ? []
                : ProductType::find($attributes['product_type_id'])
                ->customisationAttributes
                ->mapWithKeys(function (LunarAttribute $attribute) {
                    return [
                        $attribute->id => $attribute,
                    ];
                })
        );
    }

    protected function config(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $attributes['meta'] ? json_decode($attributes['meta'], true)['config'] :  ['rules' => [], 'dynamic_pricing' => false],
        );
    }

}
