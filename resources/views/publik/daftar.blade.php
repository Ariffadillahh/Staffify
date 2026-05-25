@extends('layouts.public')

@section('content')
    <div class="max-w-4xl mx-auto font-sans antialiased">
        <div class="text-center mb-10">
            <div
                class="bg-blue-600 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-200">
                <i class="ph ph-users-three text-3xl text-white"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Open Recruitment Staff</h1>
            <p class="text-slate-500 font-medium mt-2">BEM PNJ - Kabinet Simpul Perubahan</p>
        </div>

        @if ($errors->any())
            <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm">
                <div class="flex items-start">
                    <i class="ph ph-warning-circle text-red-500 text-2xl mr-3 mt-0.5"></i>
                    <div>
                        <h3 class="text-red-800 font-bold mb-1">Mohon periksa kembali form Anda:</h3>
                        <ul class="list-disc list-inside text-red-600 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden mb-10">
            <form action="{{ route('daftar.store') }}" method="POST" enctype="multipart/form-data"
                class="p-6 sm:p-8 md:p-12 space-y-10">
                @csrf

                <div>
                    <h3
                        class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-3 border-b pb-3 border-slate-100">
                        <span
                            class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-black flex-shrink-0">1</span>
                        Informasi Pribadi
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_lengkap" required value="{{ old('nama_lengkap') }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition outline-none"
                                placeholder="Masukkan nama lengkap">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">NIM <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="nim" required value="{{ old('nim') }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition outline-none"
                                placeholder="Contoh: 2407411000">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email <span
                                    class="text-red-500">*</span></label>
                            <input type="email" name="email" required value="{{ old('email') }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition outline-none"
                                placeholder="email@mahasiswa.pnj.ac.id">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">No. WhatsApp
                                <span class="text-red-500">*</span></label>
                            <input type="number" name="no_whatsapp" required value="{{ old('no_whatsapp') }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition outline-none"
                                placeholder="081234567890">
                        </div>
                    </div>
                </div>

                <div>
                    <h3
                        class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-3 border-b pb-3 border-slate-100">
                        <span
                            class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-black flex-shrink-0">2</span>
                        Motivasi & Pilihan Divisi
                    </h3>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alasan
                                Mengikuti Kepanitiaan/Proker Ini</label>
                            <textarea name="alasan_mengikuti_proker" rows="2"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition outline-none"
                                placeholder="Jelaskan secara singkat motivasi Anda...">{{ old('alasan_mengikuti_proker') }}</textarea>
                        </div>

                        <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100">
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Divisi
                                        Pilihan <span class="text-red-500">*</span></label>
                                    <select name="divisi_id" required
                                        class="w-full bg-white border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition outline-none cursor-pointer">
                                        <option value="" disabled {{ old('divisi_id') ? '' : 'selected' }}>Pilih
                                            Divisi yang Anda inginkan...</option>
                                        @foreach ($divisis as $divisi)
                                            <option value="{{ $divisi->id }}"
                                                {{ old('divisi_id') == $divisi->id ? 'selected' : '' }}>
                                                {{ $divisi->nama_divisi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alasan
                                        Memilih Divisi Tersebut <span class="text-red-500">*</span></label>
                                    <textarea name="alasan" rows="2" required
                                        class="w-full bg-white border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition outline-none"
                                        placeholder="Mengapa Anda yakin cocok di divisi ini?">{{ old('alasan') }}</textarea>
                                </div>
                            </div>

                            <div
                                class="mt-5 flex items-start gap-3 bg-white p-4 rounded-xl border border-blue-100 shadow-sm">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="bersedia_pindah_divisi" id="bersedia_pindah" value="1"
                                        {{ old('bersedia_pindah_divisi') ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer">
                                </div>
                                <label for="bersedia_pindah" class="text-sm font-semibold text-slate-700 cursor-pointer">
                                    Saya bersedia ditempatkan di divisi lain jika panitia merasa saya lebih sesuai
                                    di sana.
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pengalaman
                                Organisasi / Kepanitiaan (Opsional)</label>
                            <textarea name="pengalaman" rows="3"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition outline-none"
                                placeholder="Sebutkan pengalaman Anda yang relevan (jika ada)...">{{ old('pengalaman') }}</textarea>
                        </div>
                    </div>
                </div>

                <div>
                    <h3
                        class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-3 border-b pb-3 border-slate-100">
                        <span
                            class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-black flex-shrink-0">3</span>
                        Unggah Foto Formal
                    </h3>

                    <div class="relative border-2 border-dashed border-slate-300 rounded-2xl p-8 text-center bg-slate-50 hover:bg-blue-50 hover:border-blue-300 transition-all duration-300 group cursor-pointer overflow-hidden"
                        id="drop-area">
                        <input type="file" name="foto" id="foto-input" required
                            accept="image/png, image/jpeg, image/jpg" onchange="previewImage(event)"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                            title="Klik untuk mengunggah foto">

                        <div id="upload-placeholder" class="space-y-3 relative z-10 transition-opacity duration-300">
                            <i class="ph ph-image text-5xl text-slate-400 group-hover:text-blue-500 transition-colors"></i>
                            <div>
                                <p class="text-sm font-bold text-slate-700">Klik di sini untuk mengunggah foto</p>
                                <p class="text-xs text-slate-400 font-medium mt-1">*Format JPG/PNG (Maks. 2MB). Gunakan jas
                                    almamater.</p>
                            </div>
                            <span
                                class="inline-block px-4 py-1.5 bg-white border border-slate-200 rounded-full text-xs font-bold text-slate-600 shadow-sm group-hover:text-blue-600 group-hover:border-blue-200 mt-2">Pilih
                                File</span>
                        </div>

                        <div id="preview-container" class="hidden relative z-10 flex flex-col items-center">
                            <img id="image-preview" src="#" alt="Preview Foto"
                                class="h-40 w-auto rounded-lg shadow-md border border-slate-200 mb-3 object-cover">
                            <p
                                class="text-xs font-bold text-emerald-600 flex items-center gap-1 bg-emerald-50 px-3 py-1 rounded-full">
                                <i class="ph-fill ph-check-circle"></i> Foto berhasil dipilih</p>
                            <p class="text-[10px] text-slate-400 mt-2 hover:underline">Klik kotak ini lagi jika ingin
                                mengganti foto</p>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit"
                        class="w-full bg-slate-900 hover:bg-blue-600 text-white font-black py-4 md:py-5 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 uppercase tracking-widest text-sm flex items-center justify-center gap-2 group">
                        Simpan & Pilih Jadwal Wawancara <i
                            class="ph ph-arrow-right text-lg group-hover:translate-x-1 transition-transform"></i>
                    </button>
                    <p class="text-center text-xs text-slate-400 mt-4">Pastikan data yang diisi sudah lengkap dan benar
                        sebelum melanjutkan.</p>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const input = event.target;
            const placeholder = document.getElementById('upload-placeholder');
            const previewContainer = document.getElementById('preview-container');
            const previewImage = document.getElementById('image-preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    placeholder.classList.add('hidden');
                    previewContainer.classList.remove('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                previewImage.src = '#';
                previewContainer.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        }
    </script>
@endsection
