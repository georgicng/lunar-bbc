<?php

use Illuminate\Support\Facades\Route;
use App\Services\ProductService;
use App\Services\OrderService;
use Inertia\Inertia;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CartResource;
use Illuminate\Http\Request;
use App\Http\Requests\CartPostRequest;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return Inertia::render('Home');
});

Route::get('/categories', function (ProductService $service) {
    return Inertia::render('Category', [
        'categories' => CategoryResource::collection($service->getCategories()),
    ]);
});
Route::get('/products', function (ProductService $service) {
    return Inertia::render('Products', [
        'products' => ProductResource::collection($service->getProducts(request()->query('category', null))),
    ]);
});
Route::get('/products/{id}', function (int $id, ProductService $service) {
    return Inertia::render('Product', [
        'product' => new ProductResource($service->getProduct($id)),
    ]);
});
Route::post('/product/{id}/add-to-cart',  function (OrderService $orderService, ProductService $productService, CartPostRequest $request, int $id) {
    $orderService->addToCart(
        $productService->getVariant($id),
        $request->input('quantity'),
        $request->input('meta', [])
    );
    return to_route('cart');
});


Route::put('/cart/current/lines/{id}',  function (OrderService $orderService, CartPostRequest $request, int $id) {
    $orderService->updateCartItem(
        $id,
        $request->input('quantity'),
        $request->input('meta', [])
    );
    return to_route('cart');
});
Route::post('/cart/current/address',  function (OrderService $orderService, Request $request) {
    $orderService->setAddress(
        $request->input('address', [])
    );
    return to_route('cart');
});
Route::put('/cart/current/shipping',  function (OrderService $orderService, Request $request) {
    $orderService->setShipping(
        $request->input('shipping')
    );
    return to_route('cart');
});

Route::get('/cart', function (OrderService $service) {
    return Inertia::render('Cart', [
        'cart' => new CartResource($service->getCart()),
    ]);
})->name('cart');

Route::get('/checkout/{method}',  function (OrderService $orderService, string $method) {
    $orderService->fulfill(
        $method
    );
    return to_route('success');
});
Route::inertia('/success', 'Success')->name('success');


Route::controller(PaymentController::class)->prefix('paystack')->group(function () {
        Route::get('/redirect', 'redirect')->name('paystack.redirect');
        Route::get('/success', 'success')->name('paystack.success');
        Route::get('/cancel', 'cancel')->name('paystack.cancel');
        Route::get('/pay', 'popup')->name('paystack.popup');
    });
