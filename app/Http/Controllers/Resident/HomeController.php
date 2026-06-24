<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $resident = $request->user()
            ->resident()
            ->with(['purok', 'verifiedBy'])
            ->firstOrFail();

        return view('home', [
            'resident' => $resident,
            'announcements' => Announcement::latest('event_date')->take(2)->get(),
        ]);
    }
}
