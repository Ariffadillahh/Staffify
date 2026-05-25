<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | Staffify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body class="bg-slate-50 text-slate-800 font-sans antialiased flex items-center justify-center min-h-screen p-6">

    <div
        class="max-w-md w-full text-center bg-white p-8 md:p-12 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 transform transition-all">
        <div
            class="w-20 h-20 bg-rose-50 border border-rose-100 text-rose-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm">
            <i class="ph-fill ph-lock-key text-5xl"></i>
        </div>

        <h1 class="text-7xl font-black text-slate-900 tracking-tighter mb-2">403</h1>
        <h2 class="text-xl font-bold text-slate-800 mb-4 tracking-tight">Akses Dibatasi</h2>

        <p class="text-slate-500 text-sm md:text-base leading-relaxed mb-8 font-medium">
            {{ $exception->getMessage() ?: 'Anda tidak memiliki hak akses untuk membuka halaman ini.' }}
        </p>

        <a href="/"
            class="inline-flex items-center justify-center gap-2 w-full bg-slate-900 hover:bg-blue-600 text-white font-black py-4 rounded-xl shadow-md transition-colors uppercase tracking-wider text-xs">
            <i class="ph ph-house text-base"></i> Kembali ke Beranda
        </a>
    </div>

</body>

</html>
