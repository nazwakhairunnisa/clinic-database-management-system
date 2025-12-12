<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Clay Skinthetic')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800 overflow-x-hidden">

{{-- Include Navbar --}}
  @include('layouts.navbar')

  {{-- Jarak agar konten tidak tertutup navbar fixed --}}
  <div class="pt-[90px]">

    <main>
      @yield('content')
    </main>

  </div>

@if (!in_array(Route::currentRouteName(), [
    'treatment.all',
    'allpromo',
    'reservation'
]))
    @include('components.footer')
@endif

</body>
</html>