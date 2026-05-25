<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KriteriaController extends Controller
{
    public function index()
    {
        $divisi = Auth::user()->divisi;
        if (!$divisi) {
            return redirect('/')->with('error', 'Divisi belum diatur.');
        }

        $kriterias = Kriteria::where('divisi_id', $divisi->id)->get();
        return view('kadiv.kriteria.index', compact('kriterias', 'divisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kriteria' => 'required|string|max:255',
            'jenis_factor' => 'required|in:core,secondary',
            'nilai_target' => 'required|integer|min:1|max:5',
        ]);

        Kriteria::create([
            'divisi_id' => Auth::user()->divisi->id,
            'nama_kriteria' => $request->nama_kriteria,
            'jenis_factor' => $request->jenis_factor,
            'nilai_target' => $request->nilai_target,
        ]);

        return back()->with('success', 'Kriteria penilaian berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kriteria' => 'required|string|max:255',
            'jenis_factor' => 'required|in:core,secondary',
            'nilai_target' => 'required|integer|min:1|max:5',
        ]);

        $kriteria = Kriteria::findOrFail($id);

        if ($kriteria->divisi_id !== Auth::user()->divisi->id) {
            abort(403);
        }

        $kriteria->update([
            'nama_kriteria' => $request->nama_kriteria,
            'jenis_factor' => $request->jenis_factor,
            'nilai_target' => $request->nilai_target,
        ]);

        return back()->with('success', 'Kriteria berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kriteria = Kriteria::findOrFail($id);

        // Pastikan hanya bisa menghapus kriteria divisinya sendiri
        if ($kriteria->divisi_id !== Auth::user()->divisi->id) {
            abort(403);
        }

        $kriteria->delete();
        return back()->with('success', 'Kriteria berhasil dihapus.');
    }
}
