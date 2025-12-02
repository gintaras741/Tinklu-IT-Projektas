<?php

namespace App\Http\Controllers;

use App\Enums\PartType;
use App\Http\Requests\StoreBicycleRequest;
use App\Http\Requests\UpdateBicycleRequest;
use App\Models\Bicycle;
use App\Models\BicyclePart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MyBicycleController extends Controller
{
    /**
     * Display a listing of the user's bicycles.
     */
    public function index(): View
    {
        $bicycles = auth()->user()->bicycles()
            ->with(['components.part'])
            ->latest()
            ->get();

        return view('bicycles.index', compact('bicycles'));
    }

    /**
     * Show the form for creating a new bicycle.
     */
    public function create(): View
    {
        $partsByType = BicyclePart::orderBy('type')
            ->orderBy('name')
            ->get()
            ->groupBy('type');

        $types = PartType::cases();

        return view('bicycles.create', compact('partsByType', 'types'));
    }

    /**
     * Store a newly created bicycle in storage.
     */
    public function store(StoreBicycleRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // Create the bicycle
            $bicycle = auth()->user()->bicycles()->create([
                'name' => $request->name,
            ]);


            // Attach components
            foreach ($request->components as $component) {
                $bicycle->components()->create([
                    'bicycle_part_id' => $component['bicycle_part_id'],
                    'quantity' => $component['quantity'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('bicycles.index')
                ->with('status', 'Bicycle created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Failed to create bicycle: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified bicycle.
     */
    public function show(Bicycle $bicycle): View
    {
        // Ensure user owns this bicycle
        if ($bicycle->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this bicycle.');
        }

        $bicycle->load(['components.part']);

        return view('bicycles.show', compact('bicycle'));
    }

    /**
     * Show the form for editing the specified bicycle.
     */
    public function edit(Bicycle $bicycle): View
    {
        // Ensure user owns this bicycle
        if ($bicycle->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this bicycle.');
        }

        $bicycle->load('components.part');

        $partsByType = BicyclePart::orderBy('type')
            ->orderBy('name')
            ->get()
            ->groupBy('type');

        $types = PartType::cases();

        return view('bicycles.edit', compact('bicycle', 'partsByType', 'types'));
    }

    /**
     * Update the specified bicycle in storage.
     */
    public function update(UpdateBicycleRequest $request, Bicycle $bicycle): RedirectResponse
    {
        // Ensure user owns this bicycle
        if ($bicycle->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this bicycle.');
        }

        DB::beginTransaction();

        try {
            // Update bicycle name
            $bicycle->update([
                'name' => $request->name,
            ]);

            // Delete existing components
            $bicycle->components()->delete();

            // Create new components
            foreach ($request->components as $component) {
                $bicycle->components()->create([
                    'bicycle_part_id' => $component['bicycle_part_id'],
                    'quantity' => $component['quantity'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('bicycles.show', $bicycle)
                ->with('status', 'Bicycle updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Failed to update bicycle: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified bicycle from storage.
     */
    public function destroy(Bicycle $bicycle): RedirectResponse
    {
        // Ensure user owns this bicycle
        if ($bicycle->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this bicycle.');
        }

        $bicycle->delete();

        return redirect()
            ->route('bicycles.index')
            ->with('status', 'Bicycle deleted successfully!');
    }
}
