<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | Staffify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body class="bg-slate-50 text-slate-800 font-sans antialiased flex items-center justify-center min-h-screen p-6">

    <div
        class="max-w-md w-full text-center bg-white p-8 md:p-12 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 transform transition-all">
        <div
            class="w-20 h-20 bg-blue-50 border border-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm">
            <i class="ph-fill ph-magnifying-glass text-5xl"></i>
        </div>

        <h1 class="text-7xl font-black text-slate-900 tracking-tighter mb-2">404</h1>
        <h2 class="text-xl font-bold text-slate-800 mb-4 tracking-tight">Halaman Tidak Ditemukan</h2>

        <p class="text-slate-500 text-sm md:text-base leading-relaxed mb-8 font-medium">
            {{ $exception->getMessage() ?: 'Maaf, halaman yang Anda cari tidak ada atau telah dipindahkan oleh admin.' }}
        </p>

        <a href="/"
            class="inline-flex items-center justify-center gap-2 w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-xl shadow-md transition-colors uppercase tracking-wider text-xs">
            <i class="ph ph-house text-base"></i> Kembali ke Beranda
        </a>
    </div>

</body>

</html>
