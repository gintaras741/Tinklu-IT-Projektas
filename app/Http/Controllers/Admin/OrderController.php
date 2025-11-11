<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display all orders
     */
    public function index(Request $request): View
    {
        $query = Order::with('user');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('ordered_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('ordered_at', '<=', $request->date_to);
        }

        // Search by order number or user name
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $orders = $query->latest('ordered_at')->paginate(20);
        $statuses = OrderStatus::cases();

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    /**
     * Show order details
     */
    public function show(Order $order): View
    {
        $order->load(['partItems.part', 'bicycleItems.bicycle.components.part', 'user']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Order $order, Request $request): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', OrderStatus::values()),
        ]);

        $newStatus = OrderStatus::from($request->status);
        $oldStatus = $order->status;

        // Validate status transition
        if ($oldStatus === OrderStatus::Cancelled) {
            return back()->with('error', 'Cannot change status of a cancelled order.');
        }

        if ($oldStatus === OrderStatus::Delivered) {
            return back()->with('error', 'Cannot change status of a delivered order.');
        }

        DB::beginTransaction();

        try {
            // If cancelling, restore inventory
            if ($newStatus === OrderStatus::Cancelled) {
                foreach ($order->partItems()->with('part')->get() as $item) {
                    $item->part->increment('amount', $item->amount);
                }

                foreach ($order->bicycleItems()->with('bicycle.components.part')->get() as $item) {
                    foreach ($item->bicycle->components as $component) {
                        $component->part->increment('amount', $component->quantity * $item->amount);
                    }
                }
            }

            $order->update(['status' => $newStatus]);

            // Create alert for user
            Alert::create([
                'user_id' => $order->user_id,
                'text' => "Your order #{$order->order_number} status has been updated to: {$newStatus->label()}",
            ]);

            DB::commit();

            return back()->with('status', 'Order status updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Failed to update order status: ' . $e->getMessage());
        }
    }

    /**
     * Show order statistics
     */
    public function statistics(): View
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', OrderStatus::Pending)->count();
        $processingOrders = Order::where('status', OrderStatus::Processing)->count();
        $shippedOrders = Order::where('status', OrderStatus::Shipped)->count();
        $deliveredOrders = Order::where('status', OrderStatus::Delivered)->count();
        $cancelledOrders = Order::where('status', OrderStatus::Cancelled)->count();

        $totalRevenue = Order::whereIn('status', [
            OrderStatus::Processing,
            OrderStatus::Shipped,
            OrderStatus::Delivered
        ])->sum('total_amount');

        $recentOrders = Order::with('user')
            ->latest('ordered_at')
            ->limit(10)
            ->get();

        return view('admin.orders.statistics', compact(
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'shippedOrders',
            'deliveredOrders',
            'cancelledOrders',
            'totalRevenue',
            'recentOrders'
        ));
    }
}
