<?php
namespace App\Http\Controllers;
use App\Services\ProductService;
use App\Http\Resources\CategoryResource;
use Inertia\Inertia;

class ProductsController extends Controller
{
    public function index(ProductService $service)
    {
        return Inertia::render('Category', [
        'categories' => CategoryResource::collection($service->getCategories()),
    ]);
    }
}
