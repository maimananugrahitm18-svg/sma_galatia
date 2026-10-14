<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\SiswaProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        return view('auth.register', compact('kelasList'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:siswa,guru',
            // fields untuk siswa
            'nis' => 'nullable|string|max:20|unique:siswa_profiles,nis',
            'kelas_id' => 'nullable|exists:kelas,id',
            // fields untuk guru
            'nip' => 'nullable|string|max:30|unique:guru_profiles,nip',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if ($validated['role'] === 'siswa') {
            $user->assignRole('siswa');
            SiswaProfile::create([
                'user_id' => $user->id,
                'nis' => $validated['nis'] ?? ('S' . rand(100000, 999999)),
                'kelas_id' => $validated['kelas_id'] ?? null,
                'tahun_masuk' => date('Y'),
            ]);
        } else {
            $user->assignRole('guru');
            \App\Models\GuruProfile::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'] ?? null,
            ]);
        }

        Auth::login($user);

        return $this->redirectBasedOnRole($user);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil keluar.');
    }

    protected function redirectBasedOnRole($user)
    {
        if ($user->hasRole('super-admin') || $user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('guru')) {
            return redirect()->route('guru.dashboard');
        } elseif ($user->hasRole('siswa')) {
            return redirect()->route('siswa.dashboard');
        }

        return redirect('/');
    }
}
