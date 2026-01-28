<?php

namespace App\Models;

use Lunar\Base\Casts\AsAttributeData;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
