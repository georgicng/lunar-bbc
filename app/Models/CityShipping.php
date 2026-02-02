<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lunar\Base\BaseModel;
use Lunar\Models\Country;
use Lunar\Models\State;

class CityShipping extends BaseModel
{

     protected $fillable = [
        'name',
        'rate',
        'status',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];


    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::modelClass());
    }

     public function state(): BelongsTo
    {
        return $this->belongsTo(State::modelClass());
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::modelClass());
    }
}
