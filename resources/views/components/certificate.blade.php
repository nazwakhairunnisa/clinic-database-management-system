<section class="relative w-full min-h-screen bg-white flex flex-col items-center justify-start overflow-hidden pt-[90px] -mt-[128px]">
  <h2 class="text-6xl font-abril font-semibold text-[#806B3F] mb-8 translate-y-16 text-center">
    Certificate
  </h2>

  <div id="certWrapper" class="flex gap-8 overflow-x-auto scroll-smooth snap-x snap-mandatory w-full px-8 py-4">
    <div class="flex-none w-[50vw]"></div>

    <div class="cert flex-none w-[500px] h-[650px] snap-center cursor-pointer transition-all duration-700 ease-in-out xs:max-w-[200px] xs:max-h-[260px]">
      <img src="{{ asset('images/sertifikat1.jpg') }}" class="w-full h-full object-contain transition-all duration-700 ease-in-out" alt="Certificate 1">
    </div>

    <div class="cert flex-none w-[500px] h-[650px] snap-center cursor-pointer transition-all duration-700 ease-in-out xs:max-w-[200px] xs:max-h-[260px]">
      <img src="{{ asset('images/sertifikat2.jpg') }}" class="w-full h-full object-contain transition-all duration-700 ease-in-out" alt="Certificate 2">
    </div>

    <div class="cert flex-none w-[500px] h-[650px] snap-center cursor-pointer transition-all duration-700 ease-in-out xs:max-w-[200px] xs:max-h-[260px]">
      <img src="{{ asset('images/sertifikat3.jpg') }}" class="w-full h-full object-contain transition-all duration-700 ease-in-out" alt="Certificate 3">
    </div>

    <div class="flex-none w-[50vw]"></div>
  </div>
</section>

<style>
#certWrapper::-webkit-scrollbar {
  display: none;
}

.cert img {
  opacity: 0.5;
  transform: scale(0.85);
  filter: blur(1px);
  transition: all 0.6s ease;
}

.cert.active img {
  opacity: 1;
  transform: translateY(-15px) scale(1.05);
  filter: drop-shadow(10px 12px 5px rgba(0, 0, 0, 0.35))
          drop-shadow(3px 5px 10px rgba(128, 107, 63, 0.25));
}

.cert.active:hover img {
  transform: translateY(-15px) scale(1.05);
}
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const certs = document.querySelectorAll(".cert");
  const wrapper = document.getElementById("certWrapper");

  let activeIndex = 1;
  certs[activeIndex].classList.add("active");
  centerCert(certs[activeIndex]);

  certs.forEach((cert, i) => {
    cert.addEventListener("click", () => {
      certs.forEach(c => c.classList.remove("active"));
      cert.classList.add("active");
      centerCert(cert);
      activeIndex = i;
    });
  });

  function centerCert(cert) {
    const offset = cert.offsetLeft - (wrapper.offsetWidth / 2) + (cert.offsetWidth / 2);
    wrapper.scrollTo({ left: offset, behavior: "smooth" });
  }
});
</script>
