<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Clay Skinthetic')</title>
  @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 text-gray-800">

  {{-- panggil navbar --}}
  @include('layouts.navbar')

  {{-- isi halaman --}}
  <main class="pt-20">
      @yield('content')
  </main>

</body>
</html>
