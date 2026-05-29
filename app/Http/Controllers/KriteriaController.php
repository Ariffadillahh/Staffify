<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KriteriaController extends Controller
{
    public function index(Request $request)
    {
        $divisi = Auth::user()->divisi;

        if (!$divisi) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Divisi belum diatur.'], 403);
            }
            return redirect('/')->with('error', 'Divisi belum diatur.');
        }

        $kriterias = Kriteria::where('divisi_id', $divisi->id)->get();

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'divisi' => $divisi,
                'kriterias' => $kriterias
            ], 200);
        }

        return view('kadiv.kriteria.index', compact('kriterias', 'divisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kriteria' => 'required|string|max:255',
            'jenis_factor' => 'required|in:core,secondary',
            'nilai_target' => 'required|integer|min:1|max:5',
        ]);

        $kriteria = Kriteria::create([
            'divisi_id' => Auth::user()->divisi->id,
            'nama_kriteria' => $request->nama_kriteria,
            'jenis_factor' => $request->jenis_factor,
            'nilai_target' => $request->nilai_target,
        ]);

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kriteria penilaian berhasil ditambahkan.',
                'data' => $kriteria
            ], 201);
        }

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
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Akses Ditolak.'], 403);
            }
            abort(403);
        }

        $kriteria->update([
            'nama_kriteria' => $request->nama_kriteria,
            'jenis_factor' => $request->jenis_factor,
            'nilai_target' => $request->nilai_target,
        ]);

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kriteria berhasil diperbarui!',
                'data' => $kriteria
            ], 200);
        }

        return back()->with('success', 'Kriteria berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $kriteria = Kriteria::findOrFail($id);

        if ($kriteria->divisi_id !== Auth::user()->divisi->id) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Akses Ditolak.'], 403);
            }
            abort(403);
        }

        $kriteria->delete();

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kriteria berhasil dihapus.'
            ], 200);
        }

        return back()->with('success', 'Kriteria berhasil dihapus.');
    }
}
