<?php

namespace App\Models;

use Lunar\Base\BaseModel;
use Lunar\Base\Casts\AsAttributeData;
use Lunar\Base\Traits\HasAttributes;
use Lunar\Base\Traits\HasUrls;

class Page extends BaseModel
{
    use HasAttributes;
    use HasUrls;

    protected $fillable = [
        'status',
        'attribute_data',
        'blocks',
    ];

    /**
     * Define which attributes should be cast.
     *
     * @var array
     */
    protected $casts = [
        'attribute_data' => AsAttributeData::class,
    ];

     public function getMorphClass(): string
    {
        return 'page';
    }
}
