<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display customer menu page with available products for a specific company.
     */
    public function index(Request $request, \App\Models\Company $company)
    {
        $query = Product::query()
            ->where('company_id', $company->id)
            ->where('is_active', true)
            ->where('stock', '>', 0);

        // Filter by category if provided
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Search by product name
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $products = $query->orderBy('category_id')
            ->orderBy('name')
            ->get();

        // Get all categories for this company
        $categories = \App\Models\Category::where('company_id', $company->id)
            ->orderBy('name')
            ->get();

        return Inertia::render('Menu/Index', [
            'company' => $company,
            'products' => $products,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category_id']),
        ]);
    }
}
