<?php

namespace App\Services;

use Lunar\Facades\CartSession;
use Lunar\Models\CartLine;
use Lunar\Models\Order;
use \Lunar\Facades\Payments;

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

        CartSession::add($purchasable, $quantity, $meta)->calculate();
    }

    public function updateCartItem($id, $quantity, $meta = []): void
    {
        $line = CartLine::find($id);
        if ($line->purchasable->stock < $quantity) {
            throw new \Exception('Not enough stock!');
        }
        CartSession::updateLine($id, $quantity, $meta)->calculate();
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
        'contact_email' => null,
        'contact_phone' => null,
    ]): void
    {

        $cart = $this->getCart();
        $cart->setShippingAddress($address);
        $cart->setBillingAddress($address);
    }

    public function setShipping(string $id): void
    {
        $cart = $this->getCart();
        $option = \Lunar\Facades\ShippingManifest::getOption($cart, $id);
        $cart->setShippingOption($option);
    }

    public function placeOrder($driver)
    {
        $driver = Payments::driver($driver);
        $driver->cart($this->getCart());
        return [$driver->authorize(), $driver];
    }

    public function processPayment(string $id, string $transactionId, string $driver): void
    {
        $order = Order::find($id);
        $transaction = $order->transactions()->where('id', $transactionId)->first();
        $driver = Payments::driver($driver);
        $driver->order($order);
        $driver->capture($transaction);
    }

    public function changePayment(string $id): void
    {
        $cart = $this->getCart();
        $option = \Lunar\Facades\ShippingManifest::getOption($cart, $id);
        $cart->setShippingOption($option);
    }
}
