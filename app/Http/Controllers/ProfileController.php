<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // Ubah Nama — Semua Role
    public function updateName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:255',
        ], [
            'name.required' => 'Nama tidak boleh kosong.',
            'name.min'      => 'Nama minimal 3 karakter.',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return back()->with('success', 'Nama berhasil diperbarui menjadi "' . $user->name . '".');
    }

    // Ubah Password — Semua Role
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|confirmed|min:6|different:current_password',
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.confirmed'        => 'Konfirmasi password tidak cocok.',
            'password.min'              => 'Password baru minimal 6 karakter.',
            'password.different'        => 'Password baru harus berbeda dari password lama.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama yang Anda masukkan salah.'])->with('open_password_modal', true);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }

    // Halaman Reset Password — Khusus Admin & Super-Admin
    public function resetPasswordView()
    {
        $users = User::with('roles')
            ->where('id', '!=', Auth::id())
            ->orderBy('name')
            ->get();

        return view('admin.users.reset-password', compact('users'));
    }

    // Proses Reset Password — Khusus Admin & Super-Admin
    public function resetPassword(Request $request, $userId)
    {
        $request->validate([
            'new_password' => 'required|confirmed|min:6',
        ], [
            'new_password.required'  => 'Password baru wajib diisi.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
            'new_password.min'       => 'Password minimal 6 karakter.',
        ]);

        $target = User::findOrFail($userId);

        // Admin biasa tidak bisa reset password super-admin
        if ($target->hasRole('super-admin') && !Auth::user()->hasRole('super-admin')) {
            return back()->with('error', 'Anda tidak memiliki izin untuk mereset password Super Admin.');
        }

        $target->password = Hash::make($request->new_password);
        $target->save();

        return back()->with('success', 'Password akun "' . $target->name . '" berhasil direset.');
    }
}
