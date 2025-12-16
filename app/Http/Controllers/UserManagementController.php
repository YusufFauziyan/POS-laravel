<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users in the same company.
     */
    public function index()
    {
        $users = User::where('company_id', auth()->user()->company_id)
            ->with('company')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'role' => ['required', Rule::in(['admin', 'cashier', 'owner'])],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Check unique email within company
        $exists = User::where('company_id', auth()->user()->company_id)
            ->where('email', $validated['email'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'email' => 'This email is already used in your company.',
            ]);
        }

        User::create([
            'company_id' => auth()->user()->company_id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => true,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        // Ensure user belongs to same company
        if ($user->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'role' => ['required', Rule::in(['admin', 'cashier', 'owner'])],
            'is_active' => ['required', 'boolean'],
        ]);

        // Check unique email within company (excluding current user)
        $exists = User::where('company_id', auth()->user()->company_id)
            ->where('email', $validated['email'])
            ->where('id', '!=', $user->id)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'email' => 'This email is already used in your company.',
            ]);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Deactivate the specified user.
     */
    public function destroy(User $user)
    {
        // Ensure user belongs to same company
        if ($user->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized action.');
        }

        // Prevent deactivating yourself
        if ($user->id === auth()->id()) {
            return back()->withErrors([
                'error' => 'You cannot deactivate your own account.',
            ]);
        }

        $user->update(['is_active' => false]);

        return redirect()->route('users.index')->with('success', 'User deactivated successfully!');
    }

    /**
     * Reset password for the specified user.
     */
    public function resetPassword(Request $request, User $user)
    {
        // Ensure user belongs to same company
        if ($user->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('users.index')->with('success', 'Password reset successfully!');
    }
}
