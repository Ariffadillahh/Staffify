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
    public function index()
    {
        $divisis = Divisi::with('proker')->whereNull('kadiv_id')->get();
        return view('po.kadiv.generate', compact('divisis'));
    }

    public function storeDivisi(Request $request)
    {
        $request->validate([
            'proker_id' => 'required|exists:prokers,id',
            'nama_divisi.*' => 'required|string|max:255',
            'kuota_staff.*' => 'required|integer|min:1',
        ]);

        foreach ($request->nama_divisi as $key => $nama) {
            Divisi::create([
                'proker_id' => $request->proker_id,
                'nama_divisi' => $nama,
                'kuota_staff' => $request->kuota_staff[$key],
            ]);
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

        // Kirim Email (Pastikan konfigurasi .env SMTP sudah benar)
        Mail::to($kadiv->email)->send(new AkunKadivCreated($kadiv, $passwordPlain, $divisi));

        return back()->with('success', 'Akun Kadiv ' . $kadiv->name . ' berhasil dibuat!');
    }
}
