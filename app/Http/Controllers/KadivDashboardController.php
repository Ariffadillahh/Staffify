<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JadwalWawancara;

class KadivDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $divisi = $user->divisi;

        if (!$divisi) {
            return redirect('/')->with('error', 'Akun Anda belum dihubungkan ke divisi.');
        }

        // 1. Kuota Staff Divisi
        $kuota = $divisi->kuota_staff ?? 0;

        // 2. Jumlah Pendaftar (yang sudah membooking jadwal di divisi ini)
        $jumlahPendaftar = JadwalWawancara::where('divisi_id', $divisi->id)
            ->where('status', 'dibooking')
            ->count();

        // 3. Jadwal Wawancara Terdekat (berdasarkan waktu saat ini)
        $jadwalTerdekat = JadwalWawancara::with('pendaftaran')
            ->where('divisi_id', $divisi->id)
            ->where('status', 'dibooking')
            ->where(function ($query) {
                // Mencari tanggal di masa depan ATAU hari ini tapi jamnya belum lewat
                $query->where('tanggal', '>', now()->toDateString())
                    ->orWhere(function ($q) {
                        $q->where('tanggal', '=', now()->toDateString())
                            ->where('waktu_mulai', '>=', now()->toTimeString());
                    });
            })
            ->orderBy('tanggal')
            ->orderBy('waktu_mulai')
            ->first();

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

        return back()->with('success', $pesan);
    }

    public function deleteLink()
    {
        $divisi = Auth::user()->divisi;

        // Kosongkan link di database
        $divisi->update(['link_wawancara' => null]);

        return back()->with('success', 'Tautan ruang wawancara berhasil dihapus!');
    }
}
