@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Hasil & Keputusan Wawancara</h2>
                <p class="text-blue-600 font-bold uppercase text-sm mt-1">DIVISI {{ $divisi->nama_divisi }}</p>
            </div>
            <div class="flex items-center gap-2 bg-blue-50 px-4 py-2 rounded-lg border border-blue-100 text-blue-800">
                <i class="ph ph-users-four text-xl"></i>
                <span class="text-xs font-bold uppercase">Kuota: {{ $divisi->kuota_staff }} Orang</span>
            </div>
        </div>

        <div class="mb-6 bg-slate-800 text-slate-300 p-5 rounded-xl flex gap-4 items-center shadow-lg">
            <i class="ph ph-info text-3xl text-amber-400"></i>
            <div class="text-sm">
                <p class="font-bold text-white mb-0.5">Penentuan Kelulusan Staff</p>
                <p class="opacity-80">Sistem merekomendasikan kandidat berdasarkan nilai (60% Core + 40% Secondary). Anda
                    sebagai Kadiv berhak menentukan keputusan akhir: <strong class="text-emerald-400">Terima</strong>,
                    <strong class="text-rose-400">Tolak</strong>, atau <strong class="text-amber-400">Lempar ke Divisi
                        Lain</strong>.
                </p>
            </div>
        </div>

        @if (session('success'))
            <div
                class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl font-bold text-sm flex items-center gap-2">
                <i class="ph-fill ph-check-circle text-xl text-emerald-500"></i> {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse whitespace-nowrap">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-gray-200 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">
                            <th class="p-4 w-16 text-center">Rank</th>
                            <th class="p-4">Kandidat</th>
                            <th class="p-4 text-center">Total Nilai</th>
                            <th class="p-4 text-center">Rekomendasi Sistem</th>
                            <th class="p-4 text-center">Keputusan Kadiv</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php
                            $counterLolos = 0;
                        @endphp

                        @forelse($results as $index => $res)
                            @php
                                $rank = $index + 1;
                                $isLolosSistem = $rank <= $divisi->kuota_staff;
                                $status = $res['pendaftaran']->status;

                                $sahDiterimaKuota = false;

                                if ($status === 'diterima' && $counterLolos < $divisi->kuota_staff) {
                                    $sahDiterimaKuota = true;
                                    $counterLolos++;
                                }
                            @endphp

                            <tr
                                class="hover:bg-slate-50 transition {{ $sahDiterimaKuota ? 'bg-blue-50/70 font-medium text-slate-950' : '' }}">
                                <td class="p-4 text-center">
                                    @if ($rank == 1)
                                        <div
                                            class="w-8 h-8 bg-amber-400 text-white rounded-full flex items-center justify-center mx-auto shadow-sm">
                                            <i class="ph-fill ph-crown"></i>
                                        </div>
                                    @elseif($isLolosSistem)
                                        <span class="font-black text-slate-800">{{ $rank }}</span>
                                    @else
                                        <span class="font-medium text-slate-400">{{ $rank }}</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <a href="{{ route('pm.detail', $res['pendaftaran']->id) }}"
                                        class="flex items-center gap-3 group">
                                        <img src="{{ Storage::url($res['pendaftaran']->foto) }}"
                                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($res['pendaftaran']->nama_lengkap) }}&background=random';"
                                            class="w-10 h-10 rounded-full object-cover border border-gray-200 group-hover:border-blue-400 transition">
                                        <div>
                                            <p
                                                class="font-bold text-gray-800 leading-tight group-hover:text-blue-600 transition flex items-center gap-1.5">
                                                {{ $res['pendaftaran']->nama_lengkap }}
                                                @if ($sahDiterimaKuota)
                                                    <span
                                                        class="text-blue-600 bg-blue-100 text-[9px] px-1.5 py-0.5 rounded-md font-black uppercase tracking-wider scale-95">Lolos
                                                        Formasi</span>
                                                @endif
                                            </p>
                                            <p class="text-[10px] text-gray-500 font-medium tracking-tight">
                                                {{ $res['pendaftaran']->nim }} <span
                                                    class="text-[9px] text-blue-500 font-normal underline opacity-0 group-hover:opacity-100 transition-all ml-0.5">Detail
                                                    PM →</span>
                                            </p>
                                        </div>
                                    </a>
                                </td>
                                <td class="p-4 text-center font-black text-lg text-blue-700">{{ $res['total'] }}</td>
                                <td class="p-4 text-center">
                                    @if ($isLolosSistem)
                                        <span
                                            class="bg-emerald-50 text-emerald-600 border border-emerald-200 px-3 py-1.5 rounded-full text-[10px] font-black uppercase">Masuk
                                            Kuota</span>
                                    @else
                                        <span
                                            class="bg-slate-50 text-slate-500 border border-slate-200 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase">Cadangan</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    @if ($status == 'diterima')
                                        <div
                                            class="inline-flex items-center gap-1.5 bg-emerald-500 text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wide shadow-sm">
                                            <i class="ph-fill ph-check-circle text-lg"></i> DITERIMA
                                        </div>
                                    @elseif($status == 'ditolak')
                                        <div
                                            class="inline-flex items-center gap-1.5 bg-rose-100 text-rose-700 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wide border border-rose-200">
                                            <i class="ph-fill ph-x-circle text-lg"></i> DITOLAK
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center gap-2">
                                            <form action="{{ route('pm.keputusan', $res['pendaftaran']->id) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="aksi" value="terima">
                                                <button type="submit"
                                                    onclick="return confirm('Yakin ingin MENERIMA kandidat ini?')"
                                                    class="bg-emerald-500 hover:bg-emerald-600 text-white w-9 h-9 rounded-lg flex items-center justify-center shadow-sm transition"
                                                    title="Terima Kandidat">
                                                    <i class="ph ph-check font-bold text-lg"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('pm.keputusan', $res['pendaftaran']->id) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="aksi" value="tolak">
                                                <button type="submit"
                                                    onclick="return confirm('Yakin ingin MENOLAK kandidat ini?')"
                                                    class="bg-rose-500 hover:bg-rose-600 text-white w-9 h-9 rounded-lg flex items-center justify-center shadow-sm transition"
                                                    title="Tolak Kandidat">
                                                    <i class="ph ph-x font-bold text-lg"></i>
                                                </button>
                                            </form>

                                            <button type="button"
                                                onclick="openPindahModal('{{ $res['pendaftaran']->id }}', '{{ $res['pendaftaran']->nama_lengkap }}')"
                                                class="bg-amber-500 hover:bg-amber-600 text-white w-9 h-9 rounded-lg flex items-center justify-center shadow-sm transition"
                                                title="Lempar ke Divisi Lain">
                                                <i class="ph ph-arrows-left-right font-bold text-lg"></i>
                                            </button>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center text-gray-400">
                                    <i class="ph ph-chart-bar-horizontal text-5xl mb-3 block opacity-20"></i>
                                    <p class="font-medium">Belum ada data kandidat yang dinilai.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modalPindah"
        class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="ph-fill ph-arrows-left-right text-amber-500"></i> Pindahkan Kandidat
                </h3>
                <button onclick="closePindahModal()" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <i class="ph ph-x text-xl font-bold"></i>
                </button>
            </div>

            <form id="formPindah" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="aksi" value="pindah">

                <p class="text-sm text-slate-600 mb-4">Anda akan memindahkan kandidat <strong id="namaKandidatModal"
                        class="text-slate-800"></strong> ke divisi lain. Kandidat akan hilang dari daftar Anda dan
                    diteruskan ke Kadiv divisi yang baru.</p>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Divisi Tujuan
                        <span class="text-red-500">*</span></label>
                    <select name="divisi_baru_id" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="" disabled selected>-- Pilih Divisi --</option>
                        @foreach ($divisiLain as $divLain)
                            <option value="{{ $divLain->id }}">{{ $divLain->nama_divisi }} (Kuota:
                                {{ $divLain->kuota_staff }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closePindahModal()"
                        class="px-5 py-2.5 bg-slate-100 text-slate-700 font-bold text-sm rounded-xl transition hover:bg-slate-200">Batal</button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-amber-500 text-white font-bold text-sm rounded-xl shadow-md transition hover:bg-amber-600 flex items-center gap-2">
                        <i class="ph ph-paper-plane-tilt text-lg"></i> Pindahkan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openPindahModal(id, nama) {
            document.getElementById('namaKandidatModal').innerText = nama;

            let form = document.getElementById('formPindah');
            let baseUrl = "{{ route('pm.keputusan', 'DYNAMIC_ID') }}";

            form.action = baseUrl.replace('DYNAMIC_ID', id);

            document.getElementById('modalPindah').classList.remove('hidden');
        }

        function closePindahModal() {
            document.getElementById('modalPindah').classList.add('hidden');
        }
    </script>
@endsection
