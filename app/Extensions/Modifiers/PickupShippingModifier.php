<?php

namespace App\Extensions\Modifiers;

use Lunar\Base\ShippingModifier;
use Lunar\DataTypes\Price;
use Lunar\DataTypes\ShippingOption;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Contracts\Cart;
use Lunar\Models\Currency;
use Lunar\Models\TaxClass;

class PickupShippingModifier extends ShippingModifier
{
    public function handle(Cart $cart, \Closure $next)
    {
        // Get the tax class
        $taxClass = TaxClass::first();


        ShippingManifest::addOption(
            new ShippingOption(
                name: 'Pick up in store',
                description: 'Pick your order up in store',
                identifier: 'PICKUP',
                price: new Price(0, $cart->currency, 1),
                taxClass: $taxClass,
                // This is for your reference, so you can check if a collection option has been selected.
                collect: true
            )
        );


        return $next($cart);
    }
}
