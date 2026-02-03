<?php

namespace App\Pipelines\CartLine;

use Closure;
use Lunar\DataTypes\Price;
use Lunar\Facades\Pricing;
use Lunar\Models\CartLine;
use Lunar\Models\Contracts\CartLine as CartLineContract;
use Spatie\LaravelBlink\BlinkFacade as Blink;
use App\Lib\RuleEvaluator;
use Illuminate\Support\Arr;

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
        $increment = $this->getPriceIncrement($purchasable->product, $cartLine->meta['customisations']);
        //logger()->info(json_encode(['increment' => $increment, 'unit' => $cartLine->unitPrice->value]));

        $cartLine->unitPrice = new Price(
            $cartLine->unitPrice->value + ($increment * 100), //TODO: find a better way to do this
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
    protected function getPriceIncrement($product, $context)
    {
        $total = 0;
        $customisations = $product->customisations->map(function ($item) use ($product) {
            $item->attribute = $product->customisable_attributes[$item->attribute_id];
            return $item;
        });


        foreach ($customisations as $customisation) {
            $value = $context[$customisation->attribute_id] ?? null;
            $total += $this->getOptionIncrement($value, $customisation);
        }
        return $total;
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
            if (Arr::isAssoc($option['attribute_data'])) {
                $prefix = $option['attribute_data']['prefix'] ?? "+";
                $price = $option['attribute_data']['price'];
                return $acc + intval($prefix === "+" ? $price : -$price);
            }
            $selection = collect($option['attribute_data'])->first(fn($val) => $val['id'] === $_value);
            if (!$selection) {
                return $acc;
            }
            return $acc + intval($selection['prefix'] === "+" ? $selection['price'] : -$selection['price']);
        }, 0);
    }
}
