<footer class="mt-auto w-full bg-[#806B3F] text-[#f8f4ec] font-serif">
  <div class="mx-auto max-w-7xl px-4 sm:px-8 py-8">

    <!-- GRID: mobile = 1 kolom, desktop = 3 kolom -->
    <div class="grid grid-cols-1 md:grid-cols-3 items-start gap-8 md:gap-10 text-center md:text-left">

      <!-- LOGO -->
      <div class="flex flex-col items-center md:items-start">
        <img
          src="{{ asset('images/footer-logo.png') }}"
          alt="Clay Skinthetic Clinic — logo"
          class="h-[140px] md:scale-125 md:translate-y-2 w-auto"
          loading="lazy"
        />
      </div>

      <!-- CONTACT INFO -->
      <address class="not-italic flex flex-col items-center md:items-start md:-ml-28">
        <h2 class="text-2xl sm:text-4xl font-abril font-bold mb-3">
          Contact Information
        </h2>

        <a href="tel:082161835144"
           class="text-lg sm:text-2xl hover:underline underline-offset-4 mb-1"
           aria-label="Call 0821 6183 5144">
          0821 6183 5144
        </a>

        <p class="text-lg sm:text-2xl leading-relaxed">
          Jl. Jendral Ahmad Yani<br>(Kp. Kruni), Stabat
        </p>
      </address>

      <!-- SOCIAL MEDIA -->
      <div class="flex flex-col items-center md:items-start md:-translate-x-6">
        <h2 class="text-2xl sm:text-4xl font-abril font-bold mb-3">
          Our Social Media
        </h2>

        <a href="https://www.instagram.com/clayskinthetic"
           target="_blank"
           rel="noopener"
           class="inline-flex items-center gap-3 group">
          <img
            src="{{ asset('images/Instagram.png') }}"
            alt="Instagram"
            class="w-[50px] sm:w-[58px] h-[50px] sm:h-[58px] transition group-hover:opacity-80"
            loading="lazy"
          />
          <span class="sr-only">Follow us on Instagram</span>
        </a>
      </div>
    </div>

    <!-- Divider -->
    <hr class="mt-6 md:mt-10 border-t border-[#f8f4ec]/70" />

    <!-- Bottom bar -->
    <div class="pt-3 md:pt-6">
      <p class="text-center md:text-right text-sm md:text-[18px]">
        © 2025 Clay Skinthetic Clinic. All rights reserved.
      </p>
    </div>

  </div>
</footer>
