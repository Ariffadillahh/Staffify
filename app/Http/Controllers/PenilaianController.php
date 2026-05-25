<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    public function create($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        if ($pendaftaran->divisi_id !== Auth::user()->divisi->id) {
            abort(403, 'Anda tidak memiliki akses untuk menilai kandidat ini.');
        }

        $kriterias = Kriteria::where('divisi_id', Auth::user()->divisi->id)->get();

        $sudahDinilai = Penilaian::where('pendaftaran_id', $id)->exists();

        return view('kadiv.penilaian.create', compact('pendaftaran', 'kriterias', 'sudahDinilai'));
    }

    // Fungsi untuk memproses data dari form
    public function store(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        // Validasi bahwa input 'nilai' adalah array dan wajib diisi
        $request->validate([
            'nilai' => 'required|array',
            'nilai.*' => 'required|integer|min:1|max:5', // Pastikan isinya 1-5
        ]);

        // Looping data nilai yang dikirim dari form (key = kriteria_id, value = nilai 1-5)
        foreach ($request->nilai as $kriteria_id => $nilai_input) {
            Penilaian::create([
                'pendaftaran_id' => $pendaftaran->id,
                'kriteria_id' => $kriteria_id,
                'nilai' => $nilai_input
            ]);
        }

        // BARIS INI KITA MATIKAN
        $pendaftaran->update(['status' => 'dinilai']);

        // Kembalikan Kadiv ke halaman jadwal setelah selesai menilai
        return redirect()->route('jadwal.index')->with('success', 'Hasil wawancara untuk ' . $pendaftaran->nama_lengkap . ' berhasil disimpan!');
    }
}
