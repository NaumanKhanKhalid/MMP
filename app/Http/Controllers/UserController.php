<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // List all users (except owner itself)
    public function index()
    {
        try {
            $users = User::with('role')
                ->whereHas('role', fn ($q) => $q->where('name', '!=', 'Owner'))
                ->latest()
                ->paginate(15);

            return view('users.index', compact('users'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load users, please try again later.');
        }
    }

    // Create new staff/manager
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:Staff,Manager',
            'status' => 'required|in:active,inactive',
            'password' => 'required|min:6',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $role = Role::where('name', $request->role)->first();

            if (! $role) {
                return back()->with('error', 'Role not found.');
            }

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => $request->status,
                'role_id' => $role->id,
                'password' => Hash::make($request->password),
                'notes' => $request->notes,
                'first_login' => true, // Force password change on first login
            ]);

            return back()->with('success', 'User created successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('User create error: '.$e->getMessage());

            return back()->with('error', 'Something went wrong while creating user.'.$e->getMessage());
        }
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:Staff,Manager',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $role = Role::where('name', $request->role)->first();

            if (! $role) {
                return back()->with('error', 'Role not found.');
            }

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role_id' => $role->id,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            return back()->with('success', 'User updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('User update error: '.$e->getMessage());

            return back()->with('error', 'Failed to update user.');
        }
    }

    // Soft Delete
    public function destroy(User $user)
    {
        try {
            // Prevent deleting owner
            if ($user->role->name === 'Owner') {
                return response()->json(['message' => 'Cannot delete owner account!'], 403);
            }

            $user->delete(); // Soft delete

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully!',
            ]);
        } catch (\Exception $e) {
            Log::error('User delete error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user: '.$e->getMessage(),
            ], 500);
        }
    }

    // Restore soft deleted user
    public function restore($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            $user->restore();

            return back()->with('success', 'User restored successfully!');
        } catch (\Exception $e) {
            Log::error('User restore error: '.$e->getMessage());

            return back()->with('error', 'Failed to restore user.');
        }
    }

    // Permanent delete
    public function forceDestroy($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);

            // Prevent force deleting owner
            if ($user->role->name === 'Owner') {
                return back()->with('error', 'Cannot permanently delete owner account!');
            }

            $user->forceDelete();

            return back()->with('success', 'User permanently deleted!');
        } catch (\Exception $e) {
            Log::error('User force delete error: '.$e->getMessage());

            return back()->with('error', 'Failed to permanently delete user.');
        }
    }

    public function userProfileSettings()
    {
        return view('users.profile_settings');
    }

    public function userPasswordUpdate(Request $request)
    {

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);
        try {

            if (! Hash::check($request->current_password, $request->user()->password)) {
                return back()->with('error', 'Current password is incorrect.');
            }

            $request->user()->update([
                'password' => Hash::make($request->password),
            ]);

            return back()->with('success', 'Password updated successfully!');
        } catch (\Exception $e) {
            Log::error('Password update error: '.$e->getMessage());

            return back()->with('error', 'Failed to update password.');
        }
    }

    public function userProfileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$request->user()->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        try {
            $data = $request->only(['name', 'email', 'phone']);

            // Avatar upload
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('avatars', 'public');
                $data['avatar'] = asset('storage/app/public/'.$path);
            }

            $request->user()->update($data);

            return back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            Log::error('Profile update error: '.$e->getMessage());

            return back()->with('error', 'Failed to update profile.');
        }
    }

    public function removeAvatar(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->avatar) {
                // Optionally delete file from storage
                $filePath = str_replace(asset('public/storage/'), '', $user->avatar);
                Storage::disk('public')->delete($filePath);

                $user->update(['avatar' => null]);
            }

            return back()->with('success', 'Avatar removed successfully.');
        } catch (\Exception $e) {
            Log::error('Avatar remove error: '.$e->getMessage());

            return back()->with('error', 'Failed to remove avatar.');
        }
    }

    public function twoFactorEnable(Request $request)
    {
        $user = $request->user();

        if ($user->role->name !== 'Owner') {
            return back()->with('error', 'You are not allowed to perform this action.');
        }

        $user->update(['two_factor_enabled' => true]);

        return back()->with('success', 'Two-Factor Authentication enabled successfully.');
    }

    public function twoFactorDisable(Request $request)
    {
        $user = $request->user();

        if ($user->role->name !== 'Owner') {
            return back()->with('error', 'You are not allowed to perform this action.');
        }

        $user->update(['two_factor_enabled' => false]);

        return back()->with('success', 'Two-Factor Authentication disabled successfully.');
    }

    public function toggleUserStatus(User $user)
    {
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        return back()->with('success', 'User status updated successfully.');
    }

    public function createModal()
    {
        return view('users.partials.create_modal');
    }

    public function viewModal(User $user)
    {
        return view('users.partials.view_modal', compact('user'));
    }

    public function editModal(User $user)
    {
        return view('users.partials.edit_modal', compact('user'));
    }
}
