<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lunar\Base\BaseModel;
use Lunar\Models\Country;
use Lunar\Models\State;

class PickupCenter extends BaseModel
{

     protected $fillable = [
        'name',
        'rate',
        'status',
        'location',
        'phone',
        'email',
        'whatsapp',
        'address',
        'landmark',
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
