<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;

class POSController extends Controller
{
    /**
     * Display the POS interface.
     */
    public function index()
    {
        $categories = Category::active()->orderBy('name')->get();
        
        $products = Product::with('category')
            ->active()
            ->inStock()
            ->orderBy('name')
            ->get();

        return Inertia::render('POS/Index', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}
