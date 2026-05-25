<?php

namespace App\Http\Controllers;

use App\Models\JadwalWawancara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalWawancaraController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user->divisi) {
            return redirect()->route('landing')->with('error', 'Akun Anda belum dihubungkan ke divisi mana pun.');
        }

        $divisi_id = $user->divisi->id;

        $jadwals = JadwalWawancara::where('divisi_id', $divisi_id)
            ->orderBy('tanggal')
            ->orderBy('waktu_mulai')
            ->get()
            ->groupBy('tanggal');

        $tanggals = JadwalWawancara::where('divisi_id', $divisi_id)->distinct()->pluck('tanggal');
        $waktu_slots = JadwalWawancara::where('divisi_id', $divisi_id)
            ->select('waktu_mulai', 'waktu_selesai')
            ->distinct()
            ->orderBy('waktu_mulai')
            ->get();

        return view('kadiv.jadwal.index', compact('jadwals', 'tanggals', 'waktu_slots'));
    }

    public function toggleStatus($id)
    {
        $jadwal = JadwalWawancara::findOrFail($id);

        if ($jadwal->status === 'dibooking') {
            return back()->with('error', 'Jadwal ini sudah dipilih oleh kandidat!');
        }

        $jadwal->status = ($jadwal->status === 'tersedia') ? 'tidak_tersedia' : 'tersedia';
        $jadwal->save();

        return back()->with('success', 'Status jadwal berhasil diubah.');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $divisi_id = Auth::user()->divisi->id;

        // Daftar jam sesuai gambar Excel kamu sebelumnya
        $jam_slots = [
            ['11:00', '11:30'],
            ['12:20', '12:50'],
            ['13:00', '13:30'],
            ['13:40', '14:10'],
            ['14:20', '14:50'],
            ['15:40', '16:10'],
            ['16:20', '16:50'],
            ['17:00', '17:30'],
            ['18:40', '19:10'],
            ['19:20', '19:50'],
            ['20:00', '20:30'],
            ['20:40', '21:10'],
        ];

        $start = \Carbon\Carbon::parse($request->tanggal_mulai);
        $end = \Carbon\Carbon::parse($request->tanggal_selesai);

        // Loop setiap hari antara tanggal mulai dan selesai
        for ($date = $start; $date->lte($end); $date->addDay()) {
            if ($date->isWeekend()) continue; // Opsional: Lewati sabtu minggu

            foreach ($jam_slots as $jam) {
                \App\Models\JadwalWawancara::firstOrCreate([
                    'divisi_id' => $divisi_id,
                    'tanggal' => $date->format('Y-m-d'),
                    'waktu_mulai' => $jam[0],
                    'waktu_selesai' => $jam[1],
                ], [
                    'status' => 'tersedia' // Default jadi putih
                ]);
            }
        }

        return back()->with('success', 'Slot jadwal berhasil digenerate! Silakan klik kotak untuk menandai jadwal Tidak Bisa.');
    }


    public function updateBulk(Request $request)
    {
        $divisi_id = Auth::user()->divisi->id;

        // 1. Reset semua jadwal yang bukan 'dibooking' menjadi 'tersedia' (putih)
        \App\Models\JadwalWawancara::where('divisi_id', $divisi_id)
            ->where('status', '!=', 'dibooking')
            ->update(['status' => 'tersedia']);

        // 2. Jika ada kotak yang diklik (merah), ubah statusnya menjadi 'tidak_tersedia'
        if ($request->has('jadwal_merah')) {
            \App\Models\JadwalWawancara::whereIn('id', $request->jadwal_merah)
                ->where('status', '!=', 'dibooking') // Pastikan yang dibooking aman
                ->update(['status' => 'tidak_tersedia']);
        }

        return back()->with('success', 'Ketersediaan jadwal berhasil diperbarui!');
    }
}
