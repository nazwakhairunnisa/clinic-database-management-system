<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Klinik</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-[#F5F5F5] text-gray-800 font-sans">

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="w-64 bg-[#FBF7E7] border-r border-[#806B3F] rounded-r-2xl p-6 flex flex-col justify-between">
            <div>
                {{-- Logo --}}
                <div class="text-center mb-10">
                    <img src="{{ asset('images/nusacare-logo.png') }}" alt="Logo" class="h-14 mx-auto mb-3">
                    <h2 class="text-lg font-semibold text-[#806B3F]">Dashboard Admin Klinik</h2>
                </div>

                {{-- Menu --}}
                <nav class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}"
                       class="block px-4 py-2 rounded-lg hover:bg-[#EED892] {{ request()->is('admin/dashboard') ? 'bg-[#EED892] font-semibold' : '' }}">
                       Dashboard
                    </a>
                    <a href="#" class="block px-4 py-2 hover:bg-[#EED892] rounded-lg">Jadwal Reservasi</a>
                    <a href="#" class="block px-4 py-2 hover:bg-[#EED892] rounded-lg">Daftar Pasien</a>

                    <details class="group">
                        <summary class="cursor-pointer px-4 py-2 rounded-lg hover:bg-[#EED892]">Layanan</summary>
                        <div class="pl-6 mt-1 space-y-1">
                            <a href="#" class="block text-sm hover:underline">Daftar Treatment</a>
                            <a href="#" class="block text-sm hover:underline">Daftar Promo</a>
                        </div>
                    </details>

                    <details class="group">
                        <summary class="cursor-pointer px-4 py-2 rounded-lg hover:bg-[#EED892]">Inventori</summary>
                        <div class="pl-6 mt-1 space-y-1">
                            <a href="#" class="block text-sm hover:underline">Stok Obat</a>
                            <a href="#" class="block text-sm hover:underline">Pembelian Obat</a>
                        </div>
                    </details>

                    <details class="group">
                        <summary class="cursor-pointer px-4 py-2 rounded-lg hover:bg-[#EED892]">Keuangan</summary>
                        <div class="pl-6 mt-1 space-y-1">
                            <a href="#" class="block text-sm hover:underline">Daftar Pembayaran</a>
                        </div>
                    </details>
                </nav>
            </div>

            {{-- Footer --}}
            <div class="text-center text-sm text-gray-600 mt-10">
                <p>Logged in as <span class="font-semibold">Owner</span></p>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main --}}
        <main class="flex-1">
            {{-- Header --}}
            <header class="bg-white shadow-md p-4 flex justify-between items-center">
                <h1 class="text-xl font-bold text-[#806B3F]">@yield('page-title', 'Dashboard')</h1>

                <div class="flex items-center gap-3">
                    @auth
                        <span class="font-medium">{{ Auth::user()->name }}</span>
                    @endauth
                </div>
            </header>

            {{-- Konten --}}
            <div class="p-6">
                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>
