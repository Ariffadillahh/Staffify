@extends('layouts.admin')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Kelola Akun Kepala Divisi</h2>
                <p class="text-blue-600 font-bold uppercase text-sm mt-1">
                    MANAJEMEN KADIV & DIVISI
                </p>
            </div>

            <button
                class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-2.5 px-5 rounded-lg shadow-md transition flex items-center gap-2"
                onclick="openModalDivisi('{{ $proker->id ?? (\App\Models\Proker::latest()->first()->id ?? '') }}')">
                <i class="ph ph-squares-four text-xl"></i> Tambah Divisi Baru
            </button>
        </div>

       
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-md shadow-sm flex items-center">
                <i class="ph ph-warning-circle text-red-500 text-xl mr-3"></i>
                <p class="text-red-700 font-medium">{{ $errors->first() }}</p>
            </div>
        @endif

        <div class="bg-blue-50 border border-blue-100 p-5 rounded-xl flex gap-4 items-start mb-6">
            <i class="ph ph-info text-2xl text-blue-600 mt-1"></i>
            <div class="text-sm text-blue-900">
                <p class="font-bold mb-1">Panduan Generate Akun:</p>
                <p class="opacity-90">Sistem akan secara otomatis membuatkan *password* acak dan mengirimkannya langsung ke
                    *email* Kadiv yang Anda daftarkan di sini melalui email (menggunakan Mailable yang sudah kita siapkan).
                </p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse whitespace-nowrap">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-gray-200 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="p-4 w-12 text-center">No</th>
                            <th class="p-4">Nama Divisi</th>
                            <th class="p-4 text-center">Kuota Staff</th>
                            <th class="p-4">Informasi Kadiv</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($divisis as $index => $divisi)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4 text-center font-medium text-gray-500">{{ $index + 1 }}</td>
                                <td class="p-4 font-bold text-gray-800 text-lg">{{ $divisi->nama_divisi }}</td>
                                <td class="p-4 text-center font-bold text-blue-600">{{ $divisi->kuota_staff ?? 0 }} Orang
                                </td>

                                <td class="p-4">
                                    @if ($divisi->kadiv)
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                                                {{ substr($divisi->kadiv->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800">{{ $divisi->kadiv->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $divisi->kadiv->email }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                            <i class="ph ph-warning-circle"></i> Belum ada Kadiv
                                        </span>
                                    @endif
                                </td>

                                <td class="p-4 text-center">
                                    @if (!$divisi->kadiv)
                                        <button
                                            onclick="openGenerateModal('{{ $divisi->id }}', '{{ $divisi->nama_divisi }}')"
                                            class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 px-4 rounded shadow transition flex items-center justify-center gap-2 mx-auto">
                                            <i class="ph ph-user-plus text-base"></i> Buat Akun
                                        </button>
                                    @else
                                        <span
                                            class="text-xs font-bold text-emerald-500 flex items-center justify-center gap-1">
                                            <i class="ph ph-check-circle text-base"></i> Akun Aktif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400">
                                    <i class="ph ph-folder-dashed text-4xl mb-2 block"></i>
                                    <p>Belum ada divisi yang terdaftar.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modalGenerate" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-blue-50">
                <h3 class="text-xl font-bold text-blue-900">Buat Akun Kadiv Baru</h3>
                <button onclick="document.getElementById('modalGenerate').classList.add('hidden')"
                    class="text-gray-400 hover:text-red-500">
                    <i class="ph ph-x text-2xl font-bold"></i>
                </button>
            </div>

            <form action="{{ route('kadiv.store') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="divisi_id" id="generate_divisi_id">

                <div class="mb-4">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Untuk Divisi</p>
                    <p id="generate_divisi_nama" class="text-lg font-black text-gray-800 border-b pb-2"></p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap Kadiv <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" required placeholder="Contoh: Daffa Syarif"
                            class="w-full border-gray-300 rounded-lg p-2.5 bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email Kadiv <span
                                class="text-red-500">*</span></label>
                        <input type="email" name="email" required placeholder="email@mahasiswa.pnj.ac.id"
                            class="w-full border-gray-300 rounded-lg p-2.5 bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-[10px] text-gray-500 mt-1">*Password dan informasi login akan dikirimkan ke email
                            ini.</p>
                    </div>
                </div>

                <div class="mt-8 md:flex md:justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalGenerate').classList.add('hidden')"
                        class="px-5 py-2.5 bg-gray-200 text-gray-800 font-bold rounded-lg transition hover:bg-gray-300 w-full md:w-auto">Batal</button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-lg shadow-md transition hover:bg-blue-700 flex items-center gap-2 w-full md:w-auto justify-center mt-3 md:mt-0">
                        <i class="ph ph-paper-plane-tilt"></i> Buat & Kirim Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-divisi" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white p-8 rounded-xl w-full max-w-2xl shadow-2xl">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Tambah Divisi Baru</h2>
            <form action="{{ route('divisi.bulk_store') }}" method="POST">
                @csrf
                <input type="hidden" name="proker_id" id="modal_proker_id">

                <div id="divisi-container" class="space-y-3 mb-6 max-h-60 overflow-y-auto p-2">
                    <div class="flex gap-2 items-center">
                        <input type="text" name="nama_divisi[]" placeholder="Nama Divisi"
                            class="flex-1 border border-gray-300 p-2.5 rounded-lg outline-none focus:border-blue-500"
                            required>
                        <input type="number" name="kuota_staff[]" placeholder="Kuota"
                            class="w-24 border border-gray-300 p-2.5 rounded-lg outline-none focus:border-blue-500"
                            required>
                        <button type="button" class="text-red-500 font-bold px-2 invisible">X</button>
                    </div>
                </div>

                <button type="button" onclick="addDivisiRow()"
                    class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-3 rounded-lg mb-6 w-full border-2 border-dashed border-gray-300 font-semibold transition">
                    + Klik untuk Tambah Baris Divisi Lainnya
                </button>

                <div class="md:flex md:justify-end gap-3">
                    <button type="button" onclick="closeModalDivisi()"
                        class="px-5 py-2.5 text-gray-600 bg-gray-200 font-bold rounded-lg hover:bg-gray-300 transition w-full md:w-auto">Batal</button>
                    <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg font-bold shadow-md hover:bg-blue-700 transition w-full md:w-auto mt-3 md:mt-0">Simpan
                        Semua Divisi</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openGenerateModal(divisiId, divisiNama) {
            document.getElementById('generate_divisi_id').value = divisiId;
            document.getElementById('generate_divisi_nama').innerText = divisiNama;
            document.getElementById('modalGenerate').classList.remove('hidden');
        }

        function addDivisiRow() {
            const container = document.getElementById('divisi-container');
            const row = document.createElement('div');
            row.className = 'flex gap-2 items-center';
            row.innerHTML = `
                <input type="text" name="nama_divisi[]" placeholder="Nama Divisi" class="flex-1 border border-gray-300 p-2.5 rounded-lg outline-none focus:border-blue-500" required>
                <input type="number" name="kuota_staff[]" placeholder="Kuota" class="w-24 border border-gray-300 p-2.5 rounded-lg outline-none focus:border-blue-500" required>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 hover:bg-red-50 w-8 h-8 rounded flex items-center justify-center font-bold transition">X</button>
            `;
            container.appendChild(row);
        }

        function openModalDivisi(prokerId) {
            document.getElementById('modal_proker_id').value = prokerId;
            document.getElementById('modal-divisi').classList.remove('hidden');
        }

        function closeModalDivisi() {
            document.getElementById('modal-divisi').classList.add('hidden');
        }
    </script>
@endsection
