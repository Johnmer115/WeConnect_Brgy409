<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    // ── Index (Table) ─────────────────────────────────────────────

    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $type   = $request->query('type', '');
        $status = $request->query('status', '');  // '' = default (pending+ongoing)

        $query = Certificate::query()->latest();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%");
            });
        }

        if ($type && array_key_exists($type, Certificate::TYPES)) {
            $query->where('certificate_type', $type);
        }

        if ($status && array_key_exists($status, Certificate::STATUSES)) {
            $query->where('status', $status);
        } else {
            // Default: hide completed
            $query->whereIn('status', ['pending', 'ongoing']);
        }

        return view('admin.management.certificates.index', [
            'certificates'   => $query->paginate(10)->withQueryString(),
            'types'          => Certificate::TYPES,
            'statuses'       => Certificate::STATUSES,
            'selectedType'   => $type,
            'selectedStatus' => $status,
            'search'         => $search,
        ]);
    }

    // ── Create ────────────────────────────────────────────────────

    public function create()
    {
        return view('admin.management.certificates.create', [
            'types'    => Certificate::TYPES,
            'statuses' => Certificate::STATUSES,
        ]);
    }

    // ── Store ─────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id'      => 'nullable|exists:residents,id',
            'last_name'        => 'required|string|max:100',
            'first_name'       => 'required|string|max:100',
            'middle_name'      => 'nullable|string|max:100',
            'suffix'           => 'nullable|string|max:20',
            'date_of_birth'    => 'nullable|date',
            'age'              => 'nullable|integer|min:0|max:150',
            'gender'           => 'nullable|in:Male,Female',
            'religion'         => 'nullable|string|max:100',
            'address'          => 'required|string|max:255',
            'purok'            => 'nullable|string|max:100',
            'barangay_city'    => 'nullable|string|max:100',
            'country'          => 'nullable|string|max:100',
            'email'            => 'nullable|email|max:150',
            'telephone'        => 'nullable|string|max:50',
            'mobile'           => 'nullable|string|max:50',
            'certificate_type' => 'required|in:' . implode(',', array_keys(Certificate::TYPES)),
            'purpose'          => 'nullable|string|max:255',
        ]);

        $validated['status'] = 'pending';

        $certificate = Certificate::create($validated);

        \App\Models\ActivityLog::log('create', 'Certificates', "Issued new " . $certificate->type_label . " for " . $certificate->full_name);

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate issued successfully.');
    }

    // ── Edit ──────────────────────────────────────────────────────

    public function edit(Certificate $certificate)
    {
        return view('admin.management.certificates.edit', [
            'certificate' => $certificate,
            'types'       => Certificate::TYPES,
            'statuses'    => Certificate::STATUSES,
        ]);
    }

    // ── Update ────────────────────────────────────────────────────

    public function update(Request $request, Certificate $certificate)
    {
        $validated = $request->validate([
            'resident_id'      => 'nullable|exists:residents,id',
            'last_name'        => 'required|string|max:100',
            'first_name'       => 'required|string|max:100',
            'middle_name'      => 'nullable|string|max:100',
            'suffix'           => 'nullable|string|max:20',
            'date_of_birth'    => 'nullable|date',
            'age'              => 'nullable|integer|min:0|max:150',
            'gender'           => 'nullable|in:Male,Female',
            'religion'         => 'nullable|string|max:100',
            'address'          => 'required|string|max:255',
            'purok'            => 'nullable|string|max:100',
            'barangay_city'    => 'nullable|string|max:100',
            'country'          => 'nullable|string|max:100',
            'email'            => 'nullable|email|max:150',
            'telephone'        => 'nullable|string|max:50',
            'mobile'           => 'nullable|string|max:50',
            'certificate_type' => 'required|in:' . implode(',', array_keys(Certificate::TYPES)),
            'purpose'          => 'nullable|string|max:255',
            'status'           => 'required|in:pending,ongoing,completed',
        ]);

        $certificate->update($validated);

        \App\Models\ActivityLog::log('update', 'Certificates', "Updated certificate details for " . $certificate->full_name);

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate updated successfully.');
    }

    // ── Update Status (AJAX) ──────────────────────────────────────

    public function destroy(Certificate $certificate)
    {
        $certName = $certificate->full_name;
        $certTypeLabel = $certificate->type_label;

        $certificate->delete();

        \App\Models\ActivityLog::log('delete', 'Certificates', "Deleted certificate record (" . $certTypeLabel . ") of " . $certName);

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate deleted successfully.');
    }

    public function updateStatus(Request $request, Certificate $certificate)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,ongoing,completed',
        ]);

        $data = ['status' => $validated['status']];

        // If marking completed, stamp the issued_at and issued_by
        if ($validated['status'] === 'completed') {
            $data['issued_at'] = now();
            $data['issued_by'] = Auth::id();
        }

        $certificate->update($data);

        \App\Models\ActivityLog::log('update', 'Certificates', "Updated status of certificate (" . $certificate->type_label . ") for " . $certificate->full_name . " to " . $certificate->status_label);

        return response()->json([
            'success'      => true,
            'status'       => $certificate->status,
            'status_label' => $certificate->status_label,
        ]);
    }

    // ── Print ─────────────────────────────────────────────────────

    public function print(Certificate $certificate)
    {
        return view('admin.management.certificates.print', [
            'certificate' => $certificate,
        ]);
    }

    // ── Resident Lookup (AJAX) ────────────────────────────────────

    public function residentLookup(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $query = Resident::with('purok');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%");
            });
        } else {
            $query->latest();
        }

        $residents = $query->limit(10)
            ->get()
            ->map(fn (Resident $r) => [
                'id'           => $r->id,
                'full_name'    => $r->full_name,
                'last_name'    => $r->last_name,
                'first_name'   => $r->first_name,
                'middle_name'  => $r->middle_name ?? '',
                'suffix'       => $r->suffix ?? '',
                'date_of_birth'=> $r->date_of_birth?->format('Y-m-d') ?? '',
                'age'          => $r->date_of_birth ? $r->age : '',
                'gender'       => $r->gender ?? '',
                'religion'     => $r->religion ?? '',
                'address'      => $r->home_address ?? '',
                'purok'        => $r->purok?->name ?? '',
                'barangay_city'=> 'Barangay 409, Manila City',
                'country'      => 'Philippines',
                'email'        => $r->email ?? '',
                'telephone'    => $r->telephone_number ?? '',
                'mobile'       => $r->mobile_number ?? '',
                'full_address' => $r->full_address,
            ]);

        return response()->json($residents);
    }
}
