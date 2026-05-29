<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Pendaftaran;
use App\Models\JadwalWawancara;
use App\Models\User;
use App\Models\Proker;
use App\Mail\NotifikasiJadwalKadiv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PendaftaransController extends Controller
{
    public function create(Request $request)
    {
        $totalDivisi = Divisi::count();

        $allDivisiClosed = $totalDivisi > 0 && Divisi::where('is_open', false)->count() === $totalDivisi;

        if ($allDivisiClosed) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Pendaftaran staff Kabinet Simpul Perubahan belum dibuka resmi.'
                ], 403);
            }
            abort(403, 'Akses ditolak. Pendaftaran staff Kabinet Simpul Perubahan belum dibuka resmi.');
        }

        $divisis = Divisi::all();

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json(['success' => true, 'divisis' => $divisis], 200);
        }

        return view('publik.daftar', compact('divisis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'             => 'required|string|max:255',
            'nim'                      => 'required|numeric|unique:pendaftarans,nim',
            'email'                    => 'required|email',
            'no_whatsapp'              => 'required|numeric',
            'alasan_mengikuti_proker'  => 'nullable|string',
            'divisi_id'                => 'required|exists:divisis,id',
            'alasan'                   => 'required|string',
            'bersedia_pindah_divisi'   => 'nullable',
            'pengalaman'               => 'nullable|string',
            'foto'                     => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->hasFile('foto') ? $request->file('foto')->store('foto_pendaftaran', 'public') : null;
        $proker = Proker::latest()->first();

        // Mengatasi perbedaan input checkbox boolean dari Web vs JSON murni
        $bersedia = $request->has('bersedia_pindah_divisi')
            ? ($request->bersedia_pindah_divisi === 'false' ? false : true)
            : false;

        $pendaftaran = Pendaftaran::create([
            'proker_id'                => $proker ? $proker->id : null,
            'nama_lengkap'             => $request->nama_lengkap,
            'nim'                      => $request->nim,
            'email'                    => $request->email,
            'no_whatsapp'              => $request->no_whatsapp,
            'alasan_mengikuti_proker'  => $request->alasan_mengikuti_proker,
            'divisi_id'                => $request->divisi_id,
            'alasan'                   => $request->alasan,
            'bersedia_pindah_divisi'   => $bersedia,
            'pengalaman'               => $request->pengalaman,
            'foto'                     => $path,
            'status'                   => 'pending'
        ]);

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data pendaftaran berhasil disimpan.',
                'pendaftaran_id' => $pendaftaran->id,
            ], 201);
        }

        return redirect()->route('daftar.jadwal', $pendaftaran->id);
    }

    public function pilihJadwal(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::with('divisi')->findOrFail($id);
        $divisi_id = $pendaftaran->divisi_id;

        $jadwals = JadwalWawancara::where('divisi_id', $divisi_id)
            ->orderBy('tanggal')
            ->orderBy('waktu_mulai')
            ->get();

        $tanggals = JadwalWawancara::where('divisi_id', $divisi_id)
            ->distinct()
            ->orderBy('tanggal')
            ->pluck('tanggal');

        $waktu_slots = JadwalWawancara::where('divisi_id', $divisi_id)
            ->select('waktu_mulai', 'waktu_selesai')
            ->distinct()
            ->orderBy('waktu_mulai')
            ->get();

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'pendaftaran' => $pendaftaran,
                'tanggals' => $tanggals,
                'waktu_slots' => $waktu_slots,
                'jadwals' => $jadwals
            ], 200);
        }

        $jadwalsGrouped = $jadwals->groupBy('tanggal');
        return view('publik.pilih_jadwal', compact('pendaftaran', 'jadwalsGrouped', 'jadwals', 'tanggals', 'waktu_slots'));
    }

    public function simpanJadwal(Request $request, $id)
    {
        $request->validate(['jadwal_id' => 'required|exists:jadwal_wawancaras,id']);

        $pendaftaran = Pendaftaran::findOrFail($id);
        $jadwal = JadwalWawancara::findOrFail($request->jadwal_id);

        if ($jadwal->status !== 'tersedia') {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Maaf, jadwal ini baru saja diambil oleh kandidat lain.'], 422);
            }
            return back()->with('error', 'Maaf, jadwal ini baru saja diambil oleh kandidat lain. Silakan pilih jadwal lain.');
        }

        $jadwal->update(['status' => 'dibooking', 'pendaftaran_id' => $pendaftaran->id]);
        $pendaftaran->update(['jadwal_wawancara_id' => $jadwal->id]);

        $kadiv = User::where('role', 'kadiv')->whereHas('divisi', function ($q) use ($pendaftaran) {
            $q->where('id', $pendaftaran->divisi_id);
        })->first();

        if ($kadiv) {
            try {
                Mail::to($kadiv->email)->send(new NotifikasiJadwalKadiv($pendaftaran, $jadwal));
            } catch (\Exception $e) {
            }
        }

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal wawancara berhasil dibooking!',
                'nama' => $pendaftaran->nama_lengkap,
                'link_wawancara' => $pendaftaran->divisi->link_wawancara ?? null
            ], 200);
        }

        return redirect()->route('daftar.sukses')->with([
            'nama' => $pendaftaran->nama_lengkap,
            'link_wawancara' => $pendaftaran->divisi->link_wawancara ?? null
        ]);
    }

    public function statusPendaftaran() {
        $proker = Proker::latest()->first(); 

        $isOpen = false;

        if ($proker) {
            $isOpen = $proker->divisi()->where('is_open', true)->exists();
        }

        return response()->json([
            'success' => true,
            'is_open' => $isOpen
        ], 200);
    }

    public function pengumuman(Request $request)
    {
        $totalDivisi = Divisi::count();

        $allDivisiOpen = $totalDivisi > 0 && Divisi::where('is_open', true)->count() === $totalDivisi;

        if ($allDivisiOpen) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Pengumuman resmi kelulusan telah selesai dipublikasikan.'
                ], 403);
            }
            abort(403, 'Akses ditolak. Pengumuman resmi kelulusan telah selesai dipublikasikan.');
        }

        $hasil = null;
        $error = null;

        if ($request->has('nim') && $request->nim != '') {
            $pendaftaran = Pendaftaran::with('divisi')
                ->where('nim', $request->nim)
                ->first();

            if (!$pendaftaran) {
                $error = 'Maaf, NIM yang Anda masukkan tidak terdaftar di sistem kami.';
            } else {
                $hasil = $pendaftaran;
            }
        }

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => $hasil ? true : false,
                'hasil' => $hasil,
                'error' => $error
            ], $hasil ? 200 : 200);
        }

        return view('publik.pengumuman', compact('hasil', 'error'));
    }
}
