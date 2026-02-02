<?php

namespace App\Services;

use Lunar\Facades\CartSession;
use Lunar\Models\CartLine;

class OrderService
{
    public function getCart()
    {
        return CartSession::current();
    }

    public function addToCart(\Lunar\Base\Purchasable $purchasable, $quantity, $meta = []): void
    {

        if ($purchasable->stock < $quantity) {
            throw new \Exception('Not enough stock!');
        }

        CartSession::manager()->add($purchasable, $quantity, $meta);
    }

    public function updateCartItem($id, $quantity, $meta = []): void
    {
        $line = CartLine::find($id);
        if ($line->purchasable->stock < $quantity) {
            throw new \Exception('Not enough stock!');
        }
        CartSession::updateLine($id, $quantity, $meta);
    }

    public function setAddress(array $address = [
        'first_name' => null,
        'last_name' => null,
        'line_one' => null,
        'line_two' => null,
        'line_three' => null,
        'city' => null,
        'state' => null,
        'postcode' => null,
        'country_id' => null,
    ]): void
    {

        $cart = $this->getCart();
        $cart->setShippingAddress($address);
        $cart->setBillingAddress($address);
    }

    public function setShipping(\Lunar\DataTypes\ShippingOption $option): void
    {
        $cart = $this->getCart();
        $cart->setShippingOption($option);
    }

    public function fulfill($driver)
    {
        $token = "test_card_token_visa_4242";
        $driver = \Lunar\Facades\Payments::driver($driver);
        $driver->cart($this->getCart());
        $driver->withData([
            'payment_token' => $token,
        ]);
        $driver->authorize();
    }
}
