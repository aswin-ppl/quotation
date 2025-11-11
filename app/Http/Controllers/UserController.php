<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    /**
     * Return users as JSON for DataTables AJAX loading
     */
    public function data(Request $request)
    {
        try {
            $this->authorize('view-users');

            $users = User::with('roles')->get();

            $data = $users->map(function ($u) {
                $actionHtml = '';

                if (auth()->user()->can('edit-users')) {
                    $actionHtml .= '<a href="' . route('users.edit', $u->id) . '" class="btn btn-sm bg-primary-subtle text-primary">'
                        . '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">'
                        . '<path fill="currentColor" d="M20.849 8.713a3.932 3.932 0 0 0-5.562-5.561l-.887.887l.038.111a8.75 8.75 0 0 0 2.093 3.32a8.75 8.75 0 0 0 3.43 2.13z" opacity="0.5" />'
                        . '<path fill="currentColor" d="m14.439 4l-.039.038l.038.112a8.75 8.75 0 0 0 2.093 3.32a8.75 8.75 0 0 0 3.43 2.13l-8.56 8.56c-.578.577-.867.866-1.185 1.114a6.6 6.6 0 0 1-1.211.748c-.364.174-.751.303-1.526.561l-4.083 1.361a1.06 1.06 0 0 1-1.342-1.341l1.362-4.084c.258-.774.387-1.161.56-1.525q.309-.646.749-1.212c.248-.318.537-.606 1.114-1.183z" />'
                        . '</svg></a>';
                }
                if (auth()->user()->can('delete-users')) {
                    $actionHtml .= '<form action="' . route('users.destroy', $u->id) . '" method="POST" class="delete-form d-inline" style="display:inline-block;margin:0 2px;">';
                    $actionHtml .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                    $actionHtml .= '<input type="hidden" name="_method" value="DELETE">';
                    $actionHtml .= '<button type="button" class="btn btn-sm bg-danger-subtle text-danger btn-delete">';
                    $actionHtml .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">';
                    $actionHtml .= '<path fill="currentColor" d="M3 6.524c0-.395.327-.714.73-.714h4.788c.006-.842.098-1.995.932-2.793A3.68 3.68 0 0 1 12 2a3.68 3.68 0 0 1 2.55 1.017c.834.798.926 1.951.932 2.793h4.788c.403 0 .73.32.73.714a.72.72 0 0 1-.73.714H3.73A.72.72 0 0 1 3 6.524" />';
                    $actionHtml .= '<path fill="currentColor" fill-rule="evenodd" d="M11.596 22h.808c2.783 0 4.174 0 5.08-.886c.904-.886.996-2.34 1.181-5.246l.267-4.187c.1-1.577.15-2.366-.303-2.866c-.454-.5-1.22-.5-2.753-.5H8.124c-1.533 0-2.3 0-2.753.5s-.404 1.289-.303 2.866l.267 4.188c.185 2.906.277 4.36 1.182 5.245c.905.886 2.296.886 5.079.886m-1.35-9.811c-.04-.434-.408-.75-.82-.707c-.413.043-.713.43-.672.864l.5 5.263c.04.434.408.75.82.707c.413-.044.713-.43.672-.864zm4.329-.707c.412.043.713.43.671.864l-.5 5.263c-.04.434-.409.75-.82.707c-.413-.044-.713-.43-.672-.864l.5-5.264c.04-.433.409-.75.82-.707" clip-rule="evenodd" />';
                    $actionHtml .= '</svg></button></form>';
                }

                return [
                    'id' => $u->id,
                    'name' => e($u->name),
                    'email' => e($u->email),
                    'roles' => e($u->roles->pluck('name')->implode(', ')),
                    'action' => $actionHtml,
                ];
            });

            return response()->json(['data' => $data]);
        } catch (\Throwable $e) {
            Log::error('User data AJAX error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['data' => [], 'error' => 'Failed to load users'], 500);
        }
    }

}