<?php

namespace App\Http\Controllers;

use App\Models\Bicycle;
use App\Models\BicyclePart;
use App\Models\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Display the cart contents
     */
    public function index(): View
    {
        $cart = $this->getOrCreateCart();
        
        $partItems = $cart->partItems()->with('part')->get();
        $bicycleItems = $cart->bicycleItems()->with('bicycle.components.part')->get();
        
        $total = $cart->getTotal();

        return view('cart.index', compact('cart', 'partItems', 'bicycleItems', 'total'));
    }

    /**
     * Add a part to the cart
     */
    public function addPart(BicyclePart $part, Request $request): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $part->amount
        ]);

        if (!$part->isInStock($request->quantity)) {
            return back()->with('error', 'Insufficient stock for this part.');
        }

        $cart = $this->getOrCreateCart();

        // Check if part already in cart
        $existingItem = $cart->partItems()->where('bicycle_part_id', $part->id)->first();

        if ($existingItem) {
            $newAmount = $existingItem->amount + $request->quantity;
            if (!$part->isInStock($newAmount)) {
                return back()->with('error', 'Cannot add more. Insufficient stock.');
            }
            $cart->partItems()
                ->where('bicycle_part_id', $part->id)
                ->update(['amount' => $newAmount]);
        } else {
            $cart->partItems()->create([
                'bicycle_part_id' => $part->id,
                'amount' => $request->quantity,
            ]);
        }

        return back()->with('status', 'Part added to cart!');
    }

    /**
     * Add a bicycle to the cart
     */
    public function addBicycle(Bicycle $bicycle, Request $request): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // Check if all components are in stock
        foreach ($bicycle->components as $component) {
            $requiredAmount = $component->quantity * $request->quantity;
            if (!$component->part->isInStock($requiredAmount)) {
                return back()->with('error', "Insufficient stock for part: {$component->part->name}");
            }
        }

        $cart = $this->getOrCreateCart();

        // Check if bicycle already in cart
        $existingItem = $cart->bicycleItems()->where('bicycle_id', $bicycle->id)->first();

        if ($existingItem) {
            $newAmount = $existingItem->amount + $request->quantity;
            // Re-check stock with new amount
            foreach ($bicycle->components as $component) {
                $requiredAmount = $component->quantity * $newAmount;
                if (!$component->part->isInStock($requiredAmount)) {
                    return back()->with('error', 'Cannot add more. Insufficient stock.');
                }
            }
            $cart->bicycleItems()
                ->where('bicycle_id', $bicycle->id)
                ->update(['amount' => $newAmount]);
        } else {
            $cart->bicycleItems()->create([
                'bicycle_id' => $bicycle->id,
                'amount' => $request->quantity,
            ]);
        }

        return back()->with('status', 'Bicycle added to cart!');
    }

    /**
     * Update part quantity in cart
     */
    public function updatePart(Request $request, $cartId, $partId): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = $this->getOrCreateCart();
        $item = $cart->partItems()->where('bicycle_part_id', $partId)->firstOrFail();

        if (!$item->part->isInStock($request->quantity)) {
            return back()->with('error', 'Insufficient stock.');
        }

        $cart->partItems()
            ->where('bicycle_part_id', $partId)
            ->update(['amount' => $request->quantity]);

        return back()->with('status', 'Cart updated!');
    }

    /**
     * Update bicycle quantity in cart
     */
    public function updateBicycle(Request $request, $cartId, $bicycleId): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = $this->getOrCreateCart();
        $item = $cart->bicycleItems()->where('bicycle_id', $bicycleId)->firstOrFail();

        // Check stock for all components
        foreach ($item->bicycle->components as $component) {
            $requiredAmount = $component->quantity * $request->quantity;
            if (!$component->part->isInStock($requiredAmount)) {
                return back()->with('error', "Insufficient stock for part: {$component->part->name}");
            }
        }

        $cart->bicycleItems()
            ->where('bicycle_id', $bicycleId)
            ->update(['amount' => $request->quantity]);

        return back()->with('status', 'Cart updated!');
    }

    /**
     * Remove part from cart
     */
    public function removePart($cartId, $partId): RedirectResponse
    {
        $cart = $this->getOrCreateCart();
        
        if ($cart->id != $cartId) {
            abort(403);
        }

        $cart->partItems()
            ->where('bicycle_part_id', $partId)
            ->delete();

        return back()->with('status', 'Item removed from cart.');
    }

    /**
     * Remove bicycle from cart
     */
    public function removeBicycle($cartId, $bicycleId): RedirectResponse
    {
        $cart = $this->getOrCreateCart();
        
        // Ensure this is the user's cart
        if ($cart->id != $cartId) {
            abort(403);
        }

        $cart->bicycleItems()
            ->where('bicycle_id', $bicycleId)
            ->delete();

        return back()->with('status', 'Item removed from cart.');
    }

    /**
     * Clear the entire cart
     */
    public function clear(): RedirectResponse
    {
        $cart = $this->getOrCreateCart();
        $cart->partItems()->delete();
        $cart->bicycleItems()->delete();

        return redirect()->route('cart.index')->with('status', 'Cart cleared.');
    }

    /**
     * Get or create cart for the authenticated user
     */
    private function getOrCreateCart(): Cart
    {
        $user = Auth::user();
        return $user->cart ?? $user->cart()->create();
    }
}
