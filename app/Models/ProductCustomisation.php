<?php

namespace App\Models;

use Lunar\Base\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Lunar\Models\Product;
use Lunar\Facades\AttributeManifest;

class ProductCustomisation extends BaseModel implements Contracts\ProductCustomisation
{

    protected $fillable = [
        'required',
        'min',
        'max',
        'attribute_data',
        'attribute_id',
        'product_id',
        'position'
    ];

    protected $casts = [
        'attribute_data' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::modelClass())->withTrashed();
    }

    public function getMorphClass(): string
    {
        return 'product_customisation';
    }
}
