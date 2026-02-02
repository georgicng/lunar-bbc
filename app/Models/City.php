<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lunar\Base\BaseModel;
use Lunar\Models\Country;
use Lunar\Models\State;
use Lunar\Base\Traits\HasMacros;
use Lunar\Database\Factories\StateFactory;

class City extends BaseModel
{
    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

     protected $fillable = [
        'name',
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
}
