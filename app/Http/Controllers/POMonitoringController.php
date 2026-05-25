<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class POMonitoringController extends Controller
{
    public function jadwalWawancara(Request $request)
    {
        $divisis = Divisi::all();

        // Ambil ID Divisi dari parameter URL, default ke divisi pertama
        $selected_divisi_id = $request->divisi_id ?? ($divisis->first()->id ?? null);

        $divisi_terpilih = null;
        $kandidats = [];

        if ($selected_divisi_id) {
            // Mengambil data divisi terpilih
            $divisi_terpilih = Divisi::find($selected_divisi_id);

            // PERBAIKAN: Mengubah divisi_1_id menjadi divisi_id menyesuaikan database baru
            $kandidats = Pendaftaran::with('jadwalWawancara')
                ->where('divisi_id', $selected_divisi_id)
                ->whereNotNull('jadwal_wawancara_id')
                ->get()
                ->sortBy(function ($k) {
                    // Urutan berdasarkan waktu
                    return $k->jadwalWawancara->tanggal . ' ' . $k->jadwalWawancara->waktu_mulai;
                });
        }

        return view('po.monitoring.jadwal', compact('divisis', 'selected_divisi_id', 'divisi_terpilih', 'kandidats'));
    }

   public function kandidat(Request $request)
    {
        $divisis = Divisi::all();
        $selected_divisi_id = $request->divisi_id;

        $query = Pendaftaran::with(['divisi', 'penilaians.kriteria']);

        $divisi_terpilih = null;

        if ($selected_divisi_id) {
            $query->where('divisi_id', $selected_divisi_id);
            $divisi_terpilih = Divisi::find($selected_divisi_id);
        }

        $raw_kandidats = $query->get();

        $bobot_gap = [
            0  => 5, 1 => 4.5, -1 => 4, 2 => 3.5, -2 => 3, 3 => 2.5, -3 => 2, 4 => 1.5, -4 => 1
        ];

        foreach ($raw_kandidats as $kandidat) {
            $ncf = 0; $nsf = 0;
            $count_cf = 0; $count_sf = 0;

            foreach ($kandidat->penilaians as $penilaian) {
                $gap = $penilaian->nilai - $penilaian->kriteria->nilai_target;
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
            
            $kandidat->total_pm = ($avg_cf * 0.6) + ($avg_sf * 0.4);
        }

        $kandidats = $raw_kandidats->sort(function ($a, $b) {
            $a_is_diterima = $a->status === 'diterima' ? 1 : 0;
            $b_is_diterima = $b->status === 'diterima' ? 1 : 0;

            if ($a_is_diterima !== $b_is_diterima) {
                return $b_is_diterima <=> $a_is_diterima;
            }

            return $b->total_pm <=> $a->total_pm;
        })->values();

        return view('po.monitoring.kandidat', compact('divisis', 'selected_divisi_id', 'kandidats', 'divisi_terpilih'));
    }
}
