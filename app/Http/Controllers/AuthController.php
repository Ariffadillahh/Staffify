<?php

namespace App\Http\Controllers;

use App\Models\Proker;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showSetup()
    {
        if (User::where('role', 'po')->exists()) {
            return redirect()->route('login')->with('error', 'Sistem sudah di-setup. Silakan login.');
        }

        return view('auth.setup');
    }

    public function setup(Request $request)
    {
        if (User::where('role', 'po')->exists()) {
            return redirect()->route('login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'nama_proker' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'po',
            ]);

            Proker::create([
                'nama_proker' => $request->nama_proker,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'deskripsi' => $request->deskripsi,
            ]);

            Auth::login($user);
        });

        return redirect()->route('proker.index')->with('success', 'Sistem berhasil di-setup! Selamat datang di Dashboard Admin.');
    }

    // ==========================================
    // BAGIAN LOGIN & LOGOUT (PO, VPO, Kadiv)
    // ==========================================
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role == 'kadiv') {
                return redirect()->route('kadiv.dashboard');
            }

            return redirect()->route('proker.index');
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password saat ini tidak sesuai.',
            ]);
        }

        if (Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'Password baru tidak boleh sama dengan password lama.',
            ]);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
