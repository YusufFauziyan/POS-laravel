<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class CompanyRegistrationController extends Controller
{
    /**
     * Show the company registration form.
     */
    public function showRegistrationForm()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle company registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'company_email' => ['required', 'string', 'email', 'max:255', 'unique:companies,email'],
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        try {
            DB::beginTransaction();

            // Create company
            $company = Company::create([
                'name' => $validated['company_name'],
                'email' => $validated['company_email'],
                'is_active' => true,
            ]);

            // Create Owner user
            $owner = User::create([
                'company_id' => $company->id,
                'name' => $validated['owner_name'],
                'email' => $validated['owner_email'],
                'password' => Hash::make($validated['password']),
                'role' => UserRole::OWNER,
                'is_active' => true,
            ]);

            // Create Admin user with default password
            User::create([
                'company_id' => $company->id,
                'name' => 'Admin',
                'email' => 'admin@' . $company->slug . '.local',
                'password' => Hash::make('password'),
                'role' => UserRole::ADMIN,
                'is_active' => true,
            ]);

            // Create Cashier user with default password
            User::create([
                'company_id' => $company->id,
                'name' => 'Cashier',
                'email' => 'cashier@' . $company->slug . '.local',
                'password' => Hash::make('password'),
                'role' => UserRole::CASHIER,
                'is_active' => true,
            ]);

            DB::commit();

            // Auto-login as owner
            Auth::login($owner);

            return redirect()->route('dashboard')->with('success', 'Company registered successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors([
                'error' => 'Failed to register company. Please try again.',
            ])->withInput();
        }
    }
}
