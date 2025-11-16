<footer class="mt-auto w-full bg-[#806B3F] text-[#f8f4ec] font-serif">
  <div class="mx-auto max-w-7xl px-4 sm:px-8 py-6 md:py-3">

    <div class="grid grid-cols-1 md:grid-cols-3 items-center md:items-start gap-6 md:gap-10 text-center md:text-left">

      <!-- Logo -->
      <div class="flex flex-col items-center md:items-start">
        <img
          src="{{ asset('images/footer-logo.png') }}"
          alt="Clay Skinthetic Clinic — logo"
          class="h-[120px] w-auto mb-4 md:mb-0 md:h-[140px] md:scale-125 md:translate-y-2 md:mt-4"
          loading="lazy"
        />
      </div>

      <!-- Contact -->
      <address class="not-italic flex flex-col items-center md:items-start md:-ml-28 md:mt-6">
        <h2 class="text-xl sm:text-2xl md:text-3xl font-abril font-bold mb-2">
          Contact Information
        </h2>

        <a href="tel:082161835144"
           class="text-base sm:text-lg hover:underline underline-offset-4 mb-1"
           aria-label="Call 0821 6183 5144">
          0821 6183 5144
        </a>

        <p class="text-base sm:text-lg leading-relaxed">
          Jl. Jendral Ahmad Yani<br>(Kp. Kruni), Stabat
        </p>
      </address>

      <!-- Social Media -->
      <div class="flex flex-col items-center md:items-start md:-translate-x-6 md:mt-6 mt-6">
        <h2 class="text-xl sm:text-2xl md:text-3xl font-abril font-bold mb-2">
          Our Social Media
        </h2>

        <a href="https://www.instagram.com/clayskinthetic"
           target="_blank"
           rel="noopener"
           class="inline-flex items-center gap-3 group">
          <img
            src="{{ asset('images/Instagram.png') }}"
            alt="Instagram"
            class="w-[46px] h-[46px] sm:w-[52px] sm:h-[52px] transition group-hover:opacity-80"
            loading="lazy"
          />
        </a>
      </div>
    </div>

    <hr class="mt-8 md:mt-14 border-t border-[#f8f4ec]/70" />

    <div class="pt-4 md:pt-4">
      <p class="text-center md:text-right text-xs sm:text-sm md:text-[18px]">
        © 2025 Clay Skinthetic Clinic. All rights reserved.
      </p>
    </div>

  </div>
</footer>
