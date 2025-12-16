<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockAdjustmentRequest;
use App\Models\Product;
use App\Models\StockLog;
use App\StockLogType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class InventoryController extends Controller
{
    /**
     * Display inventory management page.
     */
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('stock', 'asc')
            ->paginate(20)
            ->withQueryString();

        $stockLogs = StockLog::with(['product', 'user'])
            ->when($request->product_id, function ($query, $productId) {
                $query->where('product_id', $productId);
            })
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return Inertia::render('Inventory/Index', [
            'products' => $products,
            'stock_logs' => $stockLogs,
            'filters' => $request->only(['search', 'product_id', 'type']),
        ]);
    }

    /**
     * Adjust product stock.
     */
    public function adjust(StockAdjustmentRequest $request)
    {
        // Only Admin and Owner can adjust stock
        if (!in_array(auth()->user()->role, [\App\UserRole::ADMIN, \App\UserRole::OWNER])) {
            abort(403, 'Only administrators and owners can adjust stock.');
        }

        try {
            DB::transaction(function () use ($request) {
                $product = Product::lockForUpdate()->find($request->product_id);

                $oldStock = $product->stock;

                switch ($request->type) {
                    case StockLogType::IN->value:
                        $product->increaseStock($request->quantity);
                        $logQuantity = $request->quantity;
                        break;

                    case StockLogType::OUT->value:
                        if (!$product->hasSufficientStock($request->quantity)) {
                            throw new \Exception("Insufficient stock. Only {$product->stock} available.");
                        }
                        $product->reduceStock($request->quantity);
                        $logQuantity = -$request->quantity;
                        break;

                    case StockLogType::ADJUSTMENT->value:
                        $logQuantity = $request->quantity - $oldStock;
                        $product->update(['stock' => $request->quantity]);
                        break;

                    default:
                        throw new \Exception("Invalid stock adjustment type.");
                }

                StockLog::create([
                    'company_id' => auth()->user()->company_id,
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'type' => $request->type,
                    'quantity' => $logQuantity,
                    'note' => $request->note,
                ]);
            });

            return back()->with('success', 'Stock adjusted successfully.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
