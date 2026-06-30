<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\BarangayOfficial;
use App\Models\DashboardSetting;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $banner    = DashboardSetting::get('banner_image');
        $officials = BarangayOfficial::orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');  // keys: 'barangay', 'sk'

        // Upcoming events: announcements that have an event_date >= today
        $upcomingEvents = Announcement::whereNotNull('event_date')
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')
            ->take(8)
            ->get();

        return view('admin.dashboard.index', [
            'residentCount'        => Resident::count(),
            'pendingResidentCount' => Resident::whereNull('verified_at')->count(),
            'adminCount'           => User::whereIn('role', ['secretary', 'chairman', 'kagawad'])->count(),
            'banner'               => $banner,
            'officials'            => $officials,
            'upcomingEvents'       => $upcomingEvents,
        ]);
    }

    // ── Banner Upload ─────────────────────────────────────────────

    public function uploadBanner(Request $request)
    {
        $request->validate([
            'banner' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
        ]);

        // Delete old banner if exists
        $old = DashboardSetting::get('banner_image');
        if ($old) {
            Storage::disk('public')->delete($old);
        }

        $path = $request->file('banner')->store('banners', 'public');
        DashboardSetting::set('banner_image', $path);

        return response()->json([
            'success' => true,
            'url'     => asset('storage/' . $path),
        ]);
    }

    // ── Banner Delete ─────────────────────────────────────────────

    public function deleteBanner()
    {
        $path = DashboardSetting::get('banner_image');
        if ($path) {
            Storage::disk('public')->delete($path);
            DashboardSetting::set('banner_image', null);
        }

        return response()->json(['success' => true]);
    }

    // ── Events JSON (for calendar + timeline AJAX) ────────────────

    public function events()
    {
        $events = Announcement::whereNotNull('event_date')
            ->orderBy('event_date')
            ->get()
            ->map(fn ($a) => [
                'id'          => $a->id,
                'title'       => $a->headline,
                'description' => $a->description,
                'date'        => $a->event_date->toDateString(),
                'start'       => $a->event_date->toDateString(),
            ]);

        return response()->json($events);
    }
}
