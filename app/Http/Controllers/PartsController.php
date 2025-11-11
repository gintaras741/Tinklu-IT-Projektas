<?php

namespace App\Http\Controllers;

use App\Models\BicyclePart;
use Illuminate\View\View;

class PartsController extends Controller
{
    /**
     * Display available parts for purchase
     */
    public function index(): View
    {
        $parts = BicyclePart::orderBy('type')
            ->orderBy('name')
            ->paginate(12);

        return view('parts.index', compact('parts'));
    }
}
