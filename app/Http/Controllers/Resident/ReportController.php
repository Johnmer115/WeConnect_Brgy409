<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private const KINDS = [
        'voter' => 'Voter',
        'senior' => 'Senior Citizen',
        'youth' => 'Youth Citizen',
        '4ps' => '4Ps Member',
        'pwd' => 'PWD',
    ];

    public function __invoke(Request $request)
    {
        $selectedKinds = collect((array) $request->query('kind'))
            ->filter(fn ($kind) => array_key_exists($kind, self::KINDS))
            ->values()
            ->all();

        $residents = Resident::with('purok')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->map(function (Resident $resident) {
                $resident->report_kinds = $this->residentKinds($resident);

                return $resident;
            })
            ->filter(function (Resident $resident) use ($selectedKinds) {
                if ($selectedKinds === []) {
                    return $resident->report_kinds !== [];
                }

                return collect($selectedKinds)->some(fn ($kind) => array_key_exists($kind, $resident->report_kinds));
            })
            ->values();

        return view('resident.reports', [
            'residents' => $residents,
            'kinds' => self::KINDS,
            'selectedKinds' => $selectedKinds,
        ]);
    }

    private function residentKinds(Resident $resident): array
    {
        $kinds = [];

        if ($resident->is_voter) {
            $kinds['voter'] = self::KINDS['voter'];
        }

        if ($resident->age >= 60) {
            $kinds['senior'] = self::KINDS['senior'];
        }

        if ($resident->age >= 15 && $resident->age <= 30) {
            $kinds['youth'] = self::KINDS['youth'];
        }

        if ($resident->is_4ps) {
            $kinds['4ps'] = self::KINDS['4ps'];
        }

        if ($resident->is_pwd) {
            $kinds['pwd'] = self::KINDS['pwd'];
        }

        return $kinds;
    }
}
