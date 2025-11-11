@extends('layouts.app_public')

@section('content')

  {{-- Home Section --}}
  <section id="home">
    @include('components.home')
  </section>

  {{-- About Section --}}
  <section id="about">
    @include('components.about')
  </section>

  {{-- Treatment Section --}}
  <section id="treatment" class="min-h-[100vh] py-16">
    @include('components.treatment')
  </section>

  {{-- Promo Section --}}
  <section id="promo" class="py-">
    @include('components.promo')
  </section>

  {{-- Certificate Section --}}
  <section id="certificate">
    @include('components.certificate')
  </section>

  {{-- Contact Section --}}
  <section id="contact">
    @include('components.contact')
  </section>

@endsection
