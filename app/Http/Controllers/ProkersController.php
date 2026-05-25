<?php

namespace App\Http\Controllers;

use App\Mail\KredensialKadivBaru;
use App\Models\Divisi;
use App\Models\Proker;
use App\Models\prokers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class ProkersController extends Controller
{
    public function index()
    {
        $prokers = Proker::with('divisi')->latest()->get();

        $totalDivisi = Divisi::count();
        $totalOpen = Divisi::where('is_open', true)->count();

        $statusOprecGlobal = ($totalDivisi > 0 && $totalOpen === $totalDivisi) ? 1 : 0;

        return view('po.proker.index', compact('prokers', 'statusOprecGlobal'));
    }

    public function updateStatusRecruitment(Request $request)
    {
        $request->validate([
            'status_recruitment' => 'required|in:0,1'
        ]);

        $isOpenValue = $request->status_recruitment == '1' ? true : false;

        Divisi::query()->update(['is_open' => $isOpenValue]);

        $pesan = $isOpenValue
            ? 'Pengumuman kelulusan resmi DIBUKA untuk semua divisi!'
            : 'Pengumuman kelulusan resmi DITUTUP untuk semua divisi!';

        return back()->with('success', $pesan);
    }

    public function updateKadiv(Request $request, $id)
    {
        $divisi = \App\Models\Divisi::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $divisi->kadiv_id,
        ]);

        if ($divisi->kadiv_id && $divisi->kadiv) {
            $user = $divisi->kadiv;

            $emailBerubah = $request->email !== $user->email;
            $passwordBaru = null;

            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($emailBerubah) {
                $passwordBaru = Str::random(8); 
                $updateData['password'] = Hash::make($passwordBaru);
            }

            $user->update($updateData);

            if ($emailBerubah && $passwordBaru) {
                Mail::to($user->email)->send(new KredensialKadivBaru($user, $passwordBaru));
            }

            return back()->with('success', 'Data Kepala Divisi berhasil diperbarui!' . ($emailBerubah ? ' Kredensial baru telah dikirim via email.' : ''));
        }

        return back()->with('error', 'Gagal memperbarui, Kepala Divisi tidak ditemukan.');
    }
}
