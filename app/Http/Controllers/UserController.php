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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:staff,manager',
            'status' => 'required|in:active,inactive',
            'password' => 'required|min:6'
        ]);

        try {


            $role = Role::where('name', $request->role)->first();

            if (!$role) {
                return back()->with('error', 'Role not found.');
            }

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'status' => $request->status,
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

        $request->validate([
            'role' => 'required|in:staff,manager',
            'status' => 'required|in:active,inactive',
        ]);

        try {


            $role = Role::where('name', $request->role)->first();

            if (!$role) {
                return back()->with('error', 'Role not found.');
            }

            $user->update([
                'role_id' => $role->id,
                'status' => $request->status,
            ]);

            return back()->with('success', 'Role updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error("Role update error: " . $e->getMessage());
            return back()->with('error', 'Failed to update role.');
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


            if (!Hash::check($request->current_password, $request->user()->password)) {
                return back()->with('error', 'Current password is incorrect.');
            }

            $request->user()->update([
                'password' => Hash::make($request->password),
            ]);

            return back()->with('success', 'Password updated successfully!');
        } catch (\Exception $e) {
            Log::error("Password update error: " . $e->getMessage());
            return back()->with('error', 'Failed to update password.');
        }
    }

    public function userProfileUpdate(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);


        try {

            $data = $request->only(['name', 'email']);

            // Avatar upload
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('avatars', 'public');

                // Full URL banani hai
                $data['avatar'] = asset('storage/' . $path);
            }

            $request->user()->update($data);

            return back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error("Profile update error: " . $e->getMessage());
            return back()->with('error', 'Failed to update profile.');
        }
    }

    public function removeAvatar(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->avatar) {
                // Optionally delete file from storage
                $filePath = str_replace(asset('storage/'), '', $user->avatar);
                Storage::disk('public')->delete($filePath);

                $user->update(['avatar' => null]);
            }

            return back()->with('success', 'Avatar removed successfully.');
        } catch (\Exception $e) {
            Log::error("Avatar remove error: " . $e->getMessage());
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

}
