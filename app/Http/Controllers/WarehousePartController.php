<?php

namespace App\Http\Controllers;

use App\Enums\PartType;
use App\Models\BicyclePart;
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
}
