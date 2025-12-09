<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(15);
        $totalUsers = User::count();
        $adminUsers = User::where('role', 'admin')->count();
        $memberUsers = User::where('role', 'member')->count();

        return view('admin.users.index', compact('users', 'totalUsers', 'adminUsers', 'memberUsers'));
    }

    public function show(User $user)
    {
        $user->load('store');
        $store = $user->store;

        return view('admin.users.show', compact('user', 'store'));
    }

    public function toggleRole(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat mengubah role diri sendiri.');
        }

        $user->update([
            'role' => $user->role === 'admin' ? 'member' : 'admin'
        ]);

        return back()->with('success', 'Role user berhasil diubah.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun diri sendiri.');
        }

        $userName = $user->name;

        // 1. Hapus foto user jika ada
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        // 2. Hapus toko user jika ada
        if ($user->store) {

            // Hapus logo toko jika ada
            if ($user->store->logo && Storage::disk('public')->exists($user->store->logo)) {
                Storage::disk('public')->delete($user->store->logo);
            }

            $user->store->delete();
        }

        // 3. Hapus user
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$userName}' berhasil dihapus.");
    }
}
