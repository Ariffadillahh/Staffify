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
    public function showSetup(Request $request)
    {
        $hasPO = User::where('role', 'po')->exists();

        if ($hasPO) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sistem sudah di-setup. Silakan login.'
                ], 400);
            }
            return redirect()->route('login')->with('error', 'Sistem sudah di-setup. Silakan login.');
        }

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sistem siap di-setup.'
            ], 200);
        }

        return view('auth.setup');
    }

    public function setup(Request $request)
    {
        if (User::where('role', 'po')->exists()) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Setup ditolak. Akun PO sudah ada.'
                ], 400);
            }
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

        $user = null;

        DB::transaction(function () use ($request, &$user) {
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

            if (!$request->is('api/*') && !$request->expectsJson()) {
                Auth::login($user);
            }
        });

        if ($request->is('api/*') || $request->expectsJson()) {
            /** @var \App\Models\User $user */
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'success' => true,
                'message' => 'Sistem berhasil di-setup!',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ], 201);
        }

        return redirect()->route('proker.index')->with('success', 'Sistem berhasil di-setup! Selamat datang di Dashboard Admin.');
    }

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
            // PERBAIKAN AMAN: Cek rute api/* atau header JSON
            if ($request->is('api/*') || $request->expectsJson()) {
                $user = Auth::user();
                /** @var \App\Models\User $user */
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'success' => true,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'user' => $user
                ], 200);
            }

            $request->session()->regenerate();
            if (Auth::user()->role == 'kadiv') {
                return redirect()->route('kadiv.dashboard');
            }
            return redirect()->route('proker.index');
        }

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Kredensial tidak cocok dengan data kami.'
            ], 401);
        }

        return back()->withErrors(['email' => 'Kredensial tidak cocok.'])->onlyInput('email');
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
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Password saat ini tidak sesuai.'], 422);
            }
            throw ValidationException::withMessages([
                'current_password' => 'Password saat ini tidak sesuai.',
            ]);
        }

        if (Hash::check($validated['password'], $user->password)) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Password baru tidak boleh sama dengan password lama.'], 422);
            }
            throw ValidationException::withMessages([
                'password' => 'Password baru tidak boleh sama dengan password lama.',
            ]);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diperbarui.'
            ], 200);
        }

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function logout(Request $request)
    {
        if ($request->is('api/*') || $request->expectsJson()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil'
            ], 200);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
