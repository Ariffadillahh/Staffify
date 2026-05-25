@extends('layouts.admin')

@section('content')
    @if (session('success'))
        <div
            class="mb-6 bg-emerald-50 border border-emerald-200 p-4 rounded-xl flex items-center gap-3 text-emerald-800 font-bold text-sm shadow-sm">
            <i class="ph-fill ph-check-circle text-xl text-emerald-500"></i>
            {{ session('success') }}
        </div>
    @endif


    <div class="">
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Dashboard Proker</h1>
            <p class="text-slate-500 font-medium mt-1">Pantau statistik, deskripsi, dan kesiapan divisi untuk setiap program
                kerja Kabinet Simpul Perubahan.</p>
        </div>

        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                    <i class="ph-fill ph-door text-blue-600 text-xl"></i> Kontrol Gerbang Pendaftaran (Oprec)
                </h3>
                <p class="text-xs font-medium text-slate-500 mt-1 leading-relaxed">
                    Gunakan sakelar di samping untuk mengatur masa registrasi. Pilih <span
                        class="text-emerald-600 font-bold">DIBUKA (Buka Publik)</span> agar mahasiswa dapat mengakses dan
                    mengisi formulir pendaftaran staff, atau ubah ke <span class="text-rose-600 font-bold">DITUTUP (Kunci
                        Pendaftaran)</span> jika kuota waktu pendaftaran telah habis dan data siap dievaluasi.
                </p>
            </div>

            <form action="{{ route('po.status_recruitment.update') }}" method="POST" class="shrink-0 w-full sm:w-auto">
                @csrf
                <div class="flex bg-slate-100 p-1 rounded-xl border border-slate-200/60 w-full sm:w-auto">
                    <label class="flex-1 sm:flex-initial">
                        <input type="radio" name="status_recruitment" value="0" onchange="this.form.submit()"
                            class="peer hidden" {{ $statusOprecGlobal == 0 ? 'checked' : '' }}>
                        <div
                            class="cursor-pointer text-center px-5 py-2 rounded-lg text-xs font-black uppercase tracking-wider transition-all duration-200 text-slate-500 peer-checked:bg-white peer-checked:text-rose-600 peer-checked:shadow-sm flex items-center justify-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-rose-500 block"></span> Ditutup
                        </div>
                    </label>

                    <label class="flex-1 sm:flex-initial">
                        <input type="radio" name="status_recruitment" value="1" onchange="this.form.submit()"
                            class="peer hidden" {{ $statusOprecGlobal == 1 ? 'checked' : '' }}>
                        <div
                            class="cursor-pointer text-center px-5 py-2 rounded-lg text-xs font-black uppercase tracking-wider transition-all duration-200 text-slate-500 peer-checked:bg-white peer-checked:text-emerald-600 peer-checked:shadow-sm flex items-center justify-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 block animate-pulse"></span> Dibuka
                        </div>
                    </label>
                </div>
            </form>
        </div>


    </div>

    <div class="grid grid-cols-1">
        @foreach ($prokers as $proker)
            @php
                $totalDivisi = $proker->divisi->count();
                $totalKuota = $proker->divisi->sum('kuota_staff');
                $kadivTerisi = $proker->divisi->whereNotNull('kadiv_id')->count();
                $progressKadiv = $totalDivisi > 0 ? round(($kadivTerisi / $totalDivisi) * 100) : 0;
            @endphp

            <div
                class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden flex flex-col hover:shadow-lg transition-shadow duration-300">
                <div class="p-6 md:p-8 border-b border-slate-100 bg-slate-50/50">
                    <div class="md:flex md:justify-between md:items-start mb-5">
                        <div>
                            <h3 class="text-2xl font-black text-slate-800">{{ $proker->nama_proker }}</h3>
                            <div class="flex items-center gap-2 text-sm text-slate-500 mt-2 font-medium">
                                <i class="ph-fill ph-calendar-blank text-blue-500"></i>
                                {{ \Carbon\Carbon::parse($proker->tanggal_mulai)->translatedFormat('d M Y') }} -
                                {{ \Carbon\Carbon::parse($proker->tanggal_selesai)->translatedFormat('d M Y') }}
                            </div>
                        </div>
                        <span
                            class="bg-blue-100 text-blue-700 border border-blue-200 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm mt-3 md:mt-0 flex items-center gap-1">
                            Proker Aktif
                        </span>
                    </div>

                    <div
                        class="text-slate-600 text-sm leading-relaxed bg-white p-4 rounded-2xl border border-slate-100 shadow-sm relative">
                        <i class="ph-fill ph-quotes text-3xl text-slate-100 absolute -top-2 -left-1"></i>
                        <p class="relative z-10">
                            {{ $proker->deskripsi ?? 'Belum ada deskripsi untuk program kerja ini. Silakan lengkapi di menu edit proker.' }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-px bg-slate-100 border-b border-slate-100">
                    <div class="bg-white p-5 text-center transition hover:bg-slate-50">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Divisi</p>
                        <p class="text-3xl font-black text-slate-800">{{ $totalDivisi }}</p>
                    </div>
                    <div class="bg-white p-5 text-center transition hover:bg-slate-50">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Target Staff</p>
                        <p class="text-3xl font-black text-blue-600">{{ $totalKuota }}</p>
                    </div>
                    <div class="bg-white p-5 text-center transition hover:bg-slate-50">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kadiv Terisi</p>
                        <div class="flex items-center justify-center gap-1.5">
                            <p
                                class="text-3xl font-black {{ $progressKadiv == 100 ? 'text-emerald-500' : 'text-amber-500' }}">
                                {{ $kadivTerisi }}</p>
                            <span class="text-sm font-bold text-slate-300">/ {{ $totalDivisi }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-6 md:p-8 flex-1 bg-white">
                    <div class="flex justify-between items-center mb-5">
                        <h4 class="font-bold text-slate-800 flex items-center gap-2">
                            <i class="ph-fill ph-layout text-blue-500 text-xl"></i> Rincian Divisi
                        </h4>
                        @if ($progressKadiv == 100 && $totalDivisi > 0)
                            <span
                                class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded border border-emerald-100 uppercase tracking-wider">
                                100% Ready
                            </span>
                        @endif
                    </div>

                    <div class="space-y-3 pr-2">
                        @forelse($proker->divisi as $divisi)
                            <div
                                class="group flex flex-col sm:flex-row sm:items-center justify-between p-4 rounded-2xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/40 transition-all gap-4">
                                <div>
                                    <p class="font-bold text-slate-800">{{ $divisi->nama_divisi }}</p>
                                    <p class="text-xs font-semibold text-slate-500 mt-1 flex items-center gap-1">
                                        <i class="ph-fill ph-users text-blue-400"></i> Butuh {{ $divisi->kuota_staff }}
                                        Staff
                                    </p>
                                </div>

                                <div>
                                    @if ($divisi->kadiv_id && $divisi->kadiv)
                                        <div
                                            class="flex items-center justify-between gap-4 bg-emerald-50 border border-emerald-100 px-3 py-2 rounded-xl">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-emerald-200 text-emerald-800 flex items-center justify-center text-sm font-black shadow-sm">
                                                    {{ substr($divisi->kadiv->name, 0, 1) }}
                                                </div>
                                                <div class="text-left">
                                                    <p
                                                        class="text-[9px] font-black uppercase text-emerald-600 tracking-widest leading-none mb-0.5">
                                                        Kepala Divisi</p>
                                                    <p
                                                        class="text-xs font-bold text-slate-700 leading-tight truncate max-w-[130px]">
                                                        {{ $divisi->kadiv->name }}</p>
                                                </div>
                                            </div>

                                            <button type="button"
                                                onclick="openEditKadivModal('{{ $divisi->id }}', '{{ $divisi->kadiv->name }}', '{{ $divisi->kadiv->email }}', '{{ $divisi->nama_divisi }}')"
                                                class="text-slate-400 hover:text-blue-600 p-1 rounded-lg transition-colors"
                                                title="Edit Akun Kadiv">
                                                <i class="ph ph-pencil-simple text-base font-bold"></i>
                                            </button>
                                        </div>
                                    @else
                                        <span
                                            class="flex items-center gap-1.5 bg-red-50 text-red-600 border border-red-100 px-4 py-2.5 rounded-xl text-xs font-bold w-full sm:w-auto justify-center shadow-sm">
                                            <i class="ph-fill ph-warning-circle text-base"></i> Kadiv Kosong
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                                <i class="ph-fill ph-folder-open text-4xl text-slate-300 mb-3 block"></i>
                                <p class="text-slate-500 text-sm font-bold">Belum ada divisi yang dibuat.</p>
                                <p class="text-slate-400 text-xs mt-1">Silakan tambahkan divisi di menu Kelola Kadiv.</p>
                            </div>
                        @endempty
                </div>

                <div id="modalEditKadiv"
                    class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
                    <div
                        class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all animate-fade-in">
                        <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                                <i class="ph-fill ph-user-gear text-blue-600"></i> Edit Akun Kadiv <span
                                    id="textDivisiModal" class="text-blue-600"></span>
                            </h3>
                            <button onclick="closeEditKadivModal()"
                                class="text-slate-400 hover:text-rose-500 transition-colors">
                                <i class="ph ph-x text-xl font-bold"></i>
                            </button>
                        </div>

                        <form id="formEditKadiv" method="POST" class="p-6 space-y-4">
                            @csrf

                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama
                                    Lengkap Kadiv</label>
                                <input type="text" name="name" id="inputNamaKadiv" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm text-slate-700 font-semibold focus:ring-2 focus:ring-blue-500 outline-none transition">
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Email
                                    Resmi</label>
                                <input type="email" name="email" id="inputEmailKadiv" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm text-slate-700 font-semibold focus:ring-2 focus:ring-blue-500 outline-none transition">
                                <p
                                    class="text-[10px] text-amber-600 font-medium mt-1.5 leading-relaxed flex items-start gap-1">
                                    <i class="ph ph-info text-xs shrink-0 mt-0.5"></i>
                                    *Jika email diubah, sistem otomatis meng-generate password baru dan mengirimkan
                                    kredensial tersebut ke email yang baru ditulis.
                                </p>
                            </div>

                            <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end gap-2">
                                <button type="button" onclick="closeEditKadivModal()"
                                    class="px-4 py-2.5 bg-slate-100 text-slate-700 font-bold text-xs rounded-xl transition hover:bg-slate-200">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-5 py-2.5 bg-blue-600 text-white font-bold text-xs rounded-xl shadow-md transition hover:bg-blue-700 flex items-center gap-1.5">
                                    <i class="ph ph-floppy-disk text-sm"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
    function openEditKadivModal(divisiId, namaKadiv, emailKadiv, namaDivisi) {
        document.getElementById('textDivisiModal').innerText = '- ' + namaDivisi;
        document.getElementById('inputNamaKadiv').value = namaKadiv;
        document.getElementById('inputEmailKadiv').value = emailKadiv;

        let url = "{{ route('po.divisi.update_kadiv', ':id') }}";
        url = url.replace(':id', divisiId);

        document.getElementById('formEditKadiv').action = url;

        document.getElementById('modalEditKadiv').classList.remove('hidden');
    }

    function closeEditKadivModal() {
        document.getElementById('modalEditKadiv').classList.add('hidden');
    }
</script>
@endsection
