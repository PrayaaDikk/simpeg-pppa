<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <title>@yield('title')</title>

    <!-- Menggunakan CDN Tailwind agar praktis, atau ganti dengan link CSS lokal Anda -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-full font-['Poppins',sans-serif]">
    <main class="grid min-h-full place-items-center bg-gray-900 px-6 py-24 sm:py-32 lg:px-8">
        <div class="text-center">
            <!-- Menampilkan Kode Error (misal: 404) -->
            <p class="text-base font-semibold text-indigo-400">
                @yield('code')
            </p>

            <!-- Menampilkan Judul Error (misal: Page Not Found) -->
            <h1 class="mt-4 text-5xl font-semibold tracking-tight text-balance text-white sm:text-7xl">
                @yield('title')
            </h1>

            <!-- Menampilkan Pesan Detail -->
            <p class="mt-6 text-lg font-medium text-pretty text-gray-400 sm:text-xl/8">
                @yield('message')
            </p>

            <div class="mt-10 flex items-center justify-center gap-x-6">
                <!-- Tombol Kembali ke Beranda -->
                <a href="{{ url()->previous() }}"
                    class="rounded-md bg-indigo-500 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                    Kembali
                </a>
            </div>
        </div>
    </main>
</body>

</html>
