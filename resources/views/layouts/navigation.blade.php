<style>
  /* Pastikan transisi halus */
  #menu-toggle #bar1,
  #menu-toggle #bar2,
  #menu-toggle #bar3 {
    transition: transform .25s ease, opacity .25s ease;
  }

  /* Ketika tombol memiliki class 'open' ubah menjadi X */
  #menu-toggle.open #bar1 {
    transform: translateY(6px) rotate(45deg);
  }

  #menu-toggle.open #bar2 {
    opacity: 0;
    transform: translateX(-10px);
  }

  #menu-toggle.open #bar3 {
    transform: translateY(-10px) rotate(-45deg);
  }
</style>
<nav class="fixed top-0 left-0 w-full z-50 bg-green-700 text-white shadow-md">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      <div class="flex items-center">
        <!-- Tombol hamburger -->
        <button id="menu-toggle" data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar"
          aria-controls="logo-sidebar" aria-expanded="false" type="button"
          class="flex flex-col justify-between w-7 h-5 sm:hidden focus:outline-none mr-3 transition-transform duration-300 ease-in-out">
          <span id="bar1"
            class="block w-7 h-1 bg-white rounded-full origin-center transition-transform duration-300"></span>
          <span id="bar2"
            class="block w-7 h-1 bg-white rounded-full origin-center transition-opacity duration-300"></span>
          <span id="bar3"
            class="block w-7 h-1 bg-white rounded-full origin-center transition-transform duration-300"></span>
        </button>

        <!-- Logo -->
        <a class="flex items-center gap-3">
          <img src="{{ asset('storage/kwas_putih_2.png') }}" alt="KWaS" class="h-10 w-auto" />
        </a>
      </div>

      <div class="flex items-center">
        <details class="relative">
          <summary
            class="flex items-center gap-2 md:gap-3 cursor-pointer select-none bg-white text-gray-600 px-3 py-2 rounded-lg shadow-sm">
            <span
              class="inline-flex items-center justify-center h-6 w-6 rounded-full border-2 border-gray-800 text-gray-800 flex-shrink-0">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </span>
            <span
              class="text-xs md:text-sm font-medium max-w-[90px] md:max-w-none truncate whitespace-nowrap overflow-hidden">
              {{ optional(Auth::user())->name ?? optional(Auth::user())->role ?? 'Guest' }}
            </span>
          </summary>
        </details>
      </div>

    </div>
  </div>
</nav>

<script>
  (function () {
    const btn = document.getElementById('menu-toggle');
    if (!btn) return;
    btn.addEventListener('click', function () {
      const isOpen = btn.classList.toggle('open');
      // untuk aksesibilitas
      btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
  })();
</script>