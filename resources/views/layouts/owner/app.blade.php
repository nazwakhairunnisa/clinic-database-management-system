@vite('resources/css/app.css')

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clinic Dashboard</title>

  {{-- Font Awesome --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  {{-- Alpine.js --}}
  <script src="//unpkg.com/alpinejs" defer></script>

  {{-- FullCalendar --}}
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>

   {{-- Iconify (penting biar logout muncul di semua halaman) --}}
  <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>

  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body x-data="{ sidebarOpen: false }" class="bg-[#F8F6F1] font-sans">

  {{-- === NAVBAR === --}}
  <nav class="fixed top-0 left-0 w-full h-[70px] bg-white shadow-md flex items-center justify-between px-4 sm:px-6 lg:px-8 z-50">
    
    {{-- Kiri: Hamburger + Logo + Judul --}}
    <div class="flex items-center space-x-4">
      <button @click="sidebarOpen = !sidebarOpen" class="block lg:hidden text-[#806B3F] text-2xl focus:outline-none">
        <i class="fa-solid fa-bars"></i>
      </button>

      <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-10 sm:h-12 w-auto">
      <h1 class="text-lg sm:text-xl font-bold text-[#806B3F]">
  @yield('pageTitle', 'Dashboard')
</h1>

    </div>

    {{-- Kanan: Search + Notif + Logout --}}
<div class="flex items-center space-x-2 sm:space-x-3 md:space-x-4">

  {{-- Search Icon (hanya muncul di Dashboard) --}}
  @if (request()->routeIs('owner.dashboard'))
    <button class="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 flex items-center justify-center rounded-full bg-[#F5EAD5]/80 hover:bg-[#EED892] transition-all duration-200">
      <i class="fa-solid fa-magnifying-glass text-gray-700 text-base sm:text-lg md:text-xl"></i>
    </button>
  @endif

  {{-- Notification Icon (selalu muncul) --}}
  <button class="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 flex items-center justify-center rounded-full bg-[#F5EAD5]/80 hover:bg-[#EED892] transition-all duration-200">
    <i class="fa-regular fa-bell text-gray-700 text-base sm:text-lg md:text-xl"></i>
  </button>

  {{-- Logout (selalu muncul) --}}
  <a href="#" class="flex items-center bg-gray-200/50 rounded-lg hover:bg-gray-300 transition-all duration-200 px-3 py-1.5 sm:px-4 sm:py-2">
    <span class="iconify text-gray-700 text-xl sm:text-2xl" data-icon="material-symbols:logout-rounded"></span>
    <span class="hidden md:inline font-normal text-gray-700 text-sm sm:text-base ml-2">Logout</span>
  </a>

</div>


  </nav>

  {{-- === SIDEBAR === --}}
  <aside
    class="fixed top-[70px] left-0 h-[calc(100%-70px)] bg-[#FBF7E7] shadow-lg flex flex-col z-40 w-64 lg:w-[260px] transform transition-transform duration-300"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
  >
    {{-- Menu Sidebar --}}
    <div class="flex-1 p-4 lg:p-6 overflow-y-auto">
      <ul class="space-y-2 text-[#806B3F] font-medium">

        {{-- Dashboard --}}
        <li>
          <a href="{{ route('owner.dashboard') }}" class="block py-2 px-4 hover:bg-[#E9E1C5] rounded-lg">
            <i class="fa-solid fa-house mr-2"></i>Dashboard
          </a>
        </li>

        {{-- Jadwal Reservasi --}}
        <li>
          <a href="{{ route('owner.jadwal') }}"
            class="block py-2 px-4 rounded-lg flex items-center 
                    {{ request()->routeIs('owner.jadwal') ? 'bg-[#E9E1C5] text-[#806B3F] font-semibold' : 'hover:bg-[#E9E1C5]' }}">
            <i class="fa-regular fa-calendar mr-2"></i>
            Jadwal Reservasi
          </a>
        </li>


        {{-- Daftar Pasien --}}
        <li>
          <a href="{{ route('owner.pasien') }}" 
            class="block py-2 px-4 hover:bg-[#E9E1C5] rounded-lg">
            <i class="fa-solid fa-users mr-2"></i>Daftar Pasien
          </a>
        </li>

        {{-- Layanan --}}
        <li>
          <div class="flex items-center justify-between py-2 px-4 rounded-lg hover:bg-[#E9E1C5] cursor-pointer menu-toggle">
            <div class="flex items-center space-x-2">
              <i class="fas fa-concierge-bell w-5 text-center"></i>
              <span class="font-medium">Layanan</span>
            </div>
            <i class="fas fa-chevron-down text-sm"></i>
          </div>
          <ul class="ml-6 mt-1 space-y-1 text-sm text-gray-700 submenu hidden">
            <li><a href="{{ route('owner.treatment') }}" class="block hover:text-[#8B6B3F]">Daftar Treatment</a></li>
            <li><a href="#" class="block hover:text-[#8B6B3F]">Daftar Promo</a></li>
          </ul>
        </li>

        {{-- Inventori --}}
        <li>
          <div class="flex items-center justify-between py-2 px-4 rounded-lg hover:bg-[#E9E1C5] cursor-pointer menu-toggle">
            <div class="flex items-center space-x-2">
              <i class="fas fa-box w-5 text-center"></i>
              <span class="font-medium">Inventori</span>
            </div>
            <i class="fas fa-chevron-down text-sm"></i>
          </div>
          <ul class="ml-6 mt-1 space-y-1 text-sm text-gray-700 submenu hidden">
            <li><a href="{{ route('owner.stok-obat') }}" class="block hover:text-[#8B6B3F]">Daftar Obat</a></li>
          </ul>
        </li>

        {{-- Keuangan --}}
        <li>
          <div class="flex items-center justify-between py-2 px-4 rounded-lg hover:bg-[#E9E1C5] cursor-pointer menu-toggle">
            <div class="flex items-center space-x-2">
              <i class="fas fa-chart-line w-5 text-center"></i>
              <span class="font-medium">Keuangan</span>
            </div>
            <i class="fas fa-chevron-down text-sm"></i>
          </div>
          <ul class="ml-6 mt-1 space-y-1 text-sm text-gray-700 submenu hidden">
            <li><a href="{{ route('owner.pengeluaran') }}" class="block hover:text-[#8B6B3F]">Pengeluaran</a></li>
            <li><a href="{{ route('owner.pendapatan') }}" class="block hover:text-[#8B6B3F]">Pendapatan</a></li>
          </ul>
        </li>

        {{-- Laporan --}}
        <li>
          <div class="flex items-center justify-between py-2 px-4 rounded-lg hover:bg-[#E9E1C5] cursor-pointer menu-toggle">
            <div class="flex items-center space-x-2">
              <i class="fas fa-file-alt w-5 text-center"></i>
              <span class="font-medium">Laporan</span>
            </div>
            <i class="fas fa-chevron-down text-sm"></i>
          </div>
          <ul class="ml-6 mt-1 space-y-1 text-sm text-gray-700 submenu hidden">
            <li><a href="{{ route('owner.laporan.penjualan') }}" class="block hover:text-[#8B6B3F]">Laporan Penjualan</a></li>
          </ul>
        </li>

      </ul>
    </div>

    {{-- Footer Sidebar --}}
    <div class="mt-auto">
      <div class="bg-[#8B6B3F] text-white flex items-center w-full py-3 px-4 lg:px-6 cursor-pointer hover:bg-[#A18F5E]">
        <i class="fas fa-user-circle text-3xl"></i>
        <div class="ml-2 leading-tight">
          <p class="text-sm">Logged in as</p>
          <p class="font-bold text-base">Owner</p>
        </div>
      </div>
    </div>
  </aside>

  {{-- === BACKDROP MOBILE === --}}
  <div
    x-show="sidebarOpen"
    @click="sidebarOpen = false"
    class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"
    x-transition.opacity
  ></div>

  {{-- === MAIN CONTENT === --}}
 {{-- === <main class="relative z-10 pt-[90px] p-4 sm:p-6 lg:p-8 lg:ml-[260px] transition-all duration-300">
    <div class="max-w-full">
        @yield('content')
    </div>
</main> === --}}

<main class="relative z-0 transition-all duration-300">
  <div class="min-h-screen px-4 sm:px-6 lg:px-8 lg:ml-[260px] pt-[90px]">
    @yield('content')
  </div>
</main>

  {{-- === JS Collapsible === --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const toggles = document.querySelectorAll('.menu-toggle');
      toggles.forEach(toggle => {
        toggle.addEventListener('click', () => {
          const submenu = toggle.nextElementSibling;
          if (submenu) submenu.classList.toggle('hidden');
          const icon = toggle.querySelector('i.fas.fa-chevron-down');
          if (icon) icon.classList.toggle('rotate-180');
        });
      });
    });
  </script>

</body>
</html>
