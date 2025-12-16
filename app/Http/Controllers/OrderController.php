<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockLog;
use App\OrderStatus;
use App\StockLogType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $orders = Order::query()
            ->with('user')
            ->withCount('items')
            ->when(auth()->user()->role === \App\UserRole::CASHIER, function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->when($request->search, function ($query, $search) {
                $query->where('order_number', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->payment_method, function ($query, $method) {
                $query->where('payment_method', $method);
            })
            ->when($request->date_from, function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
            'filters' => $request->only(['search', 'status', 'payment_method', 'date_from', 'date_to']),
        ]);
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(OrderRequest $request)
    {
        $this->authorize('create', Order::class);

        try {
            $order = DB::transaction(function () use ($request) {
                // Create order
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'subtotal' => $request->subtotal,
                    'tax' => $request->tax,
                    'discount' => $request->discount ?? 0,
                    'grand_total' => $request->grand_total,
                    'payment_method' => $request->payment_method,
                    'status' => OrderStatus::COMPLETED,
                ]);

                // Process each item
                foreach ($request->items as $item) {
                    // Lock product row for update
                    $product = Product::lockForUpdate()->find($item['product_id']);

                    if (!$product) {
                        throw new \Exception("Product not found.");
                    }

                    // Check stock availability
                    if (!$product->hasSufficientStock($item['quantity'])) {
                        throw new \Exception("Insufficient stock for {$product->name}. Only {$product->stock} available.");
                    }

                    // Create order item
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);

                    // Reduce stock
                    $product->reduceStock($item['quantity']);

                    // Create stock log
                    StockLog::create([
                        'product_id' => $product->id,
                        'user_id' => auth()->id(),
                        'type' => StockLogType::OUT,
                        'quantity' => -$item['quantity'],
                        'note' => "Order #{$order->order_number}",
                    ]);
                }

                return $order;
            });

            return redirect()->route('pos.index')
                ->with('success', "Order #{$order->order_number} created successfully!");

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['user', 'items.product']);

        return Inertia::render('Orders/Show', [
            'order' => $order,
        ]);
    }
}
