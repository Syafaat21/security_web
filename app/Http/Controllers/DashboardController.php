<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(){
        return view('dashboard');
    }

    public function users(){
        // Mengambil data pengguna dari database
        $users = \App\Models\User::all();
        return view('menu.user', compact('users'));
    }

    public function updateUser(Request $request, $userId){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $userId,
            'role' => 'required|in:customer,staff,admin',
        ]);

        $user = User::findOrFail($userId);
        $user->update($request->only(['name', 'email', 'role']));

        return redirect()->back()->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function deleteUser($userId){
        $user = User::findOrFail($userId);

        // Prevent deleting self
        if ($user->id == auth()->id()) {
            return redirect()->back()->with('failed', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Pengguna berhasil dihapus.');
    }

    public function unbanUser($userId){
        $user = User::findOrFail($userId);
        $user->status = 'active';
        $user->save();

        return redirect()->back()->with('success', 'Pengguna berhasil dibuka blokirnya.');
    }

    public function customer(){
        return view('customer');
    }
}
