<?php
// app/Http/Controllers/Auth/RegisterController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Purok;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showForm()
    {
        $puroks = $this->registrationPuroks();
        return view('loginpage.regisform', compact('puroks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // account credentials
            'username'         => ['required', 'string', 'min:4', 'max:50', 'unique:users,username'],
            'email'            => ['nullable', 'email', 'unique:users,email'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],

            // basic info
            'last_name'        => ['required', 'string', 'max:100'],
            'first_name'       => ['required', 'string', 'max:100'],
            'middle_name'      => ['nullable', 'string', 'max:100'],
            'suffix'           => ['nullable', 'string', 'max:20'],
            'date_of_birth'    => ['required', 'date', 'before:today'],
            'blood_type'       => ['nullable', 'string'],
            'gender'           => ['required', 'in:Male,Female'],
            'religion'         => ['nullable', 'string'],

            // memberships
            'is_4ps'           => ['nullable', 'boolean'],
            'is_pwd'           => ['nullable', 'boolean'],
            'is_voter'         => ['nullable', 'boolean'],
            'is_single_parent' => ['nullable', 'boolean'],

            // contact
            'telephone_number' => ['nullable', 'string', 'max:20'],
            'mobile_number'    => ['nullable', 'string', 'max:20'],

            // address
            'home_address'     => ['nullable', 'string'],
            'purok_id'         => ['nullable', 'exists:puroks,id'],
        ]);

        $user = DB::transaction(function () use ($request, $validated) {
            // 1. Create the login account.
            $user = User::create([
                'name'     => trim("{$validated['first_name']} {$validated['last_name']}"),
                'username' => $validated['username'],
                'email'    => $validated['email'] ?? null,
                'password' => Hash::make($validated['password']),
                'role'     => 'resident',
                'status'   => 'inactive',
            ]);

            // 2. Create the resident profile linked to that account.
            Resident::create([
                'user_id'          => $user->id,
                'last_name'        => $validated['last_name'],
                'first_name'       => $validated['first_name'],
                'middle_name'      => $validated['middle_name'] ?? null,
                'suffix'           => $validated['suffix'] ?? null,
                'date_of_birth'    => $validated['date_of_birth'],
                'blood_type'       => $validated['blood_type'] ?? null,
                'gender'           => $validated['gender'],
                'religion'         => $validated['religion'] ?? null,
                'health_status'    => 'Alive',
                'is_4ps'           => $request->boolean('is_4ps'),
                'is_pwd'           => $request->boolean('is_pwd'),
                'is_voter'         => $request->boolean('is_voter'),
                'is_single_parent' => $request->boolean('is_single_parent'),
                'email'            => $validated['email'] ?? null,
                'telephone_number' => $validated['telephone_number'] ?? null,
                'mobile_number'    => $validated['mobile_number'] ?? null,
                'home_address'     => $validated['home_address'] ?? null,
                'purok_id'         => $validated['purok_id'] ?? null,
            ]);

            return $user;
        });

        return redirect()
            ->route('login')
            ->with('success', 'Registration submitted. Please wait for an admin to approve your account before logging in.');
    }

    private function registrationPuroks()
    {
        $names = ['Sunflower', 'Rosal', 'Gumamela', 'Sampaguita', 'Ilang-Ilang'];

        return Purok::whereIn('name', $names)
            ->get()
            ->sortBy(fn ($purok) => array_search($purok->name, $names, true))
            ->values();
    }
}
