<section id="about" class="relative z-10 bg-white -mt-[70px]">
  <div class="max-w-7xl mx-auto px-6 py-16">
    <div class="flex flex-col md:flex-row items-start gap-8">

      {{-- teks About Us --}}
      <div class="flex-1 min-w-[300px] mb-12 md:mb-0 md:pl-[60px] pl-[30px]">
        <h2 class="font-abril font-bold text-[38px] sm:text-[48px] md:text-[60px] text-[#806B3F] mb-6 leading-tight ml-[0px] md:ml-[0px] lg:ml-[-40px]">
          About Us
        </h2>  

        {{-- lorem ipsum bebas kamu atur sendiri padding/ukuran --}}
        <p class="text-[18px] sm:text-[20px] md:text-[25px] text-black leading-normal md:leading-snug max-w-[600px] ml-[20px] md:ml-[2px] md:pl-0">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
          incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
          exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
          dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
        </p>
      </div>

      {{-- gambar About Us --}}
      <div class="w-full md:w-auto flex justify-center md:justify-start mb-6">
        <img 
          src="{{ asset('images/about.jpg') }}" 
          alt="About Us" 
          class="object-cover rounded-xl w-[80%] sm:w-[70%] md:w-[500px] lg:w-full lg:h-[450px] 
           lg:-translate-x-[80px] lg:translate-y-[30px]">
      </div>

    </div>
  </div>
</section>

<!-- Bagian Statistik -->
<section class="relative left-1/2 right-1/2 -ml-[50vw] -mr-[50vw]
w-screen flex justify-center bg-[#FBF7E7] border-y-2 border-[#bfa16c]
py-28 md:py-10">
  <div class="flex flex-col md:flex-row justify-between items-center 
  w-full max-w-6xl text-center space-y-24 md:space-y-0 md:gap-14 px-6">

 {{-- Total Treatment --}}
        <div class="flex flex-col items-center gap-2 transition-all duration-700 opacity-0 translate-y-5">
          <h3 class="countup font-abril font-bold text-7xl md:text-6xl text-[#806B3F]" data-end="29">0</h3>
          <p class="text-2xl md:text-xl text-black">Total Treatment</p>
        </div>

      <!-- Garis pembatas --> 
      <div class="hidden md:flex relative items-center justify-center"> 
        <div class="absolute w-[2px] h-[130px] bg-[#806B3F]"></div> 
      </div>

        {{-- Patients --}}
        <div class="flex flex-col items-center gap-2 transition-all duration-700 opacity-0 translate-y-5">
          <h3 class="countup font-abril font-bold text-7xl md:text-6xl text-[#806B3F]" data-end="100" data-suffix="+">0</h3>
          <p class="text-2xl md:text-xl text-black">Patients</p>
        </div>

      <!-- Garis pembatas --> 
      <div class="hidden md:flex relative items-center justify-center"> 
        <div class="absolute w-[2px] h-[130px] bg-[#806B3F]"></div> 
      </div>

        {{-- Certificate --}}
        <div class="flex flex-col items-center gap-2 transition-all duration-700 opacity-0 translate-y-5">
          <h3 class="countup font-abril font-bold text-7xl md:text-6xl text-[#806B3F]" data-end="11">0</h3>
          <p class="text-2xl md:text-xl text-black">Certificate</p>
        </div>

      <!-- Garis pembatas --> 
      <div class="hidden md:flex relative items-center justify-center"> 
        <div class="absolute w-[2px] h-[130px] bg-[#806B3F]"></div> 
      </div>

        {{-- Years of Experience --}}
        <div class="flex flex-col items-center gap-2 transition-all duration-700 opacity-0 translate-y-5">
          <h3 class="countup font-abril font-bold text-7xl md:text-6xl text-[#806B3F]" data-end="3" data-suffix="+">0</h3>
          <p class="text-2xl md:text-xl text-black">Years of Experience</p>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const easeOutCubic = t => 1 - Math.pow(1 - t, 3);

  function animateCount(el){
    if (el.dataset.animated === "true") return;
    el.dataset.animated = "true";

    const end = parseFloat(el.dataset.end || "0");
    const suffix = el.dataset.suffix || "";
    const decimals = parseInt(el.dataset.decimals || "0", 10);
    const duration = parseInt(el.dataset.duration || "1500", 10);

    const fmt = new Intl.NumberFormat("id-ID", {
      minimumFractionDigits: decimals,
      maximumFractionDigits: decimals
    });

    const startTime = performance.now();
    function tick(now){
      const t = Math.min((now - startTime) / duration, 1);
      const value = end * easeOutCubic(t);
      el.textContent = (decimals ? value : Math.floor(value)).toLocaleString("id-ID") + suffix;
      if (t < 1) requestAnimationFrame(tick);
      else el.textContent = fmt.format(end) + suffix;
    }
    requestAnimationFrame(tick);
  }

  const counters = document.querySelectorAll(".countup");
  const cards = document.querySelectorAll(".flex.flex-col.items-center.gap-2.transition-all");

  const io = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const el = entry.target;
        el.classList.remove("opacity-0", "translate-y-5"); // fade-in naik halus
        const count = el.querySelector(".countup");
        if (count) animateCount(count);
        io.unobserve(entry.target);
      }
    });
  }, { threshold: 0.4 });

  cards.forEach(el => io.observe(el));
});
</script>