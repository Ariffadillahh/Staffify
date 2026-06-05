<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    public function create(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::with(['divisi', 'jadwalWawancara', 'penilaians'])->findOrFail($id);

        $kriterias = Kriteria::where('divisi_id', Auth::user()->divisi->id)->get();
        $sudahDinilai = Penilaian::where('pendaftaran_id', $id)->exists();

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'pendaftaran' => $pendaftaran,
                'kriterias' => $kriterias,
                'sudah_dinilai' => $sudahDinilai
            ], 200);
        }

        return view('kadiv.penilaian.create', compact('pendaftaran', 'kriterias', 'sudahDinilai'));
    }

    public function store(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $request->validate([
            'nilai' => 'required|array',
            'nilai.*' => 'required|integer|min:1|max:5',
        ]);

        foreach ($request->nilai as $kriteria_id => $nilai_input) {
            Penilaian::updateOrCreate(
                [
                    'pendaftaran_id' => $pendaftaran->id,
                    'kriteria_id'    => $kriteria_id
                ],
                [
                    'nilai' => $nilai_input
                ]
            );
        }

        $pendaftaran->update(['status' => 'dinilai']);

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Hasil wawancara untuk ' . $pendaftaran->nama_lengkap . ' berhasil disimpan!',
            ], 200);
        }

        return redirect()->route('jadwal.index')->with('success', 'Hasil wawancara untuk ' . $pendaftaran->nama_lengkap . ' berhasil disimpan!');
    }
}
