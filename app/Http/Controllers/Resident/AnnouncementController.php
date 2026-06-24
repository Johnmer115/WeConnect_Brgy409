<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function __invoke(Request $request)
    {
        $resident = $request->user()
            ->resident()
            ->with(['purok', 'verifiedBy'])
            ->firstOrFail();

        return view('resident.announcements', [
            'resident' => $resident,
            'announcements' => Announcement::latest('event_date')->paginate(8),
        ]);
    }
}
