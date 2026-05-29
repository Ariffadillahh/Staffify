<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Kriteria;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileMatchingController extends Controller
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

        $pendaftarans = Pendaftaran::where('divisi_id', $divisi->id)
            ->whereHas('penilaians')
            ->with(['penilaians.kriteria'])
            ->get();

        $divisiLain = Divisi::where('id', '!=', $divisi->id)->get();

        if ($pendaftarans->isEmpty()) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'results' => [],
                    'divisi' => $divisi,
                    'divisiLain' => $divisiLain
                ], 200);
            }
            return view('kadiv.hasil_pm.index', ['results' => [], 'divisi' => $divisi, 'divisiLain' => $divisiLain]);
        }

        $results = $this->kalkulasiPM($pendaftarans, $divisi->id);

        // RESPON API FLUTTER
        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'results' => $results,
                'divisi' => $divisi,
                'divisiLain' => $divisiLain
            ], 200);
        }

        // RESPON WEB BLADE LAMA
        return view('kadiv.hasil_pm.index', compact('results', 'divisi', 'divisiLain'));
    }

    public function simpanKeputusan(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $message = "";

        if ($request->aksi == 'terima') {
            $pendaftaran->update(['status' => 'diterima']);
            $message = "Kandidat {$pendaftaran->nama_lengkap} berhasil DITERIMA.";
        } elseif ($request->aksi == 'tolak') {
            $pendaftaran->update(['status' => 'ditolak']);
            $message = "Kandidat {$pendaftaran->nama_lengkap} telah DITOLAK.";
        } elseif ($request->aksi == 'pindah') {
            $request->validate(['divisi_baru_id' => 'required|exists:divisis,id']);

            $pendaftaran->update([
                'divisi_id' => $request->divisi_baru_id,
                'status' => 'diterima'
            ]);
            $message = "Kandidat {$pendaftaran->nama_lengkap} berhasil dilempar ke divisi lain.";
        }

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $pendaftaran
            ], 200);
        }

        return back()->with('success', $message);
    }

    public function detailKalkulasi(Request $request, $id)
    {
        $divisi = Auth::user()->divisi;
        $pendaftaran = Pendaftaran::with(['penilaians.kriteria'])->findOrFail($id);

        $bobot_gap = [
            0  => ['bobot' => 5,   'ket' => 'Tidak ada selisih (Sesuai target)'],
            1  => ['bobot' => 4.5, 'ket' => 'Kelebihan 1 tingkat'],
            -1 => ['bobot' => 4,   'ket' => 'Kekurangan 1 tingkat'],
            2  => ['bobot' => 3.5, 'ket' => 'Kelebihan 2 tingkat'],
            -2 => ['bobot' => 3,   'ket' => 'Kekurangan 2 tingkat'],
            3  => ['bobot' => 2.5, 'ket' => 'Kelebihan 3 tingkat'],
            -3 => ['bobot' => 2,   'ket' => 'Kekurangan 2 tingkat'],
            4  => ['bobot' => 1.5, 'ket' => 'Kelebihan 4 tingkat'],
            -4 => ['bobot' => 1,   'ket' => 'Kekurangan 4 tingkat'],
        ];

        $detail_kriteria = [];
        $ncf = 0;
        $nsf = 0;
        $cf_bobots = [];
        $sf_bobots = [];

        foreach ($pendaftaran->penilaians as $penilaian) {
            $target = $penilaian->kriteria->nilai_target;
            $aktual = $penilaian->nilai;
            $gap = $aktual - $target;

            $konversi = $bobot_gap[$gap] ?? ['bobot' => 1, 'ket' => 'Sangat Kurang'];
            $bobot = $konversi['bobot'];

            if ($penilaian->kriteria->jenis_factor == 'core') {
                $ncf += $bobot;
                $cf_bobots[] = $bobot;
            } else {
                $nsf += $bobot;
                $sf_bobots[] = $bobot;
            }

            $detail_kriteria[] = [
                'nama' => $penilaian->kriteria->nama_kriteria,
                'jenis' => $penilaian->kriteria->jenis_factor,
                'target' => $target,
                'aktual' => $aktual,
                'gap' => $gap,
                'bobot' => $bobot,
                'keterangan' => $konversi['ket']
            ];
        }

        $count_cf = count($cf_bobots);
        $count_sf = count($sf_bobots);

        $avg_cf = $count_cf > 0 ? ($ncf / $count_cf) : 0;
        $avg_sf = $count_sf > 0 ? ($nsf / $count_sf) : 0;
        $total_value = ($avg_cf * 0.6) + ($avg_sf * 0.4);

        $ringkasan = [
            'avg_cf' => number_format($avg_cf, 2),
            'avg_sf' => number_format($avg_sf, 2),
            'total' => number_format($total_value, 2),
            'teks_cf' => $count_cf > 0 ? implode(' + ', $cf_bobots) : '0',
            'teks_sf' => $count_sf > 0 ? implode(' + ', $sf_bobots) : '0',
            'count_cf' => $count_cf,
            'count_sf' => $count_sf,
        ];

        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'pendaftaran' => $pendaftaran,
                'divisi' => $divisi,
                'detail_kriteria' => $detail_kriteria,
                'ringkasan' => $ringkasan
            ], 200);
        }

        return view('kadiv.hasil_pm.detail', compact('pendaftaran', 'divisi', 'detail_kriteria', 'ringkasan'));
    }

    private function kalkulasiPM($pendaftarans, $divisi_id)
    {
        $bobot_gap = [
            0 => 5,
            1 => 4.5,
            -1 => 4,
            2 => 3.5,
            -2 => 3,
            3 => 2.5,
            -3 => 2,
            4 => 1.5,
            -4 => 1
        ];

        $final_scores = [];

        foreach ($pendaftarans as $p) {
            $ncf = 0;
            $nsf = 0;
            $count_cf = 0;
            $count_sf = 0;

            foreach ($p->penilaians as $penilaian) {
                $target = $penilaian->kriteria->nilai_target;
                $aktual = $penilaian->nilai;
                $gap = $aktual - $target;

                $nilai_bobot = $bobot_gap[$gap] ?? 1;

                if ($penilaian->kriteria->jenis_factor == 'core') {
                    $ncf += $nilai_bobot;
                    $count_cf++;
                } else {
                    $nsf += $nilai_bobot;
                    $count_sf++;
                }
            }

            $avg_cf = $count_cf > 0 ? ($ncf / $count_cf) : 0;
            $avg_sf = $count_sf > 0 ? ($nsf / $count_sf) : 0;
            $total_value = ($avg_cf * 0.6) + ($avg_sf * 0.4);

            $final_scores[] = [
                'pendaftaran' => $p,
                'ncf' => number_format($avg_cf, 2),
                'nsf' => number_format($avg_sf, 2),
                'total' => number_format($total_value, 2),
            ];
        }

        usort($final_scores, function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        return $final_scores;
    }
}
