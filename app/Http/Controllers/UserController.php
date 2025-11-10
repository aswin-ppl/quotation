<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
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

    public function store(StoreUserRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                $user->syncRoles($request->roles);
            });

            return redirect()->route('users.create')
                ->with('success', 'User created successfully');
        } catch (\Throwable $e) {
            \Log::error('User creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

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

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            DB::transaction(function () use ($request, $user) {
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                ]);

                if ($request->filled('password')) {
                    $user->update([
                        'password' => Hash::make($request->password),
                    ]);
                }

                $user->syncRoles($request->roles);
            });

            return redirect()->route('users.index')
                ->with('success', 'User updated successfully');
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