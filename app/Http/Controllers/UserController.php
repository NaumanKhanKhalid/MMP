<?php
namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    // List all users (except owner itself)
    public function index()
    {
        try {
            // Owner ke ilawa sab users paginate karo (10 per page)
            $users = User::whereHas('role', fn($q) => $q->where('name', '!=', 'Owner'))
                ->paginate(10);

            return view('users.index', compact('users'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load users, please try again later.');
        }
    }

    // Create new staff/manager
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'role' => 'required|in:staff,manager',
                'password' => 'required|min:6'
            ]);

            $role = Role::where('name', $request->role)->first();

            if (!$role) {
                return back()->with('error', 'Role not found.');
            }

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $role->id,
                'password' => Hash::make($request->password),
            ]);

            return back()->with('success', 'User created successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error("User create error: " . $e->getMessage());
            return back()->with('error', 'Something went wrong while creating user.' . $e->getMessage());
        }
    }

    // Update role
    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'role' => 'required|in:staff,manager',
            ]);

            $role = Role::where('name', $request->role)->first();

            if (!$role) {
                return back()->with('error', 'Role not found.');
            }

            $user->update([
                'role_id' => $role->id,
            ]);

            return back()->with('success', 'Role updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error("Role update error: " . $e->getMessage());
            return back()->with('error', 'Failed to update role.');
        }
    }

    // Reset password
    public function resetPassword(User $user)
    {
        try {
            $newPassword = '123456'; // default or random generate
            $user->update(['password' => Hash::make($newPassword)]);

            return back()->with('success', 'Password has been reset successfully. New password: ' . $newPassword);
        } catch (\Exception $e) {
            Log::error("Password reset error: " . $e->getMessage());
            return back()->with('error', 'Failed to reset password.');
        }
    }

    // Delete
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return back()->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            Log::error("User delete error: " . $e->getMessage());
            return back()->with('error', 'Failed to delete user.');
        }
    }
}
