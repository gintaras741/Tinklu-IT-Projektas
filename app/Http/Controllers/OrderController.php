<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Alert;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display user's orders
     */
    public function index(): View
    {
        $orders = Auth::user()->orders()
            ->with(['partItems.part', 'bicycleItems.bicycle'])
            ->latest('ordered_at')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show order details
     */
    public function show(Order $order): View
    {
        // Ensure user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['partItems.part', 'bicycleItems.bicycle.components.part', 'user']);

        return view('orders.show', compact('order'));
    }

    /**
     * Show checkout form
     */
    public function create(): View|RedirectResponse
    {
        $cart = Auth::user()->cart;

        if (!$cart || $cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $partItems = $cart->partItems()->with('part')->get();
        $bicycleItems = $cart->bicycleItems()->with('bicycle.components.part')->get();
        $total = $cart->getTotal();

        return view('orders.create', compact('cart', 'partItems', 'bicycleItems', 'total'));
    }

    /**
     * Store a new order
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cart = Auth::user()->cart;

        if (!$cart || $cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        DB::beginTransaction();

        try {
            // Validate stock availability
            foreach ($cart->partItems()->with('part')->get() as $item) {
                if (!$item->part->isInStock($item->amount)) {
                    DB::rollBack();
                    return back()->with('error', "Insufficient stock for part: {$item->part->name}");
                }
            }

            foreach ($cart->bicycleItems()->with('bicycle.components.part')->get() as $item) {
                foreach ($item->bicycle->components as $component) {
                    $requiredAmount = $component->quantity * $item->amount;
                    if (!$component->part->isInStock($requiredAmount)) {
                        DB::rollBack();
                        return back()->with('error', "Insufficient stock for part: {$component->part->name}");
                    }
                }
            }

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => 0, // Will be calculated
                'status' => OrderStatus::Pending,
                'shipping_address' => $request->shipping_address,
                'notes' => $request->notes,
                'ordered_at' => now(),
            ]);

            $totalAmount = 0;

            // Convert part items to order items
            foreach ($cart->partItems()->with('part')->get() as $item) {
                $priceAtPurchase = $item->part->price;
                $subtotal = $item->amount * $priceAtPurchase;
                
                DB::table('part_items')
                    ->where('cart_id', $item->cart_id)
                    ->where('bicycle_part_id', $item->bicycle_part_id)
                    ->update([
                        'order_id' => $order->id,
                        'price_at_purchase' => $priceAtPurchase,
                        'subtotal' => $subtotal,
                        'updated_at' => now(),
                    ]);

                // Reduce inventory
                $item->part->decrement('amount', $item->amount);
                
                $totalAmount += $subtotal;
            }

            // Convert bicycle items to order items
            foreach ($cart->bicycleItems()->with('bicycle.components.part')->get() as $item) {
                $priceAtPurchase = $item->bicycle->calculatePrice();
                $subtotal = $item->amount * $priceAtPurchase;
                
                DB::table('bicycle_items')
                    ->where('cart_id', $item->cart_id)
                    ->where('bicycle_id', $item->bicycle_id)
                    ->update([
                        'order_id' => $order->id,
                        'price_at_purchase' => $priceAtPurchase,
                        'subtotal' => $subtotal,
                        'updated_at' => now(),
                    ]);

                // Reduce inventory for each component
                foreach ($item->bicycle->components as $component) {
                    $component->part->decrement('amount', $component->quantity * $item->amount);
                }
                
                $totalAmount += $subtotal;
            }

            // Update order total
            $order->update(['total_amount' => $totalAmount]);

            // Create alert for user
            Alert::create([
                'user_id' => Auth::id(),
                'text' => "Your order #{$order->order_number} has been successfully placed! Total: €" . number_format($totalAmount, 2),
            ]);

            DB::commit();

            return redirect()->route('orders.show', $order)->with('status', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

    /**
     * Cancel an order
     */
    public function cancel(Order $order): RedirectResponse
    {
        // Ensure user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        if (!$order->canBeCancelled()) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        DB::beginTransaction();

        try {
            // Restore inventory for parts
            foreach ($order->partItems()->with('part')->get() as $item) {
                $item->part->increment('amount', $item->amount);
            }

            // Restore inventory for bicycles
            foreach ($order->bicycleItems()->with('bicycle.components.part')->get() as $item) {
                foreach ($item->bicycle->components as $component) {
                    $component->part->increment('amount', $component->quantity * $item->amount);
                }
            }

            // Update order status
            $order->update(['status' => OrderStatus::Cancelled]);

            // Create alert for user
            Alert::create([
                'user_id' => Auth::id(),
                'text' => "Your order #{$order->order_number} has been cancelled successfully.",
            ]);

            DB::commit();

            return back()->with('status', 'Order cancelled successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Failed to cancel order: ' . $e->getMessage());
        }
    }
}
