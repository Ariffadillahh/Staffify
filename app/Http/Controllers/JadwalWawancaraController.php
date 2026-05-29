<?php

namespace App\Http\Controllers;

use App\Models\JadwalWawancara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalWawancaraController extends Controller
{
    public function index(Request $request) // Tambahkan parameter Request
    {
        $user = Auth::user();

        // VALIDASI UNTUK API / FLUTTER
        if ($request->is('api/*') || $request->expectsJson()) {
            if (!$user->divisi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda belum dihubungkan ke divisi mana pun.'
                ], 403);
            }
        } else {
            // BACKUP UNTUK WEB LAMA
            if (!$user->divisi) {
                return redirect()->route('landing')->with('error', 'Akun Anda belum dihubungkan ke divisi mana pun.');
            }
        }

        $divisi_id = $user->divisi->id;

        $jadwals = JadwalWawancara::with('pendaftaran')
            ->where('divisi_id', $divisi_id)
            ->orderBy('tanggal')
            ->orderBy('waktu_mulai')
            ->get();

        $tanggals = JadwalWawancara::where('divisi_id', $divisi_id)
            ->distinct()
            ->orderBy('tanggal', 'asc')
            ->pluck('tanggal');

        $waktu_slots = JadwalWawancara::where('divisi_id', $divisi_id)
            ->select('waktu_mulai', 'waktu_selesai')
            ->distinct()
            ->orderBy('waktu_mulai')
            ->get();

        // RESPON KHUSUS API (Data dikirim flat / tanpa di-groupBy agar Dart lebih mudah parsing)
        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'tanggals' => $tanggals,
                'waktu_slots' => $waktu_slots,
                'jadwals' => $jadwals
            ], 200);
        }

        $jadwalsGrouped = $jadwals->groupBy('tanggal');
        return view('kadiv.jadwal.index', compact('jadwalsGrouped', 'jadwals', 'tanggals', 'waktu_slots'));
    }

    public function toggleStatus(Request $request, $id) // Tambahkan parameter Request
    {
        $jadwal = JadwalWawancara::findOrFail($id);

        if ($jadwal->status === 'dibooking') {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal ini sudah dipilih oleh kandidat!'
                ], 422);
            }
            return back()->with('error', 'Jadwal ini sudah dipilih oleh kandidat!');
        }

        $jadwal->status = ($jadwal->status === 'tersedia') ? 'tidak_tersedia' : 'tersedia';
        $jadwal->save();

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status jadwal berhasil diubah.',
                'data' => $jadwal
            ], 200);
        }

        return back()->with('success', 'Status jadwal berhasil diubah.');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $divisi_id = Auth::user()->divisi->id;

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

        for ($date = $start; $date->lte($end); $date->addDay()) {
            if ($date->isWeekend()) continue;

            foreach ($jam_slots as $jam) {
                JadwalWawancara::firstOrCreate([
                    'divisi_id' => $divisi_id,
                    'tanggal' => $date->format('Y-m-d'),
                    'waktu_mulai' => $jam[0],
                    'waktu_selesai' => $jam[1],
                ], [
                    'status' => 'tersedia'
                ]);
            }
        }

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Slot jadwal berhasil digenerate!'
            ], 200);
        }

        return back()->with('success', 'Slot jadwal berhasil digenerate! Silakan klik kotak untuk menandai jadwal Tidak Bisa.');
    }

    public function updateBulk(Request $request)
    {
        $divisi_id = Auth::user()->divisi->id;

        // 1. Reset semua jadwal yang bukan 'dibooking' menjadi 'tersedia'
        JadwalWawancara::where('divisi_id', $divisi_id)
            ->where('status', '!=', 'dibooking')
            ->update(['status' => 'tersedia']);

        // 2. Jika ada array ID jadwal_merah, set menjadi 'tidak_tersedia'
        if ($request->has('jadwal_merah')) {
            JadwalWawancara::whereIn('id', $request->jadwal_merah)
                ->where('status', '!=', 'dibooking')
                ->update(['status' => 'tidak_tersedia']);
        }

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ketersediaan jadwal berhasil diperbarui!'
            ], 200);
        }

        return back()->with('success', 'Ketersediaan jadwal berhasil diperbarui!');
    }
}
