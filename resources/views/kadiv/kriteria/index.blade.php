@extends('layouts.admin')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Kriteria Penilaian</h2>
                <p class="text-blue-600 font-bold uppercase text-sm mt-1">DIVISI {{ $divisi->nama_divisi }}</p>
            </div>
            <button onclick="document.getElementById('modalTambahKriteria').classList.remove('hidden')"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-lg shadow-md transition flex items-center gap-2">
                <i class="ph ph-plus-circle text-xl"></i> Tambah Kriteria
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50 border-b border-gray-200 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <th class="p-4 w-12 text-center">No</th>
                        <th class="p-4">Nama Kriteria</th>
                        <th class="p-4 text-center">Jenis Factor</th>
                        <th class="p-4 text-center">Nilai Target</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($kriterias as $index => $k)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 text-center font-medium text-gray-500">{{ $index + 1 }}</td>
                            <td class="p-4 font-bold text-gray-800">{{ $k->nama_kriteria }}</td>
                            <td class="p-4 text-center">
                                <span
                                    class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $k->jenis_factor == 'core' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $k->jenis_factor }}
                                </span>
                            </td>
                            <td class="p-4 text-center font-black text-indigo-600">{{ $k->nilai_target }}</td>
                            <td class="p-4 flex justify-end gap-2">
                                <button
                                    onclick="openEditModal('{{ $k->id }}', '{{ $k->nama_kriteria }}', '{{ $k->jenis_factor }}', '{{ $k->nilai_target }}')"
                                    class="h-8 w-8 bg-white border border-amber-200 text-amber-500 hover:bg-amber-50 rounded flex items-center justify-center transition shadow-sm">
                                    <i class="ph ph-pencil-simple font-bold"></i>
                                </button>

                                <form action="{{ route('kriteria.destroy', $k->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus kriteria ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="h-8 w-8 bg-white border border-red-200 text-red-500 hover:bg-red-50 rounded flex items-center justify-center transition shadow-sm">
                                        <i class="ph ph-trash font-bold"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-400">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="modalTambahKriteria"
        class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="text-xl font-bold text-gray-800">Tambah Kriteria Baru</h3>
                <button onclick="document.getElementById('modalTambahKriteria').classList.add('hidden')"
                    class="text-gray-400 hover:text-red-500"><i class="ph ph-x text-2xl"></i></button>
            </div>
            <form action="{{ route('kriteria.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Kriteria</label>
                    <input type="text" name="nama_kriteria" required
                        class="w-full border-gray-300 rounded-lg p-2.5 bg-gray-50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Factor</label>
                    <select name="jenis_factor" required class="w-full border-gray-300 rounded-lg p-2.5 bg-gray-50">
                        <option value="core">Core Factor</option>
                        <option value="secondary">Secondary Factor</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nilai Target (1-5)</label>
                    <select name="nilai_target" required class="w-full border-gray-300 rounded-lg p-2.5 bg-gray-50">
                        <option value="5">5 - Sangat Baik</option>
                        <option value="4">4 - Baik</option>
                        <option value="3">3 - Cukup</option>
                        <option value="2">2 - Kurang</option>
                        <option value="1">1 - Sangat Kurang</option>
                    </select>
                </div>
                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalTambahKriteria').classList.add('hidden')"
                        class="px-5 py-2 bg-gray-200 font-bold rounded-lg">Batal</button>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white font-bold rounded-lg shadow-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEditKriteria"
        class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-amber-50">
                <h3 class="text-xl font-bold text-gray-800">Edit Kriteria</h3>
                <button onclick="document.getElementById('modalEditKriteria').classList.add('hidden')"
                    class="text-gray-400 hover:text-red-500"><i class="ph ph-x text-2xl font-bold"></i></button>
            </div>

            <form id="formEditKriteria" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Kriteria</label>
                    <input type="text" name="nama_kriteria" id="edit_nama" required
                        class="w-full border-gray-300 rounded-lg p-2.5 bg-gray-50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Factor</label>
                    <select name="jenis_factor" id="edit_factor" required
                        class="w-full border-gray-300 rounded-lg p-2.5 bg-gray-50">
                        <option value="core">Core Factor</option>
                        <option value="secondary">Secondary Factor</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nilai Target (1-5)</label>
                    <select name="nilai_target" id="edit_target" required
                        class="w-full border-gray-300 rounded-lg p-2.5 bg-gray-50">
                        <option value="5">5 - Sangat Baik</option>
                        <option value="4">4 - Baik</option>
                        <option value="3">3 - Cukup</option>
                        <option value="2">2 - Kurang</option>
                        <option value="1">1 - Sangat Kurang</option>
                    </select>
                </div>
                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalEditKriteria').classList.add('hidden')"
                        class="px-5 py-2 bg-gray-200 font-bold rounded-lg">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-500 text-white font-bold rounded-lg shadow-md">Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, nama, factor, target) {
            const form = document.getElementById('formEditKriteria');
            form.action = `/admin/kriteria/${id}`;

            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_factor').value = factor;
            document.getElementById('edit_target').value = target;

            document.getElementById('modalEditKriteria').classList.remove('hidden');
        }
    </script>
@endsection
