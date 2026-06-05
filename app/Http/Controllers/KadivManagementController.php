<?php

namespace App\Http\Controllers;

use App\Mail\AkunKadivCreated;
use App\Models\Divisi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class KadivManagementController extends Controller
{
    public function index(Request $request)
    {
        // Ambil daftar divisi yang belum memiliki Kepala Divisi (Kadiv)
        $divisis = Divisi::with('proker')
            ->orderByRaw('kadiv_id IS NULL DESC')
            ->orderBy('kadiv_id')
            ->get();
            
        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'divisis' => $divisis
            ], 200);
        }

        return view('po.kadiv.generate', compact('divisis'));
    }

    public function storeDivisi(Request $request)
    {
        $request->validate([
            'proker_id' => 'required|exists:prokers,id',
            'nama_divisi' => 'required|array',
            'nama_divisi.*' => 'required|string|max:255',
            'kuota_staff' => 'required|array',
            'kuota_staff.*' => 'required|integer|min:1',
        ]);

        $createdDivisions = [];

        foreach ($request->nama_divisi as $key => $nama) {
            $createdDivisions[] = Divisi::create([
                'proker_id' => $request->proker_id,
                'nama_divisi' => $nama,
                'kuota_staff' => $request->kuota_staff[$key],
            ]);
        }

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Daftar divisi berhasil ditambahkan!',
                'data' => $createdDivisions
            ], 201);
        }

        return back()->with('success', 'Daftar divisi berhasil ditambahkan!');
    }

    public function storeKadiv(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'divisi_id' => 'required|exists:divisis,id'
        ]);

        $passwordPlain = Str::random(8);

        $kadiv = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($passwordPlain),
            'role' => 'kadiv',
        ]);

        $divisi = Divisi::findOrFail($request->divisi_id);
        $divisi->update(['kadiv_id' => $kadiv->id]);

        // Kirim Email secara asynchronous (background task) / Sync tergantung setup .env kamu
        try {
            Mail::to($kadiv->email)->send(new AkunKadivCreated($kadiv, $passwordPlain, $divisi));
        } catch (\Exception $e) {
            // Tetap izinkan akun terbuat di lokal sistem jika internet server/SMTP bermasalah
        }

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Akun Kadiv ' . $kadiv->name . ' berhasil dibuat!',
                'data' => [
                    'kadiv' => $kadiv,
                    'divisi' => $divisi,
                    'generated_password' => $passwordPlain // Opsional untuk membantu debug pada API mobile
                ]
            ], 201);
        }

        return back()->with('success', 'Akun Kadiv ' . $kadiv->name . ' berhasil dibuat!');
    }
}
