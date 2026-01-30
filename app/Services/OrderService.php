<?php
namespace App\Services;

class OrderService
{
    public function getCart()
    {
        return \Lunar\Facades\CartSession::current();
    }
}
