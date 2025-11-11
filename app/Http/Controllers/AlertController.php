<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlertController extends Controller
{
    /**
     * Display all alerts for the authenticated user
     */
    public function index(): View
    {
        $alerts = Alert::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        // Mark unread alerts as read when viewing the page
        Alert::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('alerts.index', compact('alerts'));
    }

    /**
     * Mark alert as read (delete it)
     */
    public function destroy(Alert $alert)
    {
        // Ensure user can only delete their own alerts
        if ($alert->user_id !== auth()->id()) {
            abort(403);
        }

        $alert->delete();

        return back()->with('status', 'Alert dismissed successfully.');
    }

    /**
     * Mark all alerts as read (delete all)
     */
    public function destroyAll()
    {
        Alert::where('user_id', auth()->id())->delete();

        return back()->with('status', 'All alerts dismissed successfully.');
    }
}
