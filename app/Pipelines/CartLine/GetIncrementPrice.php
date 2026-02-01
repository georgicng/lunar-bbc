<?php

namespace App\Pipelines\CartLine;

use Closure;
use Lunar\DataTypes\Price;
use Lunar\Facades\Pricing;
use Lunar\Models\CartLine;
use Lunar\Models\Contracts\CartLine as CartLineContract;
use Spatie\LaravelBlink\BlinkFacade as Blink;
use App\Lib\RuleEvaluator;

class GetIncrementPrice
{
    /**
     * Called just before cart totals are calculated.
     *
     * @return void
     */
    public function handle(CartLineContract $cartLine, Closure $next)
    {
        /** @var CartLine $cart */
        $purchasable = $cartLine->purchasable;
        $cart = $cartLine->cart;

        if ($customer = $cart->customer) {
            $customerGroups = $customer->customerGroups;
        } else {
            $customerGroups = $cart->user?->customers->pluck('customerGroups')->flatten();
        }

        $currency = Blink::once('currency_' . $cart->currency_id, function () use ($cart) {
            return $cart->currency;
        });

        $priceResponse = Pricing::currency($currency)
            ->qty($cartLine->quantity)
            ->currency($cart->currency)
            ->customerGroups($customerGroups)
            ->for($purchasable)
            ->get();

        $cartLine->unitPrice = new Price(
            $priceResponse->matched->price->value,
            $cart->currency,
            $purchasable->getUnitQuantity()
        );

        $cartLine->unitPriceInclTax = new Price(
            $priceResponse->matched->priceIncTax()->value,
            $cart->currency,
            $purchasable->getUnitQuantity()
        );

        return $next($cartLine);
    }

    /**
     * Get product minimal price.
     *
     * @param  int  $qty
     * @return float
     */
    protected function getPriceIncrement($purchasable, $context)
    {
        $product = $purchasable->product;
        $customisations = $product->customisations->map(function ($item) use ($product) {
            $item->attribute = $product->customisable_attributes[$item->attribute_id];
            return $item;
        });
    }

    protected function getOptionIncrement($value, $option)
    {
        if (!$value) {
            return 0;
        }
        if (!is_array($value)) {
            $value = [$value];
        }
        return array_reduce($value, function ($acc, $_value) use ($option) {
            if (!is_array($option['attribute_data'])) {
                return $acc + ($option['attribute_data']['prefix'] === "+" ? $option['attribute_data']['price'] : -$option['attribute_data']['price']);
            }
            $selection = array_find($option['attribute_data'], fn($val) => $val['id'] === $_value);
            if (!$selection) {
                return $acc;
            }
            return $acc + ($selection['attribute_data']['prefix'] === "+" ? $selection['attribute_data']['price'] : -$selection['attribute_data']['price']);
        }, 0);
    }
}
