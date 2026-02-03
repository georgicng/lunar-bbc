<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\City;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $cart = $this->calculate();
        return [
            "id" => $this->id,
            "lines" => $cart->lines->map(function ($line) {
                return [
                    'id' => $line->id,
                    'identifier' => $line->purchasable->getIdentifier(),
                    'quantity' => $line->quantity,
                    'name' => $line->purchasable->getDescription(),
                    'image' => $line->purchasable->product->getMedia('images')->first()?->getUrl(),
                    'option' => $line->purchasable->getOption(),
                    'options' => $line->purchasable->getOptions()->implode(' / '),
                    'subTotal' => $line->subTotal->formatted(),
                    'unitPrice' => $line->unitPrice->formatted(),
                    "line" => $line
                ];
            }),
            "addresses" => [
                "shippingAddress" => $this->shippingAddress,
                "billingAddress" => $this->billingAddress
            ],
            "totals" => [
                "total" => $this->total?->formatted(),
                "subTotal" => $this->subTotal?->formatted(), // The cart sub total, excluding tax
                "subTotalDiscounted" => $this->subTotalDiscounted?->formatted(), // The cart sub total, minus the discount amount.
                "shippingTotal" => $this->shippingTotal?->formatted(), // The monetary value for the shipping total. (if applicable)
                "taxTotal" => $this->taxTotal?->formatted(), // The monetary value for the amount of tax applied.
                "taxBreakdown" => $this->taxBreakdown, // This is a collection of all taxes applied across all lines.
                "discountTotal" => $this->discountTotal?->formatted(), // The monetary value for the discount total.
                "discountBreakdown" => $this->discountBreakdown, // This is a collection of how discounts were calculated
                "shippingSubTotal" => $this->shippingSubTotal?->formatted(), // The shipping total, excluding tax.
                //"shippingTotal" => $this->shippingTotal, // The shipping total including tax.
                "shippingBreakdown" =>  $this->shippingBreakdown, // This is a collection of the shipping breakdown for the cart.
            ],
            "shippingMethods" => \Lunar\Facades\ShippingManifest::getOptions(\Lunar\Facades\CartSession::current()),
            "paymentMethods" =>  [
                ["id" => "cash-in-hand", "name" => "Payment on Delivery"],
                ["id" => "paystack", "name" => "Pay with Card"],
                ["id" => "teller", "name" => "Bank Transfer"]
            ],
            "meta" => [
                "cities" => City::get()
            ],
            "shipping" => $this->getShippingOption()?->identifier

        ];
    }
}
