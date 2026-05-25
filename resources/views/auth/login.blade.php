<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Staffify</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-md p-8 border border-gray-200">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-blue-600">Staffify.</h1>
            <p class="text-sm text-gray-500 mt-2 font-medium">Kabinet Simpul Perubahan</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-3 rounded-md text-sm mb-4 border border-red-200">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 text-red-600 p-3 rounded-md text-sm mb-4 border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full border-gray-300 bg-gray-50 rounded-lg p-3 border focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full border-gray-300 bg-gray-50 rounded-lg p-3 border focus:ring-blue-500 focus:border-blue-500">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition">
                Masuk Dashboard
            </button>
        </form>
        <div class="mt-6 text-center">
            <a href="{{ route('landing') }}" class="text-sm text-gray-500 hover:text-blue-600 transition">&larr; Kembali
                ke Beranda</a>
        </div>
    </div>
</body>

</html>
