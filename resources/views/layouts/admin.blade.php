<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <title>@yield('pageTitle', 'Admin Dashboard')</title>

  {{-- Vite --}}
  @vite('resources/css/app.css')

  {{-- Fonts --}}
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&display=swap" rel="stylesheet">

  {{-- Alpine --}}
  <script src="//unpkg.com/alpinejs" defer></script>

  {{-- FullCalendar --}}
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>

  {{-- Iconify --}}
  <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

  {{-- FontAwesome --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body x-data="{ sidebarOpen: false }" data-role="{{ auth()->user()->role }}" class="bg-[#F8F6F1] font-['Roboto']">

{{-- NAVBAR --}}
<nav
  class="fixed top-0 left-0 w-full h-[70px] bg-white shadow-md z-50
         flex items-center justify-between px-4 sm:px-6 lg:px-8">

  <div class="flex items-center">
    {{-- Hamburger (mobile only) --}}
    <button @click="sidebarOpen = !sidebarOpen"
      class="block lg:hidden text-[#806B3F] text-3xl mr-3">
      <iconify-icon icon="tabler:menu-2"></iconify-icon>
    </button>

    {{-- Logo --}}
    <img src="{{ asset('images/logo.jpg') }}" class="h-10 sm:h-14 w-auto">
  </div>

  <div class="flex-1">
    <h1
      class="text-[1.2rem] sm:text-[1.6rem] md:text-[1.8rem] 
             font-['Abril_Fatface'] tracking-tight text-[#806B3F]
             ml-2 sm:ml-4 lg:ml-[260px]">
      @yield('pageTitle', 'Admin Dashboard')
    </h1>
  </div>

  <div class="flex items-center space-x-3 md:space-x-4">
    @if (request()->routeIs('admin.dashboard'))
      <button class="w-10 h-10 flex items-center justify-center rounded-full bg-[#F5EAD5]/80 hover:bg-[#EED892] transition">
        <iconify-icon icon="tabler:search" class="text-xl text-gray-700"></iconify-icon>
      </button>
    @endif

    <button class="w-10 h-10 flex items-center justify-center rounded-full bg-[#F5EAD5]/80 hover:bg-[#EED892] transition">
      <iconify-icon icon="tabler:bell" class="text-xl text-gray-700"></iconify-icon>
    </button>

    <form method="POST" action="{{ route('logout') }}" class="inline">
      @csrf
      <button type="submit" class="flex items-center bg-gray-200/50 rounded-lg hover:bg-gray-300 px-3 py-2 transition">
        <iconify-icon icon="tabler:logout" class="text-2xl text-gray-700"></iconify-icon>
        <span class="hidden sm:inline ml-2 text-base font-normal text-gray-700">Logout</span>
      </button>
    </form>
  </div>
</nav>

{{-- SIDEBAR --}}
<aside
  class="fixed top-[70px] left-0 h-[calc(100%-70px)] bg-[#FBF7E7] shadow-lg flex flex-col z-40 w-64 lg:w-[260px] transform transition-transform duration-300"
  :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

  <div class="flex-1 p-10 overflow-y-auto">
    {{-- compute which submenu should be open based on current route --}}
    @php
      $openLayanan = request()->routeIs('admin.treatment.*') || request()->routeIs('admin.promo.*');
      $openInventori = request()->routeIs('admin.stok-obat.*') || request()->routeIs('admin.supplier.*') || request()->routeIs('admin.pembelian_obat.*');
    @endphp

    <ul class="space-y-1.5 text-[#806B3F]">
      {{-- Dashboard --}}
      <li>
        <a href="{{ route('admin.dashboard') }}" class="flex items-center rounded-lg hover:bg-[#E9E1C5] text-[1rem]">
          <iconify-icon icon="material-symbols-light:dashboard-outline-rounded" class="text-xl mr-2 text-black"></iconify-icon>
          Dashboard
        </a>
      </li>

      {{-- Jadwal Operasional --}}
    <li>
      <a href="{{ route('admin.jadwal_operasional.index') }}"
        class="flex items-center rounded-lg text-[1rem] {{ request()->routeIs('admin.jadwal_operasional.*') ? 'bg-[#E9E1C5] font-semibold' : 'hover:bg-[#E9E1C5]' }}">
        <iconify-icon icon="material-symbols:calendar-clock-outline" class="text-xl mr-2 text-black"></iconify-icon>
        Jadwal Operasional
      </a>
    </li>

      {{-- Daftar Reservasi --}}
      <li>
        <a href="{{ route('admin.jadwal_reservasi.index') }}"
          class="flex items-center rounded-lg text-[1rem] {{ request()->routeIs('admin.jadwal_reservasi.*') ? 'bg-[#E9E1C5] font-semibold' : 'hover:bg-[#E9E1C5]' }}">
          <iconify-icon icon="material-symbols-light:schedule-outline" class="text-xl mr-2 text-black"></iconify-icon>
          Daftar Reservasi
        </a>
      </li>
    
      {{-- Pembayaran --}}
      <li>
        <a href="{{ route('admin.pembayaran.index') }}"
          class="flex items-center rounded-lg hover:bg-[#E9E1C5] text-[1rem]">
          <iconify-icon icon="material-symbols-light:money-outline" class="text-xl mr-2 text-black"></iconify-icon>
          Daftar Pembayaran
        </a>
      </li>

      {{-- Riwayat Pembayaran --}}
      <li>
        <a href="{{ route('admin.pembayaran.riwayat') }}"
          class="flex items-center rounded-lg hover:bg-[#E9E1C5] text-[1rem]">
          <iconify-icon icon="mdi:history" class="text-xl mr-2 text-black"></iconify-icon>
          Riwayat Pembayaran
        </a>
      </li>

      {{-- Daftar Pasien --}}
      <li>
        <a href="{{ route('admin.pasien.index') }}" class="flex items-center rounded-lg hover:bg-[#E9E1C5] text-[1rem]">
          <iconify-icon icon="circum:user" class="text-xl mr-2 text-black"></iconify-icon>
          Daftar Pasien
        </a>
      </li>

      <div class="h-2"></div>

      {{-- LAYANAN --}}
      <li>
        <div class="flex items-center justify-between pt-0 pb-0 cursor-pointer menu-toggle">
          <div class="flex items-center space-x-2">
            <iconify-icon icon="material-symbols:inventory" class="text-xl text-black"></iconify-icon>
            <span class="uppercase text-[1rem] tracking-wider font-semibold leading-[1]">Layanan</span>
          </div>
          <iconify-icon icon="tabler:chevron-down"
            class="text-sm text-black transition-transform duration-200 {{ $openLayanan ? 'rotate-180' : '' }}"></iconify-icon>
        </div>

        <div class="border-b border-[#806B3F] ml-[1.8rem]"></div>

        <ul class="ml-[2.2rem] mt-[4px] space-y-1 submenu {{ $openLayanan ? '' : 'hidden' }}">
          <li><a href="{{ route('admin.treatment.index') }}" class="block text-[1rem] hover:text-gray-700">Daftar Treatment</a></li>
          <li><a href="{{ route('admin.promo.index') }}" class="block text-[1rem] hover:text-gray-700">Daftar Promo</a></li>
        </ul>
      </li>
        <div class="h-1"></div>

      {{-- INVENTORI --}}
      <li>
        <div class="flex items-center justify-between pt-0 pb-0 cursor-pointer menu-toggle">
          <div class="flex items-center space-x-2">
            <iconify-icon icon="si:inventory-duotone" class="text-xl text-black"></iconify-icon>
            <span class="uppercase text-[1rem] tracking-wider font-semibold leading-[1]">Inventori</span>
          </div>
          <iconify-icon icon="tabler:chevron-down"
            class="text-sm text-black transition-transform duration-200 {{ $openInventori ? 'rotate-180' : '' }}"></iconify-icon>
        </div>

        <div class="border-b border-[#806B3F] ml-[1.8rem]"></div>

        <ul class="ml-[2.2rem] mt-[4px] space-y-1 submenu {{ $openInventori ? '' : 'hidden' }}">
          <li><a href="{{ route('admin.stok-obat.index') }}" class="block text-[1rem] hover:text-gray-700">Daftar Obat</a></li>
        </ul>
      </li>
    </ul>
    </div>
        <div class="h-1"></div>

  {{-- Footer Sidebar --}}
  <div class="bg-[#8B6B3F] text-white flex items-center py-3 px-6">
    <iconify-icon icon="ion:person-circle-outline" class="text-5xl"></iconify-icon>
    <div class="ml-2 leading-tight">
      <p>Logged in as</p>
      <p class="font-bold text-base">{{ Auth::user()->username }}</p>
    </div>
  </div>
</aside>

{{-- BACKDROP MOBILE --}}
<div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-40 z-30 lg:hidden"></div>

{{-- MAIN CONTENT --}}
<main class="relative z-0">
  <div class="min-h-screen px-4 sm:px-6 lg:px-8 lg:ml-[260px] pt-[90px]">
    @yield('content')
  </div>
</main>

{{-- Dropdown / Toggle Script --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
  // Only toggle when clicking the header (menu-toggle), not when clicking child links
  document.querySelectorAll(".menu-toggle").forEach(toggle => {
    toggle.addEventListener("click", (e) => {
      e.stopPropagation();

      const parent = toggle.closest("li");
      const submenu = parent.querySelector(".submenu");
      const arrow = toggle.querySelector('iconify-icon[icon="tabler:chevron-down"]');

      if (!submenu) return;

      submenu.classList.toggle("hidden");
      if (arrow) arrow.classList.toggle("rotate-180");
    });
  });

  
  document.querySelectorAll("aside .submenu a").forEach(a => {
    a.addEventListener("click", (e) => {
    });
  });

  
});
</script>

</body>
</html>