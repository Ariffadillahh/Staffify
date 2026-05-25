@extends('layouts.admin')

@section('content')
    <div class="max-w-6xl mx-auto">

        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Dashboard Divisi</h2>
            <p class="text-blue-600 font-bold uppercase text-sm mt-1">
                {{ $divisi->nama_divisi }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm flex items-center gap-5">
                <div
                    class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                    <i class="ph ph-users text-3xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider mb-1">Kuota Staff</p>
                    <h3 class="text-3xl font-black text-gray-800">{{ $kuota }} <span
                            class="text-sm font-medium text-gray-400">Orang</span></h3>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm flex items-center gap-5">
                <div
                    class="w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0">
                    <i class="ph ph-user-check text-3xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider mb-1">Calon Staff</p>
                    <h3 class="text-3xl font-black text-gray-800">{{ $jumlahPendaftar }} <span
                            class="text-sm font-medium text-gray-400">Pendaftar</span></h3>
                </div>
            </div>

            <div
                class="bg-slate-900 rounded-xl border border-slate-800 p-6 shadow-md flex items-center gap-5 relative overflow-hidden">
                <i class="ph ph-clock-countdown absolute -right-4 -bottom-4 text-7xl text-slate-800 opacity-50"></i>

                <div
                    class="w-14 h-14 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-amber-400 flex-shrink-0 z-10">
                    <i class="ph ph-calendar-star text-3xl"></i>
                </div>
                <div class="z-10">
                    <p class="text-sm text-slate-400 font-semibold uppercase tracking-wider mb-1">Wawancara Terdekat</p>
                    @if ($jadwalTerdekat)
                        <h3 class="text-lg font-bold text-white leading-tight truncate w-32 md:w-full">
                            {{ $jadwalTerdekat->pendaftaran->nama_lengkap ?? 'Kandidat' }}</h3>
                        <p class="text-amber-400 text-xs font-bold mt-1">
                            {{ \Carbon\Carbon::parse($jadwalTerdekat->tanggal)->translatedFormat('d M') }},
                            {{ \Carbon\Carbon::parse($jadwalTerdekat->waktu_mulai)->format('H:i') }}
                        </p>
                    @else
                        <h3 class="text-base font-bold text-slate-500 mt-2">Belum ada jadwal</h3>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 bg-gray-50 p-5 flex items-center gap-3">
                <i class="ph ph-link text-2xl text-indigo-600"></i>
                <h3 class="font-bold text-gray-800 text-lg">Tautan Ruang Wawancara</h3>
            </div>
            <div class="p-6 md:p-8">
                <p class="text-gray-500 mb-6 text-sm">
                    Masukkan tautan <strong>Google Meet</strong> atau <strong>Zoom</strong> untuk sesi wawancara divisi
                    Anda. Tautan ini akan dikirimkan otomatis atau ditampilkan di halaman pendaftar yang telah membooking
                    jadwal.
                </p>

                <form action="{{ route('kadiv.update_link') }}" method="POST"
                    class="flex flex-col md:flex-row gap-4 items-end">
                    @csrf
                    <div class="flex-1 w-full">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">URL / Link Meeting</label>
                        <input type="url" name="link_wawancara"
                            value="{{ old('link_wawancara', $divisi->link_wawancara) }}"
                            placeholder="Contoh: https://meet.google.com/abc-defg-hij"
                            class="w-full border-gray-300 rounded-lg p-3 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-gray-50">
                    </div>
                    <button type="submit"
                        class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg shadow-md transition-colors flex items-center justify-center gap-2">
                        <i class="ph ph-floppy-disk"></i> Simpan Tautan
                    </button>
                </form>

                @if ($divisi->link_wawancara)
                    <div
                        class="mt-6 p-4 border border-indigo-100 bg-indigo-50 rounded-lg flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="flex-1 w-full overflow-hidden">
                            <p class="text-xs font-bold text-indigo-400 uppercase mb-1">Tautan Saat Ini:</p>
                            <a href="{{ $divisi->link_wawancara }}" target="_blank"
                                class="text-indigo-700 font-medium hover:underline block truncate w-full">
                                {{ $divisi->link_wawancara }}
                            </a>
                        </div>

                        <div class="flex items-center gap-2 w-full md:w-auto">
                            <a href="{{ $divisi->link_wawancara }}" target="_blank"
                                class="flex-1 md:flex-none flex items-center justify-center gap-2 h-10 px-4 bg-white border border-indigo-200 rounded-lg text-indigo-600 shadow-sm hover:bg-indigo-50 hover:text-indigo-800 transition font-semibold text-sm">
                                <i class="ph ph-arrow-square-out text-lg"></i> Buka
                            </a>

                            <form action="{{ route('kadiv.delete_link') }}" method="POST" class="flex-1 md:flex-none m-0"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus tautan GMeet/Zoom ini? Kandidat tidak akan bisa melihat tautan ini lagi.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 h-10 px-4 bg-white border border-red-200 rounded-lg text-red-600 shadow-sm hover:bg-red-50 hover:text-red-700 transition font-semibold text-sm">
                                    <i class="ph ph-trash text-lg"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
