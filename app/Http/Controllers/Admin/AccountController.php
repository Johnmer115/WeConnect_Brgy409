<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $roles = ['chairman', 'secretary', 'kagawad', 'resident'];

        return view('admin.management.admin.index', [
            'accounts' => User::whereIn('role', $roles)
                ->when($search !== '', function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('role', 'like', "%{$search}%")
                            ->orWhereRaw("CASE role
                                WHEN 'chairman' THEN 'Barangay Chairman'
                                WHEN 'secretary' THEN 'Barangay Councilor'
                                WHEN 'kagawad' THEN 'Barangay SK Councilor'
                                WHEN 'resident' THEN 'Resident'
                                ELSE role
                            END LIKE ?", ["%{$search}%"]);
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
        return view('admin.management.admin.create', [
            'accountTypeOptions' => $this->accountTypeOptions(),
            'adminRoleOptions' => $this->adminRoleOptions(),
            'availableResidents' => $this->availableResidents(),
        ]);
    }

    public function store(Request $request)
    {
        $accountType = $request->validate([
            'account_type' => ['required', 'in:admin,user'],
        ])['account_type'];

        if ($accountType === 'admin') {
            $validated = $this->validatedAdminAccount($request);

            User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'status' => 'active',
            ]);

            \App\Models\ActivityLog::log('create', 'Accounts', "Registered a new admin account: " . $validated['name']);

            return redirect()
                ->route('admin.accounts.index')
                ->with('success', 'Admin account registered successfully.');
        }

        $validated = $this->validatedResidentAccount($request);
        $resident = Resident::with('user')->findOrFail($validated['resident_id']);

        if ($resident->user) {
            return back()
                ->withInput()
                ->with('error', 'That resident already has an account.');
        }

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username'),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($resident, $validated) {
            $user = User::create([
                'name' => $resident->full_name,
                'username' => $validated['username'],
                'email' => $resident->email,
                'password' => Hash::make($validated['password']),
                'role' => 'resident',
                'status' => 'active',
            ]);

            $resident->forceFill(['user_id' => $user->id])->save();
        });

        \App\Models\ActivityLog::log('create', 'Accounts', "Registered a new resident user account: " . $resident->full_name);

        return redirect()
            ->route('admin.accounts.index')
            ->with('success', 'Resident account registered successfully.');
    }

    public function edit(User $account)
    {
        return view('admin.management.admin.edit', [
            'account' => $account,
            'roleOptions' => $this->roleOptions(),
        ]);
    }

    public function update(Request $request, User $account)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($account->id)],
            'status' => ['required', 'in:active,inactive'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $payload = [
            'email' => $validated['email'],
            'status' => $validated['status'],
        ];

        if (! empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $account->update($payload);

        \App\Models\ActivityLog::log('update', 'Accounts', "Updated account details for: " . $account->name);

        return redirect()
            ->route('admin.accounts.index')
            ->with('success', 'Account updated successfully.');
    }

    public function destroy(User $account)
    {
        if (auth()->id() === $account->id) {
            return back()->with('error', 'You cannot delete the account you are currently using.');
        }

        $accountName = $account->name;

        DB::transaction(function () use ($account) {
            if ($account->resident) {
                $account->resident->forceFill(['user_id' => null])->save();
            }

            $account->delete();
        });

        \App\Models\ActivityLog::log('delete', 'Accounts', "Deleted user login account: " . $accountName);

        return back()->with('success', 'Account deleted successfully.');
    }

    private function roleOptions(): array
    {
        return [
            'chairman' => 'Barangay Chairman',
            'secretary' => 'Barangay Councilor',
            'kagawad' => 'Barangay SK Councilor',
            'resident' => 'Resident',
        ];
    }

    private function accountTypeOptions(): array
    {
        return [
            'admin' => 'Admin',
            'user' => 'User',
        ];
    }

    private function adminRoleOptions(): array
    {
        return [
            'chairman' => 'Barangay Chairman',
            'secretary' => 'Barangay Councilor',
            'kagawad' => 'Barangay SK Councilor',
        ];
    }

    private function availableResidents()
    {
        return Resident::query()
            ->doesntHave('user')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
    }

    private function validatedAdminAccount(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username'),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'role' => ['required', 'in:chairman,secretary,kagawad'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        return $validated;
    }

    private function validatedAccount(Request $request, bool $creating = true, ?User $account = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($account?->id),
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($account?->id),
            ],
            'role' => ['required', 'in:chairman,secretary,kagawad,resident'],
            'status' => ['required', 'in:active,inactive'],
            'password' => [$creating ? 'required' : 'nullable', 'string', 'min:8', 'confirmed'],
        ]);

        return $validated;
    }

    private function validatedResidentAccount(Request $request): array
    {
        $validated = $request->validate([
            'resident_id' => [
                'required',
                Rule::exists('residents', 'id')->whereNull('user_id'),
            ],
        ]);

        return $validated;
    }
}
