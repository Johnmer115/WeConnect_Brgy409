<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard.index', [
            'residentCount' => Resident::count(),
            'pendingResidentCount' => Resident::whereNull('verified_at')->count(),
            'adminCount' => User::whereIn('role', ['secretary', 'chairman', 'kagawad'])->count(),
            'recentResidents' => Resident::with('purok')->latest()->take(5)->get(),
        ]);
    }
}
