<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $this->authorize('view-users');
        $users = User::all();
        return view('user-and-permissions.users.index', compact('users'));
    }

    public function create()
    {
        $this->authorize('create-users');
        $roles = Role::all();
        return view('user-and-permissions.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-users');

        try {
            // Validate inputs
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'roles' => 'required'
            ]);

            DB::transaction(function () use ($validated) {
                // Create user
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                ]);

                // Assign roles
                $user->syncRoles($validated['roles']);
            });

            // Success response
            return redirect()->route('users.index')
                ->with('success', 'User created successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel already handles redirecting back with errors
            throw $e;
        } catch (\Throwable $e) {
            // Log for debugging
            \Log::error('User creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            // Friendly fail message
            return redirect()->route('users.index')
                ->with('error', 'Something went wrong while creating the user!');
        }
    }


    public function edit(User $user)
    {
        $this->authorize('edit-users');
        $roles = Role::all();
        return view('user-and-permissions.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('edit-users');

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'roles' => 'required'
            ]);

            DB::transaction(function () use ($validated, $request, $user) {
                // Update basic info
                $user->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                ]);

                // Update password if provided
                if ($request->filled('password')) {
                    $request->validate([
                        'password' => 'string|min:8|confirmed',
                    ]);
                    $user->update([
                        'password' => Hash::make($request->password),
                    ]);
                }

                // Update role
                $user->syncRoles($validated['roles']);
            });

            return redirect()->route('users.index')
                ->with('success', 'User updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel will auto-redirect back with errors, no need to handle manually
            throw $e;
        } catch (\Throwable $e) {
            \Log::error('User update failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('users.index')
                ->with('error', 'Something went wrong while updating the user!');
        }
    }


    public function destroy(User $user)
    {
        $this->authorize('delete-users');

        try {
            DB::transaction(fn() => $user->delete());
            return back()->with('success', 'User deleted successfully');
        } catch (\Throwable $e) {
            \Log::error("User deletion failed: {$e->getMessage()}", ['id' => $user->id]);
            return back()->with('error', 'Failed to delete user!');
        }
    }

}