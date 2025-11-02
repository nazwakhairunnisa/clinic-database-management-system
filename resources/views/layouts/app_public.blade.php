<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Clay Skinthetic')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-gray-50 text-gray-800">

  {{-- navbar --}}
  @include('layouts.navbar')

  {{-- konten halaman --}}
  <main class="pt-[90px]">
      @yield('content')
  </main>

</body>
</html>
