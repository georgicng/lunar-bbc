<?php

namespace App\Extensions\Modifiers;

use Lunar\Base\ShippingModifier;
use Lunar\DataTypes\Price;
use Lunar\DataTypes\ShippingOption;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Contracts\Cart;
use Lunar\Models\Currency;
use Lunar\Models\TaxClass;
use App\Models\City;
use App\Models\PickupCenter;

class PickupShippingModifier extends ShippingModifier
{
    public function handle(Cart $cart, \Closure $next)
    {
        if (!$cart?->shippingAddress?->city) {
            return ShippingManifest::addOptions(collect());
        }

        $city = City::where('name', $cart->shippingAddress->city)->first();
        if (!$city) {
            return ShippingManifest::addOptions(collect());
        }
        $models = PickupCenter::where('city_id', $city->id)->get();
        //logger(json_encode($models));
        if (!$models) {
            return ShippingManifest::addOptions(collect());
        }

        // Get the tax class
        $taxClass = TaxClass::first();
        ShippingManifest::addOptions($models->map(fn($model) => new ShippingOption(
            name: $model?->name,
            description: "Pick your order up in our {$model?->name} store",
            identifier: "PICKUP_{$model?->id}",
            price: new Price(intval($model?->rate) * 100, $cart->currency, 1),
            taxClass: $taxClass,
            // This is for your reference, so you can check if a collection option has been selected.
            //collect: $shipping === "PICKUP_{$model?->id}",
        )));
        return $next($cart);
    }
}
