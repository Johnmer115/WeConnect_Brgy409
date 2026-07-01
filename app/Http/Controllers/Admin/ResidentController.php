<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purok;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResidentController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        return view('admin.management.resisdent.index', [
            'residents' => Resident::with(['purok', 'user'])
                ->when($search !== '', function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('mobile_number', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                })
                ->latest()
                ->paginate(10)
                ->withQueryString(),
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('admin.management.resisdent.create', [
            'resident' => new Resident(),
            'puroks' => $this->barangayPuroks(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatedResident($request);

        $validated['verified_at'] = now();
        $validated['verified_by'] = $request->user()->id;

        $resident = Resident::create($validated);

        \App\Models\ActivityLog::log('create', 'Residents', "Added resident record: " . $resident->full_name);

        return redirect()
            ->route('admin.residents.index', $resident) 
            ->with('success', 'Resident registered successfully.');
    }

    public function show(Resident $resident)
    {
        return view('admin.management.resisdent.show', [
            'resident' => $resident->load(['purok', 'user']),
        ]);
    }

    public function edit(Resident $resident)
    {
        return view('admin.management.resisdent.edit', [
            'resident' => $resident,
            'puroks' => $this->barangayPuroks(),
        ]);
    }

    public function update(Request $request, Resident $resident)
    {
        $resident->update($this->validatedResident($request));

        \App\Models\ActivityLog::log('update', 'Residents', "Updated resident record: " . $resident->full_name);

        return redirect()
            ->route('admin.residents.index', $resident)
            ->with('success', 'Resident updated successfully.');
    }

    public function destroy(Resident $resident)
    {
        $residentName = $resident->full_name;

        DB::transaction(function () use ($resident) {
            if ($resident->user) {
                $resident->user->delete();
            }

            $resident->delete();
        });

        \App\Models\ActivityLog::log('delete', 'Residents', "Deleted resident record: " . $residentName);

        return redirect()
            ->route('admin.residents.index')
            ->with('success', 'Resident deleted successfully.');
    }

    public function verify(Request $request, Resident $resident)
    {
        DB::transaction(function () use ($request, $resident) {
            if (! $resident->verified_at) {
                $resident->forceFill([
                    'verified_at' => now(),
                    'verified_by' => $request->user()->id,
                ])->save();
            }

            if ($resident->user && $resident->user->status !== 'active') {
                $resident->user->forceFill(['status' => 'active'])->save();
            }
        });

        \App\Models\ActivityLog::log('update', 'Residents', "Verified resident registration: " . $resident->full_name);

        return back()->with('success', 'Resident registration accepted.');
    }

    private function barangayPuroks()
    {
        return Purok::orderBy('id')->get();
    }

    private function validatedResident(Request $request): array
    {
        $validated = $request->validate([
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['required', 'date', 'before_or_equal:today'],
            'blood_type' => ['nullable', 'string', 'max:5'],
            'gender' => ['required', 'in:Male,Female'],
            'religion' => ['nullable', 'string', 'max:255'],
            'health_status' => ['required', 'in:Alive,Deceased'],
            'date_deceased' => ['nullable', 'required_if:health_status,Deceased', 'date', 'after_or_equal:date_of_birth'],
            'is_4ps' => ['nullable', 'boolean'],
            'is_pwd' => ['nullable', 'boolean'],
            'is_voter' => ['nullable', 'boolean'],
            'is_single_parent' => ['nullable', 'boolean'],
            'email' => ['nullable', 'email', 'max:255'],
            'telephone_number' => ['nullable', 'string', 'max:50'],
            'mobile_number' => ['nullable', 'string', 'max:50'],
            'home_address' => ['nullable', 'string'],
            'purok_id' => ['nullable', 'exists:puroks,id'],
        ]);

        foreach (['is_4ps', 'is_pwd', 'is_voter', 'is_single_parent'] as $field) {
            $validated[$field] = $request->boolean($field);
        }

        if ($validated['health_status'] === 'Alive') {
            $validated['date_deceased'] = null;
        }

        return $validated;
    }
}
