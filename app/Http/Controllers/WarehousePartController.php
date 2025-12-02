<?php

namespace App\Http\Controllers;

use App\Enums\PartType;
use App\Models\BicyclePart;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\StoreBicyclePartRequest;
use App\Http\Requests\UpdateBicyclePartRequest;
use Illuminate\Http\UploadedFile;

class WarehousePartController extends Controller
{
    /**
     * Display a listing of the bicycle parts in storage.
     */
    public function index(): View
    {
        $parts = BicyclePart::orderBy('name')->paginate(20);
        return view('warehouse.index', compact('parts'));
    }

    /**
     * Show the form for creating a new part.
     */
    public function create(): View
    {
        $types = PartType::values();
        return view('warehouse.create', compact('types'));
    }

    /**
     * Store a newly created part in storage.
     */
    public function store(StoreBicyclePartRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Only handle when an actual file was uploaded
        /** @var UploadedFile|null $image */
        $image = $request->file('image');
        if ($image instanceof UploadedFile) {
            $data['image'] = $image->store('parts', 'public');
        }

        BicyclePart::create($data);

        return redirect()->route('warehouse.index')->with('status', 'Part created');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BicyclePart $bicycle_part): View
    {
        $types = PartType::values();
        return view('warehouse.edit', ['part' => $bicycle_part, 'types' => $types]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBicyclePartRequest $request, BicyclePart $bicycle_part): RedirectResponse
    {
        $data = $request->validated();

        // Only replace when a new file is provided
        /** @var UploadedFile|null $image */
        $image = $request->file('image');
        if ($image instanceof UploadedFile) {
            if ($bicycle_part->image) {
                Storage::disk('public')->delete($bicycle_part->image);
            }
            $data['image'] = $image->store('parts', 'public');
        }

        $bicycle_part->update($data);

        return redirect()->route('warehouse.index')->with('status', 'Part updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BicyclePart $bicycle_part): RedirectResponse
    {
        if ($bicycle_part->image) {
            Storage::disk('public')->delete($bicycle_part->image);
        }
        $bicycle_part->delete();
        return redirect()->route('warehouse.index')->with('status', 'Part deleted');
    }

    /**
     * Show orders that need restocking
     */
    public function restocking(): View
    {
        // Get pending and processing orders with out of stock items
        $ordersNeedingRestock = Order::whereIn('status', [OrderStatus::Pending, OrderStatus::Processing])
            ->where('notes', 'like', '%[TRŪKSTA SANDĖLYJE]%')
            ->with(['user', 'partItems.part', 'bicycleItems.bicycle'])
            ->orderBy('ordered_at', 'asc')
            ->get();

        // Aggregate needed parts
        $partsNeeded = [];
        
        foreach ($ordersNeedingRestock as $order) {
            $stockInfo = $order->getOutOfStockInfo();
            if (!$stockInfo) continue;
            
            // Parse the stock info to extract part needs
            preg_match_all('/-\s*([^:]+):\s*reikia\s*(\d+),\s*sandėlyje\s*(\d+),\s*trūksta\s*(\d+)/u', $stockInfo, $matches, PREG_SET_ORDER);
            
            foreach ($matches as $match) {
                $partName = trim($match[1]);
                // Remove bicycle name in parentheses if present
                $partName = preg_replace('/\s*\(dviračiui[^)]+\)\s*/u', '', $partName);
                
                $shortage = (int)$match[4];
                
                if (!isset($partsNeeded[$partName])) {
                    $partsNeeded[$partName] = [
                        'name' => $partName,
                        'total_shortage' => 0,
                        'orders' => []
                    ];
                }
                
                $partsNeeded[$partName]['total_shortage'] += $shortage;
                $partsNeeded[$partName]['orders'][] = $order->order_number;
            }
        }
        
        // Sort by total shortage descending
        uasort($partsNeeded, function($a, $b) {
            return $b['total_shortage'] - $a['total_shortage'];
        });

        return view('warehouse.restocking', [
            'ordersNeedingRestock' => $ordersNeedingRestock,
            'partsNeeded' => $partsNeeded
        ]);
    }
}
