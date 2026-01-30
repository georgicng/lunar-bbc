<?php

use Illuminate\Support\Facades\Route;
use App\Services\ProductService;
use App\Services\OrderService;
use Inertia\Inertia;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;

Route::get('/', function () {
    return Inertia::render('Home');
});

Route::get('/categories', function () {
    $service = new ProductService();
    return Inertia::render('Category', [
        'categories' => CategoryResource::collection($service->getCategories()),
    ]);
});
Route::get('/products', function () {
    $service = new ProductService();
    return Inertia::render('Products', [
        'products' => ProductResource::collection($service->getProducts(request()->query('category', null))),
    ]);
});
Route::get('/products/:id', function ($id) {
    $service = new ProductService();
    return Inertia::render('Product', [
        'product' => $service->getProduct($id),
    ]);
});
Route::get('/cart', function () {
    $service = new OrderService();
    return Inertia::render('Cart', [
        'cart' => $service->getCart(),
    ]);
});
Route::get('/checkout', function () {
    $service = new OrderService();
    return Inertia::render('Checkout', [
        'cart' => $service->getCart(),
    ]);
});
Route::inertia('/success', 'Success');
