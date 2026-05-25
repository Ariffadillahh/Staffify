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
    public function create()
    {
        $divisis = Divisi::all();
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

        $pendaftaran = Pendaftaran::create([
            'proker_id'                => $proker ? $proker->id : null,
            'nama_lengkap'             => $request->nama_lengkap,
            'nim'                      => $request->nim,
            'email'                    => $request->email,
            'no_whatsapp'              => $request->no_whatsapp,
            'alasan_mengikuti_proker'  => $request->alasan_mengikuti_proker,
            'divisi_id'                => $request->divisi_id,
            'alasan'                   => $request->alasan,
            'bersedia_pindah_divisi'   => $request->has('bersedia_pindah_divisi') ? true : false,
            'pengalaman'               => $request->pengalaman,
            'foto'                     => $path,
            'status'                   => 'pending'
        ]);

        return redirect()->route('daftar.jadwal', $pendaftaran->id);
    }

    // public function pilihJadwal($id)
    // {
    //     $pendaftaran = Pendaftaran::findOrFail($id);

    //     $divisi_id = $pendaftaran->divisi_id;

    //     $jadwals = JadwalWawancara::where('divisi_id', $divisi_id)
    //         ->where('tanggal', '>=', now()->toDateString())
    //         ->orderBy('tanggal')
    //         ->orderBy('waktu_mulai')
    //         ->get()
    //         ->groupBy('tanggal');

    //     $tanggals = JadwalWawancara::where('divisi_id', $divisi_id)
    //         ->where('tanggal', '>=', now()->toDateString())
    //         ->distinct()
    //         ->pluck('tanggal');

    //     $waktu_slots = JadwalWawancara::where('divisi_id', $divisi_id)
    //         ->select('waktu_mulai', 'waktu_selesai')
    //         ->distinct()
    //         ->orderBy('waktu_mulai')
    //         ->get();

    //     return view('publik.pilih_jadwal', compact('pendaftaran', 'jadwals', 'tanggals', 'waktu_slots'));
    // }

    public function pilihJadwal($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $divisi_id = $pendaftaran->divisi_id;

        $jadwals = JadwalWawancara::where('divisi_id', $divisi_id)
            ->orderBy('tanggal')
            ->orderBy('waktu_mulai')
            ->get()
            ->groupBy('tanggal');

        $tanggals = JadwalWawancara::where('divisi_id', $divisi_id)
            ->distinct()
            ->orderBy('tanggal')
            ->pluck('tanggal');

        $waktu_slots = JadwalWawancara::where('divisi_id', $divisi_id)
            ->select('waktu_mulai', 'waktu_selesai')
            ->distinct()
            ->orderBy('waktu_mulai')
            ->get();

        return view('publik.pilih_jadwal', compact('pendaftaran', 'jadwals', 'tanggals', 'waktu_slots'));
    }

    public function simpanJadwal(Request $request, $id)
    {
        $request->validate(['jadwal_id' => 'required|exists:jadwal_wawancaras,id']);

        $pendaftaran = Pendaftaran::findOrFail($id);
        $jadwal = JadwalWawancara::findOrFail($request->jadwal_id);

        if ($jadwal->status !== 'tersedia') {
            return back()->with('error', 'Maaf, jadwal ini baru saja diambil oleh kandidat lain. Silakan pilih jadwal lain.');
        }

        $jadwal->update(['status' => 'dibooking', 'pendaftaran_id' => $pendaftaran->id]);
        $pendaftaran->update(['jadwal_wawancara_id' => $jadwal->id]);

        $kadiv = User::where('role', 'kadiv')->whereHas('divisi', function ($q) use ($pendaftaran) {
            $q->where('id', $pendaftaran->divisi_id);
        })->first();

        if ($kadiv) {
            Mail::to($kadiv->email)->send(new NotifikasiJadwalKadiv($pendaftaran, $jadwal));
        }

        // PERBAIKAN 2: Ganti relasi divisi1 menjadi divisi
        return redirect()->route('daftar.sukses')->with([
            'nama' => $pendaftaran->nama_lengkap,
            'link_wawancara' => $pendaftaran->divisi->link_wawancara ?? null
        ]);
    }

    public function pengumuman(Request $request)
    {
        $totalDivisi = \App\Models\Divisi::count();
        $allDivisiOpen = $totalDivisi > 0 && \App\Models\Divisi::where('is_open', true)->count() === $totalDivisi;

        if (!$allDivisiOpen) {
            abort(403, 'Akses ditolak. Pengumuman resmi kelulusan belum dibuka oleh panitia.');
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
                if ($pendaftaran->divisi && $pendaftaran->divisi->is_open) {
                    $hasil = $pendaftaran;
                } else {
                    $error = 'Pengumuman untuk Divisi ' . ($pendaftaran->divisi->nama_divisi ?? '') . ' belum dibuka. Silakan cek kembali secara berkala.';
                }
            }
        }

        return view('publik.pengumuman', compact('hasil', 'error'));
    }
}
