<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Kriteria;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileMatchingController extends Controller
{
    public function index()
    {
        $divisi = Auth::user()->divisi;

        $pendaftarans = Pendaftaran::where('divisi_id', $divisi->id)
            ->whereHas('penilaians')
            ->with(['penilaians.kriteria'])
            ->get();

        $divisiLain = Divisi::where('id', '!=', $divisi->id)->get();

        if ($pendaftarans->isEmpty()) {
            return view('kadiv.hasil_pm.index', ['results' => [], 'divisi' => $divisi, 'divisiLain' => $divisiLain]);
        }

        $results = $this->kalkulasiPM($pendaftarans, $divisi->id);

        return view('kadiv.hasil_pm.index', compact('results', 'divisi', 'divisiLain'));
    }

    public function simpanKeputusan(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        if ($request->aksi == 'terima') {
            $pendaftaran->update(['status' => 'diterima']);
            return back()->with('success', "Kandidat {$pendaftaran->nama_lengkap} berhasil DITERIMA.");
        } elseif ($request->aksi == 'tolak') {
            $pendaftaran->update(['status' => 'ditolak']);
            return back()->with('success', "Kandidat {$pendaftaran->nama_lengkap} telah DITOLAK.");
        } elseif ($request->aksi == 'pindah') {
            $request->validate(['divisi_baru_id' => 'required|exists:divisis,id']);

            $pendaftaran->update([
                'divisi_id' => $request->divisi_baru_id,
                'status' => 'diterima'
            ]);

            return back()->with('success', "Kandidat {$pendaftaran->nama_lengkap} berhasil dilempar ke divisi lain.");
        }

        return back();
    }

    public function detailKalkulasi($id)
    {
        $divisi = Auth::user()->divisi;
        $pendaftaran = \App\Models\Pendaftaran::with(['penilaians.kriteria'])->findOrFail($id);

        // $userLogin = Auth::user();

        // if ($userLogin->role !== 'po' && $userLogin->role !== 'vpo') {
        //     if ($pendaftaran->divisi_id !== ($userLogin->divisi->id ?? null)) {
        //         abort(403, 'Akses ditolak. Anda hanya dapat melihat detail nilai dari divisi Anda sendiri.');
        //     }
        // }

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

        // ARRAY BARU UNTUK MENAMPUNG DETAIL ANGKA PENJUMLAHAN
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
                $cf_bobots[] = $bobot; // Catat angkanya
            } else {
                $nsf += $bobot;
                $sf_bobots[] = $bobot; // Catat angkanya
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

        return view('kadiv.hasil_pm.detail', compact('pendaftaran', 'divisi', 'detail_kriteria', 'ringkasan'));
    }

    private function kalkulasiPM($pendaftarans, $divisi_id)
    {
        $kriterias = Kriteria::where('divisi_id', $divisi_id)->get();
        $final_scores = [];

        // Tabel Konversi Bobot Nilai Gap
        $bobot_gap = [
            0  => 5,   // Tidak ada selisih (Sesuai target)
            1  => 4.5, // Kelebihan 1 tingkat
            -1 => 4,   // Kekurangan 1 tingkat
            2  => 3.5, // Kelebihan 2 tingkat
            -2 => 3,   // Kekurangan 2 tingkat
            3  => 2.5, // Kelebihan 3 tingkat
            -3 => 2,   // Kekurangan 3 tingkat
            4  => 1.5, // Kelebihan 4 tingkat
            -4 => 1,   // Kekurangan 4 tingkat
        ];

        foreach ($pendaftarans as $p) {
            $ncf = 0; // Total Nilai Core Factor
            $nsf = 0; // Total Nilai Secondary Factor
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

            // Hitung rata-rata NCF dan NSF
            $avg_cf = $count_cf > 0 ? ($ncf / $count_cf) : 0;
            $avg_sf = $count_sf > 0 ? ($nsf / $count_sf) : 0;

            // Hitung Total (60% Core, 40% Secondary)
            $total_value = ($avg_cf * 0.6) + ($avg_sf * 0.4);

            $final_scores[] = [
                'pendaftaran' => $p,
                'ncf' => number_format($avg_cf, 2),
                'nsf' => number_format($avg_sf, 2),
                'total' => number_format($total_value, 2),
            ];
        }

        // Urutkan (Ranking) berdasarkan nilai total terbesar
        usort($final_scores, function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        return $final_scores;
    }
}
