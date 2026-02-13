<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>@yield('title') - @yield('role')</title>

   <link rel="manifest" href="{{ asset('manifest.json') }}">
   <meta name="theme-color" content="#038016">

   <meta name="csrf-token" content="{{ csrf_token() }}">
   <!-- icon logo kwas di tab browser -->
   <link rel="icon" type="image/png" href="{{ asset('storage/kwas_hijau.png') }}">
   <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
   @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
@stack('scripts')

<body class="bg-gray-100">

   @include('layouts.navigation')

   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <!-- pengganti seetbar -->
   <script>
      window.__kwasSwalConfirm = async function (options) {
         const {
            title = 'Konfirmasi',
            text = 'Apakah Anda yakin?',
            icon = 'warning',
            confirmButtonText = 'Ya',
            cancelButtonText = 'Batal',
            confirmButtonColor = '#dc2626',
            cancelButtonColor = '#6b7280'
         } = options || {};

         if (typeof Swal === 'undefined') return window.confirm(text);

         const result = await Swal.fire({
            title,
            text,
            icon,
            showCancelButton: true,
            confirmButtonText,
            cancelButtonText,
            confirmButtonColor,
            cancelButtonColor
         });

         return !!result.isConfirmed;
      };

      document.addEventListener('DOMContentLoaded', () => {
         // Flash message (success/error/info/warning)
         const flash = {
            success: @json(session('success')),
            error: @json(session('error')),
            info: @json(session('info')),
            warning: @json(session('warning')),
         };

         if (flash.success) {
            Swal.fire({
               icon: 'success',
               title: 'Berhasil',
               text: flash.success,
               timer: 1800,
               showConfirmButton: false
            });
         } else if (flash.error) {
            Swal.fire({
               icon: 'error',
               title: 'Gagal',
               text: flash.error,
            });
         } else if (flash.warning) {
            Swal.fire({
               icon: 'warning',
               title: 'Perhatian',
               text: flash.warning,
            });
         } else if (flash.info) {
            Swal.fire({
               icon: 'info',
               title: 'Info',
               text: flash.info,
            });
         }

         // Konfirmasi submit untuk form yang punya atribut data
         const confirmForms = document.querySelectorAll(
            'form[data-swal-confirm], form[data-confirm], form[data-confirm-delete]'
         );

         confirmForms.forEach((form) => {
            form.addEventListener('submit', async (e) => {
               if (form.dataset.swalConfirmed === '1') return;
               e.preventDefault();

               const text =
                  form.dataset.swalText ||
                  form.dataset.confirmText ||
                  form.dataset.text ||
                  form.dataset.message ||
                  (form.dataset.nama ? `Apakah Anda yakin ingin menghapus ${form.dataset.nama}?` : 'Apakah Anda yakin?');

               const title =
                  form.dataset.swalTitle ||
                  form.dataset.confirmTitle ||
                  (form.dataset.confirmDelete ? 'Hapus data?' : 'Konfirmasi');

               const confirmText =
                  form.dataset.swalConfirmText ||
                  form.dataset.confirmButtonText ||
                  (form.dataset.confirmDelete ? 'Ya, hapus' : 'Ya');

               const ok = await window.__kwasSwalConfirm({
                  title,
                  text,
                  icon: form.dataset.swalIcon || (form.dataset.confirmDelete ? 'warning' : 'question'),
                  confirmButtonText: confirmText,
                  cancelButtonText: form.dataset.swalCancelText || 'Batal',
               });

               if (ok) {
                  form.dataset.swalConfirmed = '1';
                  form.submit();
               }
            });
         });
      });
   </script>

   <aside id="logo-sidebar"
      class="fixed top-5 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
      aria-label="Sidebar">
      <div class="h-full px-3 py-4 overflow-y-auto bg-green-600">
         <span class="flex justify-center text-xl font-bold whitespace-nowrap text-white pb-2 mt-14">Menu
            @yield('role')</span>
         @php
            $role = optional(Auth::user())->role;
         @endphp
         <ul class="space-y-2 font-medium mt-3">
            @if ($role === 'admin')
               <li>
                  <a href="{{ route('admin.dashboard') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 21">
                        <path
                           d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path
                           d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                     </svg>
                     <span class="ms-3">Dashboard</span>
                  </a>
               </li>
               <li>
                  <a href="{{ route('admin.register') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 21">
                        <path
                           d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                     </svg>
                     <span class="ms-3">Tambah User</span>
                  </a>
               </li>
               <li>
                  <a href="{{ route('admin.daftarbarang') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 21">
                        <path
                           d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z" />
                     </svg>
                     <span class="ms-3">Daftar Barang</span>
                  </a>
               </li>
               <li>
                  <a href="{{ route('admin.daftarPembeli') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 512 512">
                        <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" stroke="none">
                           <path d="M2380 4991 c-270 -59 -488 -230 -607 -476 -62 -131 -85 -225 -86 -370 -2 -193 46 -354 153 -509 127 -184 330 -318 549 -362 112 -22 304 -15 409 15 340 98 587 390 633 748 45 354 -158 723 -487 883 -116 57 -216 81 -359 86 -92 3 -140 -1 -205 -15z m235 -286 c20 -20 25 -34 25 -78 l0 -53 54 -23 c96 -39 166 -124 166 -200 0 -46 -33 -81 -75 -81 -39 0 -64 18 -84 61 -23 48 -74 79 -130 79 -88 0 -143 -50 -127 -113 9 -39 53 -64 168 -95 58 -16 126 -41 152 -56 102 -60 138 -202 78 -311 -32 -58 -98 -111 -158 -127 l-44 -12 0 -55 c0 -68 -25 -101 -79 -101 -58 0 -81 31 -81 107 0 46 -4 63 -13 63 -8 0 -41 17 -74 39 -155 100 -196 289 -60 279 35 -3 45 -8 63 -38 62 -98 108 -130 184 -130 106 0 164 72 108 134 -17 18 -50 32 -120 50 -208 54 -288 131 -288 276 0 104 49 179 146 225 l54 26 0 55 c0 66 29 104 80 104 19 0 40 -9 55 -25z"/>
                           <path d="M1030 3300 c-167 -26 -316 -128 -400 -276 -37 -64 -70 -188 -70 -264 0 -76 33 -200 70 -265 88 -154 236 -253 413 -277 244 -33 493 121 579 357 26 75 36 224 18 304 -39 178 -193 348 -364 400 -77 23 -175 32 -246 21z"/>
                           <path d="M3934 3300 c-219 -33 -408 -208 -454 -421 -18 -80 -8 -229 18 -304 65 -177 222 -312 412 -351 278 -58 558 121 630 402 25 98 25 170 0 268 -58 229 -251 389 -490 409 -36 3 -88 2 -116 -3z"/>
                           <path d="M2455 3028 c-27 -5 -88 -27 -133 -49 -337 -162 -411 -608 -146 -873 105 -106 234 -159 384 -159 150 0 279 53 384 159 214 213 214 556 1 769 -129 129 -313 187 -490 153z"/>
                           <path d="M659 1919 c-248 -36 -469 -202 -580 -434 -71 -148 -74 -169 -74 -540 l0 -330 28 -57 c34 -70 101 -131 173 -158 52 -19 76 -20 569 -20 l515 0 0 303 c0 339 10 416 70 560 89 214 284 415 485 498 30 12 55 26 55 30 0 13 -115 78 -186 105 -127 48 -187 53 -609 52 -214 0 -415 -4 -446 -9z"/>
                           <path d="M3545 1916 c-38 -8 -101 -26 -139 -40 -71 -27 -186 -92 -186 -105 0 -4 25 -18 55 -30 201 -83 396 -284 485 -498 60 -144 70 -221 70 -560 l0 -303 515 0 c493 0 517 1 569 20 72 27 139 88 173 158 l28 57 0 330 c0 371 -3 392 -74 540 -98 204 -269 350 -491 416 -72 22 -92 23 -505 25 -326 2 -447 0 -500 -10z"/>
                           <path d="M2085 1645 c-246 -47 -455 -211 -560 -437 -65 -142 -70 -175 -70 -533 l0 -320 26 -56 c32 -68 82 -120 152 -157 l52 -27 875 0 875 0 52 27 c68 36 120 90 152 158 l26 55 0 320 c0 360 -4 392 -75 540 -97 203 -270 350 -490 416 -72 22 -91 23 -510 25 -333 2 -451 0 -505 -11z"/>
                        </g>         
                     </svg>
                     <span class="ms-3">Daftar Pembeli</span>
                  </a>
               </li>

            @elseif ($role === 'marketing')
               <li>
                  <a href="{{ route('marketing.dashboard') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 21">
                        <path
                           d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path
                           d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                     </svg>
                     <span class="ms-3">Dashboard</span>
                  </a>
               </li>
               <li>
                  <a href="{{ route('marketing.daftarbarang') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 21">
                        <path
                           d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z" />
                     </svg>
                     <span class="ms-3">Daftar Barang</span>
                  </a>
               </li>


               {{-- 👉 Daftar Pemesanan (Show) --}}
               <li>
                  <a href="{{ route('marketing.pemesanan.index') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" ria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 640 512">
                        <path
                           d="M0 8C0-5.3 10.7-16 24-16l45.3 0c27.1 0 50.3 19.4 55.1 46l.4 2 187.2 0 0 102.1-31-31c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l72 72c9.4 9.4 24.6 9.4 33.9 0l72-72c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-31 31 0-102.1 177.4 0c20 0 35.1 18.2 31.4 37.9L537.8 235.8c-5.7 30.3-32.1 52.2-62.9 52.2l-303.6 0 5.1 28.3c2.1 11.4 12 19.7 23.6 19.7L456 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-255.9 0c-34.8 0-64.6-24.9-70.8-59.1L77.2 38.6c-.7-3.8-4-6.6-7.9-6.6L24 32C10.7 32 0 21.3 0 8zM160 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm224 0a48 48 0 1 1 96 0 48 48 0 1 1 -96 0z" />
                     </svg>
                     <span class="ms-3">Daftar Pemesanan</span>
                  </a>
               </li>



            @elseif ($role === 'ppic')
               <li>
                  <a href="{{ route('ppic.dashboard') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 21">
                        <path
                           d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path
                           d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                     </svg>
                     <span class="ms-3">Dashboard</span>
                  </a>
               </li>
               {{-- 👉 Progres Produksi --}}
               <li>
                  <a href="{{ route('ppic.progres.index') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 512 512"
                        fill="currentColor">
                        <path
                           d="M195.1 9.5C198.1-5.3 211.2-16 226.4-16l59.8 0c15.2 0 28.3 10.7 31.3 25.5L332 79.5c14.1 6 27.3 13.7 39.3 22.8l67.8-22.5c14.4-4.8 30.2 1.2 37.8 14.4l29.9 51.8c7.6 13.2 4.9 29.8-6.5 39.9L447 233.3c.9 7.4 1.3 15 1.3 22.7s-.5 15.3-1.3 22.7l53.4 47.5c11.4 10.1 14 26.8 6.5 39.9l-29.9 51.8c-7.6 13.1-23.4 19.2-37.8 14.4l-67.8-22.5c-12.1 9.1-25.3 16.7-39.3 22.8l-14.4 69.9c-3.1 14.9-16.2 25.5-31.3 25.5l-59.8 0c-15.2 0-28.3-10.7-31.3-25.5l-14.4-69.9c-14.1-6-27.2-13.7-39.3-22.8L73.5 432.3c-14.4 4.8-30.2-1.2-37.8-14.4L5.8 366.1c-7.6-13.2-4.9-29.8 6.5-39.9l53.4-47.5c-.9-7.4-1.3-15-1.3-22.7s.5-15.3 1.3-22.7L12.3 185.8c-11.4-10.1-14-26.8-6.5-39.9L35.7 94.1c7.6-13.2 23.4-19.2 37.8-14.4l67.8 22.5c12.1-9.1 25.3-16.7 39.3-22.8L195.1 9.5zM256.3 336a80 80 0 1 0 -.6-160 80 80 0 1 0 .6 160z" />
                     </svg>
                     <span class="flex-1 ml-1 whitespace-nowrap">Progres Produksi</span>
                  </a>

               </li>
               {{-- 👉 Daftar Pemesanan (Show) --}}
               <li>
                  <a href="{{ route('ppic.pemesanan.index') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                           d="M3 4a1 1 0 0 0-1 1v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V5a1 1 0 0 0-1-1H3Zm2 3h10v2H5V7Zm0 4h10v2H5v-2Z" />
                     </svg>
                     <span class="flex-1 ms-3 whitespace-nowrap">Daftar Pemesanan</span>
                  </a>
               </li>

               {{-- 👉 Daftar Bahan Pendukung--}}
               <li>
                  <a href="{{ route('ppic.daftarbahanpendukung') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 576 512">
                        <path
                           d="M0 142.1L0 480c0 17.7 14.3 32 32 32s32-14.3 32-32l0-240c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32l0 240c0 17.7 14.3 32 32 32s32-14.3 32-32l0-337.9c0-27.5-17.6-52-43.8-60.7L303.2 5.1c-9.9-3.3-20.5-3.3-30.4 0L43.8 81.4C17.6 90.1 0 114.6 0 142.1zM464 256l-352 0 0 64 352 0 0-64zM112 416l352 0 0-64-352 0 0 64zm352 32l-352 0 0 64 352 0 0-64z" />
                     </svg>
                     <span class="ms-3">Stok Bahan Pendukung</span>
                  </a>
               </li>

               {{-- 👉 Daftar Order Bahan Pendukung --}}
               <li>
                  <a href="{{ route('ppic.daftarorderbahanpendukung') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 512 512">
                        <path
                           d="M311.4 32l8.6 0c35.3 0 64 28.7 64 64l0 352c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 96C0 60.7 28.7 32 64 32l8.6 0C83.6 12.9 104.3 0 128 0L256 0c23.7 0 44.4 12.9 55.4 32zM248 112c13.3 0 24-10.7 24-24s-10.7-24-24-24L136 64c-13.3 0-24 10.7-24 24s10.7 24 24 24l112 0zM128 256a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm32 0c0 13.3 10.7 24 24 24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-112 0c-13.3 0-24 10.7-24 24zm0 128c0 13.3 10.7 24 24 24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-112 0c-13.3 0-24 10.7-24 24zM96 416a32 32 0 1 0 0-64 32 32 0 1 0 0 64z" />
                     </svg>
                     <span class="ms-3">Daftar Order BP</span>
                  </a>
               </li>

               <li>
                  <a href="{{ route('ppic.daftarbarang') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 21">
                        <path
                           d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z" />
                     </svg>
                     <span class="ms-3">Daftar Barang & Stok</span>
                  </a>
               </li>

               <!-- Packing -->
               <li>
                  <a href="{{ route('ppic.packing.index') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 122.88 114.58">
                        <path
                           d="M118.13,9.54a3.25,3.25,0,0,1,2.2.41,3.28,3.28,0,0,1,2,3l.57,78.83a3.29,3.29,0,0,1-1.59,3L89.12,113.93a3.29,3.29,0,0,1-2,.65,3.07,3.07,0,0,1-.53,0L3.11,105.25A3.28,3.28,0,0,1,0,102V21.78H0A3.28,3.28,0,0,1,2,18.7L43.89.27h0A3.19,3.19,0,0,1,45.63,0l72.5,9.51Zm-37.26,1.7-24.67,14,30.38,3.88,22.5-14.18-28.21-3.7Zm-29,20L50.75,64.62,38.23,56.09,25.72,63.17l2.53-34.91L6.55,25.49V99.05l77.33,8.6V35.36l-32-4.09Zm-19.7-9.09L56.12,8,45.7,6.62,15.24,20l16.95,2.17ZM90.44,34.41v71.12l25.9-15.44-.52-71.68-25.38,16Z" />
                     </svg>
                     <span class="ms-3">Daftar Packing</span>
                  </a>
               </li>

               <!-- Pelacakan pemenuhan pemesanan -->
               <li>
                  <a href="{{ route('ppic.progres.pemesanan') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 21">
                        <path
                           d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z" />
                     </svg>
                     <span class="ms-3">Progres Pemesanan</span>
                  </a>
               </li>

               <!-- Kebutuhan Kardus -->
               <li>
                  <a href="{{ route('ppic.kebutuhan_kardus') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path
                           d="M12.013 6.175 7.006 9.369l5.007 3.194-5.007 3.193L2 12.545l5.006-3.193L2 6.175l5.006-3.194 5.007 3.194ZM6.981 17.806l5.006-3.193 5.006 3.193L11.987 21l-5.006-3.194Z" />
                        <path
                           d="m12.013 12.545 5.006-3.194-5.006-3.176 4.98-3.194L22 6.175l-5.007 3.194L22 12.562l-5.007 3.194-4.98-3.211Z" />
                     </svg>
                     <span class="ms-3">Kebutuhan Kardus</span>
                  </a>
               </li>


            @elseif ($role === 'keprod')
               <li>
                  <a href="{{ route('keprod.dashboard') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 21">
                        <path
                           d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path
                           d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                     </svg>
                     <span class="ms-3">Dashboard</span>
                  </a>
               </li>

               {{-- 👉 Progres Produksi --}}
               <li>
                  <a href="{{ route('keprod.progres.index') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 512 512"
                        fill="currentColor">
                        <path
                           d="M195.1 9.5C198.1-5.3 211.2-16 226.4-16l59.8 0c15.2 0 28.3 10.7 31.3 25.5L332 79.5c14.1 6 27.3 13.7 39.3 22.8l67.8-22.5c14.4-4.8 30.2 1.2 37.8 14.4l29.9 51.8c7.6 13.2 4.9 29.8-6.5 39.9L447 233.3c.9 7.4 1.3 15 1.3 22.7s-.5 15.3-1.3 22.7l53.4 47.5c11.4 10.1 14 26.8 6.5 39.9l-29.9 51.8c-7.6 13.1-23.4 19.2-37.8 14.4l-67.8-22.5c-12.1 9.1-25.3 16.7-39.3 22.8l-14.4 69.9c-3.1 14.9-16.2 25.5-31.3 25.5l-59.8 0c-15.2 0-28.3-10.7-31.3-25.5l-14.4-69.9c-14.1-6-27.2-13.7-39.3-22.8L73.5 432.3c-14.4 4.8-30.2-1.2-37.8-14.4L5.8 366.1c-7.6-13.2-4.9-29.8 6.5-39.9l53.4-47.5c-.9-7.4-1.3-15-1.3-22.7s.5-15.3 1.3-22.7L12.3 185.8c-11.4-10.1-14-26.8-6.5-39.9L35.7 94.1c7.6-13.2 23.4-19.2 37.8-14.4l67.8 22.5c12.1-9.1 25.3-16.7 39.3-22.8L195.1 9.5zM256.3 336a80 80 0 1 0 -.6-160 80 80 0 1 0 .6 160z" />
                     </svg>
                     <span class="flex-1 ml-1 whitespace-nowrap">Progres Produksi</span>
                  </a>
               </li>

               <li>
                  <a href="{{ route('keprod.pemesanan.index') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                           d="M3 4a1 1 0 0 0-1 1v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V5a1 1 0 0 0-1-1H3Zm2 3h10v2H5V7Zm0 4h10v2H5v-2Z" />
                     </svg>
                     <span class="flex-1 ms-3 whitespace-nowrap">Daftar Pemesanan</span>
                  </a>
               </li>

               {{-- 👉 Pengiriman ke Supplier --}}
               <li>
                  <a href="{{ route('keprod.pengiriman.index') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 640 512"
                        fill="currentColor">
                        <path
                           d="M64 96c0-35.3 28.7-64 64-64l288 0c35.3 0 64 28.7 64 64l0 32 50.7 0c17 0 33.3 6.7 45.3 18.7L621.3 192c12 12 18.7 28.3 18.7 45.3L640 384c0 35.3-28.7 64-64 64l-3.3 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-102.6 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-3.3 0c-35.3 0-64-28.7-64-64l0-48-40 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L24 240c-13.3 0-24-10.7-24-24s10.7-24 24-24l176 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L24 144c-13.3 0-24-10.7-24-24S10.7 96 24 96l40 0zM576 288l0-50.7-45.3-45.3-50.7 0 0 96 96 0zM256 424a40 40 0 1 0 -80 0 40 40 0 1 0 80 0zm232 40a40 40 0 1 0 0-80 40 40 0 1 0 0 80z" />
                     </svg>
                     <span class="flex-1 ml-1 whitespace-nowrap">Pengiriman</span>
                  </a>
               </li>

               {{-- 👉 Pengambilan ke Supplier --}}
               <li>
                  <a href="{{ route('keprod.pengambilan.index') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 512 512"
                        fill="currentColor">
                        <path
                           d="M 198.42214532871972 137.30103806228374 Q 199.30795847750866 141.73010380622839 203.73702422145328 142.6159169550173 Q 205.50865051903114 142.6159169550173 206.39446366782008 141.73010380622839 L 248.02768166089965 118.69896193771626 L 248.02768166089965 118.69896193771626 Q 255.11418685121106 114.26989619377163 262.2006920415225 118.69896193771626 L 303.83391003460207 141.73010380622839 L 303.83391003460207 141.73010380622839 Q 304.719723183391 142.6159169550173 306.49134948096884 142.6159169550173 Q 310.9204152249135 141.73010380622839 311.8062283737024 137.30103806228374 L 311.8062283737024 29.231833910034602 L 311.8062283737024 29.231833910034602 L 382.6712802768166 29.231833910034602 L 382.6712802768166 29.231833910034602 Q 400.38754325259515 30.11764705882353 412.78892733564015 41.63321799307958 Q 424.3044982698962 54.034602076124564 425.1903114186851 71.75086505190312 L 425.1903114186851 213.4809688581315 L 425.1903114186851 213.4809688581315 Q 424.3044982698962 231.19723183391002 412.78892733564015 243.59861591695503 Q 400.38754325259515 255.11418685121106 382.6712802768166 256 L 127.55709342560553 256 L 127.55709342560553 256 Q 109.840830449827 255.11418685121106 97.43944636678201 243.59861591695503 Q 85.92387543252595 231.19723183391002 85.03806228373702 213.4809688581315 L 85.03806228373702 71.75086505190312 L 85.03806228373702 71.75086505190312 Q 85.92387543252595 54.034602076124564 97.43944636678201 41.63321799307958 Q 109.840830449827 30.11764705882353 127.55709342560553 29.231833910034602 L 198.42214532871972 29.231833910034602 L 198.42214532871972 29.231833910034602 L 198.42214532871972 137.30103806228374 L 198.42214532871972 137.30103806228374 Z M 503.1418685121107 326.8650519031142 Q 512 339.2664359861592 510.2283737024221 353.439446366782 L 510.2283737024221 353.439446366782 L 510.2283737024221 353.439446366782 Q 507.57093425605535 367.61245674740485 496.0553633217993 376.47058823529414 L 383.55709342560556 459.7370242214533 L 383.55709342560556 459.7370242214533 Q 351.66782006920414 482.7681660899654 311.8062283737024 482.7681660899654 L 170.07612456747404 482.7681660899654 L 28.346020761245676 482.7681660899654 Q 15.944636678200691 482.7681660899654 7.972318339100346 474.79584775086505 Q 0 466.8235294117647 0 454.42214532871975 L 0 397.7301038062284 L 0 397.7301038062284 Q 0 385.3287197231834 7.972318339100346 377.35640138408303 Q 15.944636678200691 369.3840830449827 28.346020761245676 369.3840830449827 L 61.121107266435985 369.3840830449827 L 61.121107266435985 369.3840830449827 L 100.98269896193771 337.4948096885813 L 100.98269896193771 337.4948096885813 Q 131.98615916955018 312.69204152249137 171.8477508650519 312.69204152249137 L 240.94117647058823 312.69204152249137 L 311.8062283737024 312.69204152249137 Q 324.2076124567474 312.69204152249137 332.1799307958477 320.6643598615917 Q 340.1522491349481 328.636678200692 340.1522491349481 341.038062283737 Q 340.1522491349481 353.439446366782 332.1799307958477 361.4117647058824 Q 324.2076124567474 369.3840830449827 311.8062283737024 369.3840830449827 L 255.11418685121106 369.3840830449827 L 240.94117647058823 369.3840830449827 Q 227.65397923875432 370.2698961937716 226.7681660899654 383.55709342560556 Q 227.65397923875432 396.84429065743944 240.94117647058823 397.7301038062284 L 348.12456747404843 397.7301038062284 L 348.12456747404843 397.7301038062284 L 453.5363321799308 319.7785467128028 L 453.5363321799308 319.7785467128028 Q 465.93771626297575 310.9204152249135 480.11072664359864 312.69204152249137 Q 494.28373702422147 315.34948096885813 503.1418685121107 326.8650519031142 L 503.1418685121107 326.8650519031142 Z M 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 L 170.96193771626298 369.3840830449827 L 170.96193771626298 369.3840830449827 Q 170.96193771626298 369.3840830449827 170.96193771626298 369.3840830449827 Q 170.96193771626298 369.3840830449827 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 Z" />
                     </svg>
                     <span class="flex-1 ml-1 whitespace-nowrap">Pengambilan</span>
                  </a>
               </li>

               <!-- List Pengerjaan Supplier -->
               <li>
                  <a href="{{ route('keprod.listPengerjaanSupplier') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 384 512">
                        <path
                           d="M311.4 32l8.6 0c35.3 0 64 28.7 64 64l0 352c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 96C0 60.7 28.7 32 64 32l8.6 0C83.6 12.9 104.3 0 128 0L256 0c23.7 0 44.4 12.9 55.4 32zM248 112c13.3 0 24-10.7 24-24s-10.7-24-24-24L136 64c-13.3 0-24 10.7-24 24s10.7 24 24 24l112 0zM128 256a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm32 0c0 13.3 10.7 24 24 24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-112 0c-13.3 0-24 10.7-24 24zm0 128c0 13.3 10.7 24 24 24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-112 0c-13.3 0-24 10.7-24 24zM96 416a32 32 0 1 0 0-64 32 32 0 1 0 0 64z" />
                     </svg>
                     <span class="ms-3">List Pengerjaan Supplier</span>
                  </a>
               </li>

               <!-- History Pemindahan Stok -->
               <li>
                  <a href="{{ route('keprod.historyPemindahan') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-6 h-6 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                     </svg>
                     <span class="flex-1 ml-2 whitespace-nowrap">History Pemindahan</span>
                  </a>
               </li>

            @elseif ($role === 'qc')
               <li>
                  <a href="{{ route('qc.dashboard') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 21">
                        <path
                           d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path
                           d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                     </svg>
                     <span class="ms-3">Dashboard</span>
                  </a>
               </li>

               {{-- 👉 Daftar Pemesanan (Show) --}}
               <li>
                  <a href="{{ route('qc.pemesanan.index') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                           d="M3 4a1 1 0 0 0-1 1v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V5a1 1 0 0 0-1-1H3Zm2 3h10v2H5V7Zm0 4h10v2H5v-2Z" />
                     </svg>
                     <span class="flex-1 ms-3 whitespace-nowrap">Daftar Pemesanan</span>
                  </a>
               </li>

               {{-- 👉 Pelacakan Progres Produksi --}}
               <li>
                  <a href="{{ route('qc.progres.index') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 512 512"
                        fill="currentColor">
                        <path
                           d="M195.1 9.5C198.1-5.3 211.2-16 226.4-16l59.8 0c15.2 0 28.3 10.7 31.3 25.5L332 79.5c14.1 6 27.3 13.7 39.3 22.8l67.8-22.5c14.4-4.8 30.2 1.2 37.8 14.4l29.9 51.8c7.6 13.2 4.9 29.8-6.5 39.9L447 233.3c.9 7.4 1.3 15 1.3 22.7s-.5 15.3-1.3 22.7l53.4 47.5c11.4 10.1 14 26.8 6.5 39.9l-29.9 51.8c-7.6 13.1-23.4 19.2-37.8 14.4l-67.8-22.5c-12.1 9.1-25.3 16.7-39.3 22.8l-14.4 69.9c-3.1 14.9-16.2 25.5-31.3 25.5l-59.8 0c-15.2 0-28.3-10.7-31.3-25.5l-14.4-69.9c-14.1-6-27.2-13.7-39.3-22.8L73.5 432.3c-14.4 4.8-30.2-1.2-37.8-14.4L5.8 366.1c-7.6-13.2-4.9-29.8 6.5-39.9l53.4-47.5c-.9-7.4-1.3-15-1.3-22.7s.5-15.3 1.3-22.7L12.3 185.8c-11.4-10.1-14-26.8-6.5-39.9L35.7 94.1c7.6-13.2 23.4-19.2 37.8-14.4l67.8 22.5c12.1-9.1 25.3-16.7 39.3-22.8L195.1 9.5zM256.3 336a80 80 0 1 0 -.6-160 80 80 0 1 0 .6 160z" />
                     </svg>
                     <span class="flex-1 ml-1 whitespace-nowrap">Progres Produksi</span>
                  </a>
               </li>

               {{-- 👉 Pengiriman ke Supplier --}}
               <li>
                  <a href="{{ route('qc.pengiriman.index') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 640 512"
                        fill="currentColor">
                        <path
                           d="M64 96c0-35.3 28.7-64 64-64l288 0c35.3 0 64 28.7 64 64l0 32 50.7 0c17 0 33.3 6.7 45.3 18.7L621.3 192c12 12 18.7 28.3 18.7 45.3L640 384c0 35.3-28.7 64-64 64l-3.3 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-102.6 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-3.3 0c-35.3 0-64-28.7-64-64l0-48-40 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L24 240c-13.3 0-24-10.7-24-24s10.7-24 24-24l176 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L24 144c-13.3 0-24-10.7-24-24S10.7 96 24 96l40 0zM576 288l0-50.7-45.3-45.3-50.7 0 0 96 96 0zM256 424a40 40 0 1 0 -80 0 40 40 0 1 0 80 0zm232 40a40 40 0 1 0 0-80 40 40 0 1 0 0 80z" />
                     </svg>
                     <span class="flex-1 ml-1 whitespace-nowrap">Pengiriman</span>
                  </a>
               </li>

               {{-- 👉 Pengambilan ke Supplier --}}
               <li>
                  <a href="{{ route('qc.pengambilan.index') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 512 512"
                        fill="currentColor">
                        <path
                           d="M 198.42214532871972 137.30103806228374 Q 199.30795847750866 141.73010380622839 203.73702422145328 142.6159169550173 Q 205.50865051903114 142.6159169550173 206.39446366782008 141.73010380622839 L 248.02768166089965 118.69896193771626 L 248.02768166089965 118.69896193771626 Q 255.11418685121106 114.26989619377163 262.2006920415225 118.69896193771626 L 303.83391003460207 141.73010380622839 L 303.83391003460207 141.73010380622839 Q 304.719723183391 142.6159169550173 306.49134948096884 142.6159169550173 Q 310.9204152249135 141.73010380622839 311.8062283737024 137.30103806228374 L 311.8062283737024 29.231833910034602 L 311.8062283737024 29.231833910034602 L 382.6712802768166 29.231833910034602 L 382.6712802768166 29.231833910034602 Q 400.38754325259515 30.11764705882353 412.78892733564015 41.63321799307958 Q 424.3044982698962 54.034602076124564 425.1903114186851 71.75086505190312 L 425.1903114186851 213.4809688581315 L 425.1903114186851 213.4809688581315 Q 424.3044982698962 231.19723183391002 412.78892733564015 243.59861591695503 Q 400.38754325259515 255.11418685121106 382.6712802768166 256 L 127.55709342560553 256 L 127.55709342560553 256 Q 109.840830449827 255.11418685121106 97.43944636678201 243.59861591695503 Q 85.92387543252595 231.19723183391002 85.03806228373702 213.4809688581315 L 85.03806228373702 71.75086505190312 L 85.03806228373702 71.75086505190312 Q 85.92387543252595 54.034602076124564 97.43944636678201 41.63321799307958 Q 109.840830449827 30.11764705882353 127.55709342560553 29.231833910034602 L 198.42214532871972 29.231833910034602 L 198.42214532871972 29.231833910034602 L 198.42214532871972 137.30103806228374 L 198.42214532871972 137.30103806228374 Z M 503.1418685121107 326.8650519031142 Q 512 339.2664359861592 510.2283737024221 353.439446366782 L 510.2283737024221 353.439446366782 L 510.2283737024221 353.439446366782 Q 507.57093425605535 367.61245674740485 496.0553633217993 376.47058823529414 L 383.55709342560556 459.7370242214533 L 383.55709342560556 459.7370242214533 Q 351.66782006920414 482.7681660899654 311.8062283737024 482.7681660899654 L 170.07612456747404 482.7681660899654 L 28.346020761245676 482.7681660899654 Q 15.944636678200691 482.7681660899654 7.972318339100346 474.79584775086505 Q 0 466.8235294117647 0 454.42214532871975 L 0 397.7301038062284 L 0 397.7301038062284 Q 0 385.3287197231834 7.972318339100346 377.35640138408303 Q 15.944636678200691 369.3840830449827 28.346020761245676 369.3840830449827 L 61.121107266435985 369.3840830449827 L 61.121107266435985 369.3840830449827 L 100.98269896193771 337.4948096885813 L 100.98269896193771 337.4948096885813 Q 131.98615916955018 312.69204152249137 171.8477508650519 312.69204152249137 L 240.94117647058823 312.69204152249137 L 311.8062283737024 312.69204152249137 Q 324.2076124567474 312.69204152249137 332.1799307958477 320.6643598615917 Q 340.1522491349481 328.636678200692 340.1522491349481 341.038062283737 Q 340.1522491349481 353.439446366782 332.1799307958477 361.4117647058824 Q 324.2076124567474 369.3840830449827 311.8062283737024 369.3840830449827 L 255.11418685121106 369.3840830449827 L 240.94117647058823 369.3840830449827 Q 227.65397923875432 370.2698961937716 226.7681660899654 383.55709342560556 Q 227.65397923875432 396.84429065743944 240.94117647058823 397.7301038062284 L 348.12456747404843 397.7301038062284 L 348.12456747404843 397.7301038062284 L 453.5363321799308 319.7785467128028 L 453.5363321799308 319.7785467128028 Q 465.93771626297575 310.9204152249135 480.11072664359864 312.69204152249137 Q 494.28373702422147 315.34948096885813 503.1418685121107 326.8650519031142 L 503.1418685121107 326.8650519031142 Z M 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 L 170.96193771626298 369.3840830449827 L 170.96193771626298 369.3840830449827 Q 170.96193771626298 369.3840830449827 170.96193771626298 369.3840830449827 Q 170.96193771626298 369.3840830449827 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 Z" />
                     </svg>
                     <span class="flex-1 ml-1 whitespace-nowrap">Pengambilan</span>
                  </a>
               </li>

               <!-- Produksi Supplier -->
               <li>
                  <a href="{{ route('qc.produksi.index') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 470.744 470.743">
                        <path
                           d="M65.317,188.515l-29.328,44.379l19.202,19.145l43.375-28.765c7.306,4.562,15.271,8.549,23.734,10.997l10.566,52.604 h27.072l10.308-51.331c8.616-1.989,16.792-5.279,24.337-9.438l44.379,29.356l19.145-19.191l-28.765-43.366 c4.562-7.306,7.718-15.271,10.165-23.734l51.771-10.567v-27.071l-50.5-10.309c-1.988-8.616-4.867-16.792-9.017-24.327 l29.568-44.38l-19.088-19.144l-43.317,28.764c-7.306-4.562-15.243-7.812-23.706-10.27L164.671,0H137.6l-10.309,50.595 c-8.616,1.989-16.792,4.915-24.336,9.075l-44.38-29.539L39.431,49.228l28.764,43.318c-4.562,7.315-8.645,15.243-11.093,23.706 L4.404,126.799v27.071l51.427,10.309C57.82,172.794,61.157,180.97,65.317,188.515z M148.769,101.889 c22.539,0,40.812,18.273,40.812,40.812s-18.274,40.813-40.812,40.813c-22.539,0-40.813-18.274-40.813-40.813 S126.23,101.889,148.769,101.889z" />
                        <path d="M263.834,202.361l9.228,51.188c-7.268,5.029-13.722,10.939-19.201,17.585l-52.106-10.996l-10.729,24.853l42.726,29.682
                                          c-1.549,8.482-1.979,17.203-1.128,25.972l-44.667,29.09l9.983,25.158l51.169-9.218c5.029,7.268,10.93,13.731,17.575,19.201
                                          l-11.007,52.106l24.854,10.729l29.682-42.725c8.482,1.549,17.184,1.95,25.962,1.109l29.08,44.647l25.159-9.983l-9.209-51.15
                                          c7.268-5.029,13.731-10.92,19.211-17.566l52.116,11.007l10.729-24.853l-42.725-29.673c1.549-8.481,1.979-17.193,1.138-25.972
                                          l44.666-29.089l-9.983-25.159l-51.169,9.218c-5.029-7.267-10.93-13.731-17.575-19.201l11.006-52.106l-24.853-10.729
                                          l-29.682,42.726c-8.482-1.55-17.203-1.999-25.981-1.157l-29.099-44.686L263.834,202.361z M312.086,293.674
                                          c20.952-8.319,44.677,1.932,52.996,22.883c8.319,20.952-1.932,44.677-22.884,52.996c-20.951,8.319-44.676-1.932-52.995-22.884
                                          C280.894,325.708,291.135,301.983,312.086,293.674z" />
                     </svg>
                     <span class="ms-3">Produksi</span>
                  </a>
               </li>

            @elseif ($role === 'supir')
               <li>
                  <a href="{{ route('supir.dashboard') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 21">
                        <path
                           d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path
                           d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                     </svg>
                     <span class="ms-3">Dashboard</span>
                  </a>
               </li>
               <li>
                  <a href="{{ route('supir.pengiriman.index') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 640 512"
                        fill="currentColor">
                        <path
                           d="M64 96c0-35.3 28.7-64 64-64l288 0c35.3 0 64 28.7 64 64l0 32 50.7 0c17 0 33.3 6.7 45.3 18.7L621.3 192c12 12 18.7 28.3 18.7 45.3L640 384c0 35.3-28.7 64-64 64l-3.3 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-102.6 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-3.3 0c-35.3 0-64-28.7-64-64l0-48-40 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L24 240c-13.3 0-24-10.7-24-24s10.7-24 24-24l176 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L24 144c-13.3 0-24-10.7-24-24S10.7 96 24 96l40 0zM576 288l0-50.7-45.3-45.3-50.7 0 0 96 96 0zM256 424a40 40 0 1 0 -80 0 40 40 0 1 0 80 0zm232 40a40 40 0 1 0 0-80 40 40 0 1 0 0 80z" />
                     </svg>
                     <span class="ms-3">Daftar Pengiriman</span>
                  </a>
               </li>
               <li>
                  <a href="{{ route('supir.pengambilan.index') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 512 512"
                        fill="currentColor">
                        <path
                           d="M 198.42214532871972 137.30103806228374 Q 199.30795847750866 141.73010380622839 203.73702422145328 142.6159169550173 Q 205.50865051903114 142.6159169550173 206.39446366782008 141.73010380622839 L 248.02768166089965 118.69896193771626 L 248.02768166089965 118.69896193771626 Q 255.11418685121106 114.26989619377163 262.2006920415225 118.69896193771626 L 303.83391003460207 141.73010380622839 L 303.83391003460207 141.73010380622839 Q 304.719723183391 142.6159169550173 306.49134948096884 142.6159169550173 Q 310.9204152249135 141.73010380622839 311.8062283737024 137.30103806228374 L 311.8062283737024 29.231833910034602 L 311.8062283737024 29.231833910034602 L 382.6712802768166 29.231833910034602 L 382.6712802768166 29.231833910034602 Q 400.38754325259515 30.11764705882353 412.78892733564015 41.63321799307958 Q 424.3044982698962 54.034602076124564 425.1903114186851 71.75086505190312 L 425.1903114186851 213.4809688581315 L 425.1903114186851 213.4809688581315 Q 424.3044982698962 231.19723183391002 412.78892733564015 243.59861591695503 Q 400.38754325259515 255.11418685121106 382.6712802768166 256 L 127.55709342560553 256 L 127.55709342560553 256 Q 109.840830449827 255.11418685121106 97.43944636678201 243.59861591695503 Q 85.92387543252595 231.19723183391002 85.03806228373702 213.4809688581315 L 85.03806228373702 71.75086505190312 L 85.03806228373702 71.75086505190312 Q 85.92387543252595 54.034602076124564 97.43944636678201 41.63321799307958 Q 109.840830449827 30.11764705882353 127.55709342560553 29.231833910034602 L 198.42214532871972 29.231833910034602 L 198.42214532871972 29.231833910034602 L 198.42214532871972 137.30103806228374 L 198.42214532871972 137.30103806228374 Z M 503.1418685121107 326.8650519031142 Q 512 339.2664359861592 510.2283737024221 353.439446366782 L 510.2283737024221 353.439446366782 L 510.2283737024221 353.439446366782 Q 507.57093425605535 367.61245674740485 496.0553633217993 376.47058823529414 L 383.55709342560556 459.7370242214533 L 383.55709342560556 459.7370242214533 Q 351.66782006920414 482.7681660899654 311.8062283737024 482.7681660899654 L 170.07612456747404 482.7681660899654 L 28.346020761245676 482.7681660899654 Q 15.944636678200691 482.7681660899654 7.972318339100346 474.79584775086505 Q 0 466.8235294117647 0 454.42214532871975 L 0 397.7301038062284 L 0 397.7301038062284 Q 0 385.3287197231834 7.972318339100346 377.35640138408303 Q 15.944636678200691 369.3840830449827 28.346020761245676 369.3840830449827 L 61.121107266435985 369.3840830449827 L 61.121107266435985 369.3840830449827 L 100.98269896193771 337.4948096885813 L 100.98269896193771 337.4948096885813 Q 131.98615916955018 312.69204152249137 171.8477508650519 312.69204152249137 L 240.94117647058823 312.69204152249137 L 311.8062283737024 312.69204152249137 Q 324.2076124567474 312.69204152249137 332.1799307958477 320.6643598615917 Q 340.1522491349481 328.636678200692 340.1522491349481 341.038062283737 Q 340.1522491349481 353.439446366782 332.1799307958477 361.4117647058824 Q 324.2076124567474 369.3840830449827 311.8062283737024 369.3840830449827 L 255.11418685121106 369.3840830449827 L 240.94117647058823 369.3840830449827 Q 227.65397923875432 370.2698961937716 226.7681660899654 383.55709342560556 Q 227.65397923875432 396.84429065743944 240.94117647058823 397.7301038062284 L 348.12456747404843 397.7301038062284 L 348.12456747404843 397.7301038062284 L 453.5363321799308 319.7785467128028 L 453.5363321799308 319.7785467128028 Q 465.93771626297575 310.9204152249135 480.11072664359864 312.69204152249137 Q 494.28373702422147 315.34948096885813 503.1418685121107 326.8650519031142 L 503.1418685121107 326.8650519031142 Z M 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 L 170.96193771626298 369.3840830449827 L 170.96193771626298 369.3840830449827 Q 170.96193771626298 369.3840830449827 170.96193771626298 369.3840830449827 Q 170.96193771626298 369.3840830449827 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 Z" />
                     </svg>
                     <span class="ms-3">Daftar Pengambilan</span>
                  </a>
               </li>

            @elseif ($role === 'gudang')
               <li>
                  <a href="{{ route('gudang.dashboard') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 21">
                        <path
                           d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path
                           d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                     </svg>
                     <span class="ms-3">Dashboard</span>
                  </a>
               </li>

               {{-- 👉 Daftar Pemesanan (Show) --}}
               <li>
                  <a href="{{ route('gudang.pemesanan.index') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                           d="M3 4a1 1 0 0 0-1 1v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V5a1 1 0 0 0-1-1H3Zm2 3h10v2H5V7Zm0 4h10v2H5v-2Z" />
                     </svg>
                     <span class="flex-1 ms-3 whitespace-nowrap">Daftar Pemesanan</span>
                  </a>
               </li>

               {{-- 👉 Daftar Bahan Pendukung--}}
               <li>
                  <a href="{{ route('gudang.daftarbahanpendukung') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 576 512">
                        <path
                           d="M0 142.1L0 480c0 17.7 14.3 32 32 32s32-14.3 32-32l0-240c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32l0 240c0 17.7 14.3 32 32 32s32-14.3 32-32l0-337.9c0-27.5-17.6-52-43.8-60.7L303.2 5.1c-9.9-3.3-20.5-3.3-30.4 0L43.8 81.4C17.6 90.1 0 114.6 0 142.1zM464 256l-352 0 0 64 352 0 0-64zM112 416l352 0 0-64-352 0 0 64zm352 32l-352 0 0 64 352 0 0-64z" />
                     </svg>
                     <span class="ms-3">Stok Bahan Pendukung</span>
                  </a>
               </li>

               {{-- 👉 Daftar Order Bahan Pendukung --}}
               <li>
                  <a href="{{ route('gudang.daftarorderbahanpendukung') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 384 512">
                        <path
                           d="M311.4 32l8.6 0c35.3 0 64 28.7 64 64l0 352c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 96C0 60.7 28.7 32 64 32l8.6 0C83.6 12.9 104.3 0 128 0L256 0c23.7 0 44.4 12.9 55.4 32zM248 112c13.3 0 24-10.7 24-24s-10.7-24-24-24L136 64c-13.3 0-24 10.7-24 24s10.7 24 24 24l112 0zM128 256a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm32 0c0 13.3 10.7 24 24 24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-112 0c-13.3 0-24 10.7-24 24zm0 128c0 13.3 10.7 24 24 24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-112 0c-13.3 0-24 10.7-24 24zM96 416a32 32 0 1 0 0-64 32 32 0 1 0 0 64z" />
                     </svg>
                     <span class="ms-3">Daftar Order BP</span>
                  </a>
               </li>

               {{-- 👉 Pengiriman ke Supplier --}}
               <li>
                  <a href="{{ route('gudang.pengiriman.index') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 640 512"
                        fill="currentColor">
                        <path
                           d="M64 96c0-35.3 28.7-64 64-64l288 0c35.3 0 64 28.7 64 64l0 32 50.7 0c17 0 33.3 6.7 45.3 18.7L621.3 192c12 12 18.7 28.3 18.7 45.3L640 384c0 35.3-28.7 64-64 64l-3.3 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-102.6 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-3.3 0c-35.3 0-64-28.7-64-64l0-48-40 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L24 240c-13.3 0-24-10.7-24-24s10.7-24 24-24l176 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L24 144c-13.3 0-24-10.7-24-24S10.7 96 24 96l40 0zM576 288l0-50.7-45.3-45.3-50.7 0 0 96 96 0zM256 424a40 40 0 1 0 -80 0 40 40 0 1 0 80 0zm232 40a40 40 0 1 0 0-80 40 40 0 1 0 0 80z" />
                     </svg>
                     <span class="flex-1 ml-1 whitespace-nowrap">Pengiriman</span>
                  </a>
               </li>

               {{-- 👉 Daftar Packing --}}
               <li>
                  <a href="{{ route('gudang.packing.index') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 122.88 114.58">
                        <path
                           d="M118.13,9.54a3.25,3.25,0,0,1,2.2.41,3.28,3.28,0,0,1,2,3l.57,78.83a3.29,3.29,0,0,1-1.59,3L89.12,113.93a3.29,3.29,0,0,1-2,.65,3.07,3.07,0,0,1-.53,0L3.11,105.25A3.28,3.28,0,0,1,0,102V21.78H0A3.28,3.28,0,0,1,2,18.7L43.89.27h0A3.19,3.19,0,0,1,45.63,0l72.5,9.51Zm-37.26,1.7-24.67,14,30.38,3.88,22.5-14.18-28.21-3.7Zm-29,20L50.75,64.62,38.23,56.09,25.72,63.17l2.53-34.91L6.55,25.49V99.05l77.33,8.6V35.36l-32-4.09Zm-19.7-9.09L56.12,8,45.7,6.62,15.24,20l16.95,2.17ZM90.44,34.41v71.12l25.9-15.44-.52-71.68-25.38,16Z" />
                     </svg>
                     <span class="ms-3">Daftar Packing</span>
                  </a>
               </li>

            @elseif ($role === 'purchasing')
               <li>
                  <a href="{{ route('purchasing.dashboard') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 21">
                        <path
                           d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path
                           d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                     </svg>
                     <span class="ms-3">Dashboard</span>
                  </a>
               </li>

               {{-- 👉 Daftar Bahan Pendukung--}}
               <li>
                  <a href="{{ route('purchasing.daftarbahanpendukung') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 576 512">
                        <path
                           d="M0 142.1L0 480c0 17.7 14.3 32 32 32s32-14.3 32-32l0-240c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32l0 240c0 17.7 14.3 32 32 32s32-14.3 32-32l0-337.9c0-27.5-17.6-52-43.8-60.7L303.2 5.1c-9.9-3.3-20.5-3.3-30.4 0L43.8 81.4C17.6 90.1 0 114.6 0 142.1zM464 256l-352 0 0 64 352 0 0-64zM112 416l352 0 0-64-352 0 0 64zm352 32l-352 0 0 64 352 0 0-64z" />
                     </svg>
                     <span class="ms-3">Stok Bahan Pendukung</span>
                  </a>
               </li>

               {{-- 👉 Daftar Order Bahan Pendukung --}}
               @php
                  $jumlahOrderMenunggu = $jumlahOrderMenunggu ??
                     \App\Models\PembelianBahanPendukung::where('status_order', 'Menunggu')->count();
               @endphp

               <li class="relative">
                  <a href="{{ route('purchasing.daftarorderbahanpendukung') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900 relative">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 512 512">
                        <path
                           d="M311.4 32l8.6 0c35.3 0 64 28.7 64 64l0 352c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 96C0 60.7 28.7 32 64 32l8.6 0C83.6 12.9 104.3 0 128 0L256 0c23.7 0 44.4 12.9 55.4 32zM248 112c13.3 0 24-10.7 24-24s-10.7-24-24-24L136 64c-13.3 0-24 10.7-24 24s10.7 24 24 24l112 0zM128 256a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm32 0c0 13.3 10.7 24 24 24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-112 0c-13.3 0-24 10.7-24 24zm0 128c0 13.3 10.7 24 24 24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-112 0c-13.3 0-24 10.7-24 24zM96 416a32 32 0 1 0 0-64 32 32 0 1 0 0 64z" />
                     </svg>
                     <span class="ms-3">Daftar Order BP</span>

                     {{-- 🔴 Badge Notifikasi --}}
                     @if($jumlahOrderMenunggu > 0)
                        <span
                           class="absolute -top-1.5 left-6 bg-red-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center shadow-md animate-bounce">
                           {{ $jumlahOrderMenunggu }}
                        </span>
                     @endif
                  </a>
               </li>

               {{-- 👉 Daftar Pembayaran Supplier --}}
               <li>
                  <a href="{{ route('purchasing.pembayaran.index') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 483.2 483.2">
                        <path
                           d="M143.15,172.5h16.7V34c0-7.5,6.1-13.6,13.6-13.6h78.6v152.1h16.6h15h20.9V20.4h5.1c7.5,0,13.6,6.1,13.6,13.6v138.5h16.7 c3.8,0,6.8-3.1,6.8-6.8v-128c0-20.7-16.9-37.7-37.7-37.7h-135c-20.7,0-37.7,16.9-37.7,37.7v127.9 C136.35,169.4,139.45,172.5,143.15,172.5z" />
                        <path
                           d="M367.05,130v7.5V174c0,10.1-8.2,18.3-18.3,18.3h-214.2c-10.1,0-18.3-8.2-18.3-18.3v-36.4v-7.5 c-19.5,3.3-34.4,20.2-34.4,40.7V442c0,22.8,18.5,41.2,41.2,41.2h237.1c22.8,0,41.2-18.5,41.2-41.2V170.7	C401.45,150.2,386.55,133.3,367.05,130z M169.65,442.3c-10.4,0-18.8-8.4-18.8-18.8s8.4-18.8,18.8-18.8 c10.4,0,18.8,8.4,18.8,18.8C188.35,433.9,179.95,442.3,169.65,442.3z M169.65,385.5c-10.4,0-18.8-8.4-18.8-18.8	s8.4-18.8,18.8-18.8c10.4,0,18.8,8.4,18.8,18.8C188.35,377.1,179.95,385.5,169.65,385.5z M169.65,328.8 c-10.4,0-18.8-8.4-18.8-18.8s8.4-18.8,18.8-18.8c10.4,0,18.8,8.4,18.8,18.8C188.35,320.4,179.95,328.8,169.65,328.8z M241.65,442.3c-10.4,0-18.8-8.4-18.8-18.8s8.4-18.8,18.8-18.8c10.4,0,18.8,8.4,18.8,18.8	C260.45,433.9,252.05,442.3,241.65,442.3z M241.65,385.5c-10.4,0-18.8-8.4-18.8-18.8s8.4-18.8,18.8-18.8 c10.4,0,18.8,8.4,18.8,18.8S252.05,385.5,241.65,385.5z M241.65,328.8c-10.4,0-18.8-8.4-18.8-18.8s8.4-18.8,18.8-18.8	c10.4,0,18.8,8.4,18.8,18.8C260.45,320.4,252.05,328.8,241.65,328.8z M313.65,442.3c-10.4,0-18.8-8.4-18.8-18.8	s8.4-18.8,18.8-18.8s18.8,8.4,18.8,18.8C332.45,433.9,324.05,442.3,313.65,442.3z M313.65,385.5c-10.4,0-18.8-8.4-18.8-18.8	s8.4-18.8,18.8-18.8s18.8,8.4,18.8,18.8S324.05,385.5,313.65,385.5z M313.65,328.8c-10.4,0-18.8-8.4-18.8-18.8 s8.4-18.8,18.8-18.8s18.8,8.4,18.8,18.8C332.45,320.4,324.05,328.8,313.65,328.8z M334.55,262.1c0,5.8-4.7,10.4-10.4,10.4	h-164.9c-5.8,0-10.4-4.7-10.4-10.4v-28.3c0-5.8,4.7-10.4,10.4-10.4h164.9c5.8,0,10.4,4.7,10.4,10.4V262.1z" />
                     </svg>
                     <span class="ms-3">Daftar Pembayaran</span>
                  </a>
               </li>

            @elseif ($role === 'packing')
               <li>
                  <a href="{{ route('packing.dashboard') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 21">
                        <path
                           d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path
                           d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                     </svg>
                     <span class="ms-3">Dashboard</span>
                  </a>
               </li>

               <!-- Packing -->
               <li>
                  <a href="{{ route('packing.packing.index') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 505 511.9">
                        <path
                           d="m336.11 39.84-115.38 68.95 135.38 18.39 111.32-69.44-131.32-17.9zm26.7 205.06c73.73 0 133.5 59.77 133.5 133.5 0 73.73-59.77 133.5-133.5 133.5-73.73 0-133.49-59.77-133.49-133.5 0-73.73 59.76-133.5 133.49-133.5zm-11.93 68.58c-.05 2.21.32 3.27 2.24 3.27h29.61c8.38.67 15.89 2.89 22.23 7.17 4.39 2.99 7.62 8.55 10.04 12.47 4.29 6.97 6.73 17.05 8.05 25.17.56 3.51.82 6.81.21 8.32-.27.72-.72 1.21-1.27 1.43-3.18 1.27-6.56-3.1-8.35-5.2-8.53-9.98-20.94-14.11-35.56-14.89h-25.64c-1.52.26-2.06 1.38-1.9 3.11v11.91c-.09 3.97-2.09 5.32-6.05 3.98l-36.43-28.59-3.59-2.82-.92-.72c-2.92-2.63-1.72-7.02.91-9.09l2.78-2.18 34.92-27.49c4.25-3.35 8.72-4.26 8.72 2.97v11.18zm23.87 129.85c.04-2.22-.32-3.29-2.24-3.29h-29.62c-8.38-.66-15.89-2.88-22.22-7.17-4.41-2.99-7.66-8.58-10.08-12.53-4.38-7.16-7.27-18.84-8.32-27.21-.32-2.66-.38-5.01.08-6.21.28-.71.74-1.21 1.3-1.43 3.17-1.27 6.54 3.11 8.33 5.2 8.54 9.98 20.95 14.11 35.57 14.89h25.64c1.51-.26 2.05-1.38 1.9-3.11v-11.91c.09-3.96 2.08-5.31 6.05-3.98l36.43 28.59 3.58 2.82.91.72c2.94 2.63 1.73 7.02-.9 9.09l-2.78 2.18-34.91 27.49c-4.26 3.35-8.72 4.26-8.72-2.97v-11.17zm-169.92-317.2-.09 141.71-51.45-35.04-51.46 29.07 6.1-148.91-88.54-12.03v312.98l178.95 23.14c2.52 7.09 5.47 13.98 8.85 20.62L9.3 432.08c-5.17-.21-9.3-4.48-9.3-9.69V89.86c.27-4.05 1.89-6.89 5.72-8.81L182.47.85c1.58-.72 3.53-1.01 5.26-.76l308.18 42.03c5.09.59 8.58 4.77 8.58 9.99v.02L505 280.9c-5.72-8.46-15.57-20.29-19.93-27.77V69.56l-115.81 74.93v59.81a174.577 174.577 0 0 0-19.39.36v-58.82l-145.04-19.71zm-81.52-30.58 112.17-69.43-47.58-6.49L44.24 84.8l79.07 10.75z" />

                     </svg>
                     <span class="ms-3">Daftar Packing</span>
                  </a>
               </li>

            @elseif ($role === 'direktur')
               <li>
                  <a href="{{ route('direktur.dashboard') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 21">
                        <path
                           d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path
                           d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                     </svg>
                     <span class="ms-3">Dashboard</span>
                  </a>
               </li>

            @elseif ($role === 'supplier')
               <li>
                  <a href="{{ route('supplier.dashboard') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 21">
                        <path
                           d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path
                           d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                     </svg>
                     <span class="ms-3">Dashboard</span>
                  </a>
               </li>

               <!-- Barang Masuk -->
               <li>
                  <a href="{{ route('supplier.pengiriman.index') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path
                           d="M12.013 6.175 7.006 9.369l5.007 3.194-5.007 3.193L2 12.545l5.006-3.193L2 6.175l5.006-3.194 5.007 3.194ZM6.981 17.806l5.006-3.193 5.006 3.193L11.987 21l-5.006-3.194Z" />
                        <path
                           d="m12.013 12.545 5.006-3.194-5.006-3.176 4.98-3.194L22 6.175l-5.007 3.194L22 12.562l-5.007 3.194-4.98-3.211Z" />
                     </svg>
                     <span class="ms-3">Barang Masuk</span>
                  </a>
               </li>

               <!-- Produksi -->
               <li>
                  <a href="{{ route('supplier.produksi.index') }}"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 470.744 470.743">
                        <path d="M65.317,188.515l-29.328,44.379l19.202,19.145l43.375-28.765c7.306,4.562,15.271,8.549,23.734,10.997l10.566,52.604 h27.072l10.308-51.331c8.616-1.989,16.792-5.279,24.337-9.438l44.379,29.356l19.145-19.191l-28.765-43.366
                                          c4.562-7.306,7.718-15.271,10.165-23.734l51.771-10.567v-27.071l-50.5-10.309c-1.988-8.616-4.867-16.792-9.017-24.327
                                          l29.568-44.38l-19.088-19.144l-43.317,28.764c-7.306-4.562-15.243-7.812-23.706-10.27L164.671,0H137.6l-10.309,50.595
                                          c-8.616,1.989-16.792,4.915-24.336,9.075l-44.38-29.539L39.431,49.228l28.764,43.318c-4.562,7.315-8.645,15.243-11.093,23.706
                                          L4.404,126.799v27.071l51.427,10.309C57.82,172.794,61.157,180.97,65.317,188.515z M148.769,101.889 c22.539,0,40.812,18.273,40.812,40.812s-18.274,40.813-40.812,40.813c-22.539,0-40.813-18.274-40.813-40.813
                                          S126.23,101.889,148.769,101.889z" />
                        <path d="M263.834,202.361l9.228,51.188c-7.268,5.029-13.722,10.939-19.201,17.585l-52.106-10.996l-10.729,24.853l42.726,29.682 c-1.549,8.482-1.979,17.203-1.128,25.972l-44.667,29.09l9.983,25.158l51.169-9.218c5.029,7.268,10.93,13.731,17.575,19.201
                                          l-11.007,52.106l24.854,10.729l29.682-42.725c8.482,1.549,17.184,1.95,25.962,1.109l29.08,44.647l25.159-9.983l-9.209-51.15
                                          c7.268-5.029,13.731-10.92,19.211-17.566l52.116,11.007l10.729-24.853l-42.725-29.673c1.549-8.481,1.979-17.193,1.138-25.972
                                          l44.666-29.089l-9.983-25.159l-51.169,9.218c-5.029-7.267-10.93-13.731-17.575-19.201l11.006-52.106l-24.853-10.729
                                          l-29.682,42.726c-8.482-1.55-17.203-1.999-25.981-1.157l-29.099-44.686L263.834,202.361z M312.086,293.674 c20.952-8.319,44.677,1.932,52.996,22.883c8.319,20.952-1.932,44.677-22.884,52.996c-20.951,8.319-44.676-1.932-52.995-22.884
                                          C280.894,325.708,291.135,301.983,312.086,293.674z" />
                     </svg>
                     <span class="ms-3">Produksi</span>
                  </a>
               </li>

            @endif

            <li>
               <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <!-- <button type="submit" class="flex items-center p-2 text-white hover:text-black rounded-lg hover:bg-white hover:text-black  w-full text-left"> -->
                  <button type="submit"
                     class="flex items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900 w-full text-left">
                     <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 21">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3" />
                     </svg>
                     <span class="flex-1 ms-3 whitespace-nowrap">Logout</span>
                  </button>
               </form>
            </li>
         </ul>
      </div>
   </aside>

   <div class="sm:ml-64 pt-20">

      @yield('content')

   </div>


   <script>
      if ('serviceWorker' in navigator) {
         navigator.serviceWorker.register('/sw.js')
         .then(() => console.log('Service Worker registered'))
         .catch(err => console.error('SW failed', err));
      }
      
      document.addEventListener('DOMContentLoaded', function () {
         ['panjang', 'lebar', 'tinggi'].forEach(function (name) {
            document.querySelectorAll('input[type="number"][name="' + name + '"]').forEach(function (input) {
               if (!input.hasAttribute('step') || input.getAttribute('step') === '1') {
                  input.setAttribute('step', '0.01');
               }
               if (!input.hasAttribute('min')) {
                  input.setAttribute('min', '0');
               }
               input.setAttribute('inputmode', 'decimal');
            });
         });
      });
   </script>

   <script>
      document.addEventListener('DOMContentLoaded', function () {
         const sidebar = document.getElementById('logo-sidebar');
         if (!sidebar) return;

         const normalizePath = (path) => {
            if (!path) return '/';
            const cleaned = path.replace(/\/+$/, '');
            return cleaned === '' ? '/' : cleaned;
         };

         const currentPath = normalizePath(window.location.pathname);
         sidebar.querySelectorAll('a[href]').forEach((link) => {
            const url = new URL(link.getAttribute('href'), window.location.origin);
            const linkPath = normalizePath(url.pathname);

            if (linkPath === '/') return;

            const isActive = currentPath === linkPath || currentPath.startsWith(linkPath + '/');
            if (isActive) {
               link.classList.remove('text-white');
               link.classList.add('bg-white', 'text-black', 'font-semibold', 'shadow-sm');
               link.setAttribute('aria-current', 'page');
            }
         });
      });
   </script>

   <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>

</html>