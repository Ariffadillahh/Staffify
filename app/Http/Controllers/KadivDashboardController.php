<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JadwalWawancara;

class KadivDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $divisi = $user->divisi;

        if (!$divisi) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda belum dihubungkan ke divisi.'
                ], 403);
            }
            return redirect('/')->with('error', 'Akun Anda belum dihubungkan ke divisi.');
        }

        $kuota = $divisi->kuota_staff ?? 0;

        $jumlahPendaftar = JadwalWawancara::where('divisi_id', $divisi->id)
            ->where('status', 'dibooking')
            ->count();

        $jadwalTerdekat = JadwalWawancara::with('pendaftaran')
            ->where('divisi_id', $divisi->id)
            ->where('status', 'dibooking')
            ->where(function ($query) {
                $query->where('tanggal', '>', now()->toDateString())
                    ->orWhere(function ($q) {
                        $q->where('tanggal', '=', now()->toDateString())
                            ->where('waktu_mulai', '>=', now()->toTimeString());
                    });
            })
            ->orderBy('tanggal')
            ->orderBy('waktu_mulai')
            ->first();

        // RESPON UNTUK FLUTTER API
        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'divisi' => $divisi,
                'kuota' => $kuota,
                'jumlah_pendaftar' => $jumlahPendaftar,
                'jadwal_terdekat' => $jadwalTerdekat
            ], 200);
        }

        // RESPON UNTUK WEB BLADE LAMA
        return view('kadiv.dashboard.index', compact('divisi', 'kuota', 'jumlahPendaftar', 'jadwalTerdekat'));
    }

    public function updateLink(Request $request)
    {
        $request->validate([
            'link_wawancara' => 'required|url'
        ], [
            'link_wawancara.required' => 'Tautan tidak boleh kosong. Gunakan tombol hapus jika ingin menghapusnya.',
            'link_wawancara.url' => 'Format link tidak valid. Pastikan menggunakan http:// atau https://'
        ]);

        $divisi = Auth::user()->divisi;
        $isNew = empty($divisi->link_wawancara);

        $divisi->update(['link_wawancara' => $request->link_wawancara]);
        $pesan = $isNew ? 'Tautan ruang wawancara berhasil dibuat!' : 'Tautan ruang wawancara berhasil diperbarui!';

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $pesan,
                'divisi' => $divisi
            ], 200);
        }

        return back()->with('success', $pesan);
    }

    public function deleteLink(Request $request)
    {
        $divisi = Auth::user()->divisi;
        $divisi->update(['link_wawancara' => null]);

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tautan ruang wawancara berhasil dihapus!',
                'divisi' => $divisi
            ], 200);
        }

        return back()->with('success', 'Tautan ruang wawancara berhasil dihapus!');
    }
}
