@extends('layouts.allApp')

@section('title', 'Pelacakan Progres Produksi')
@section('role', 'Kepala Produksi')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 w-full">
        <div class="mb-6">
            <h2 class="text-3xl font-bold">Pelacakan Progres Produksi</h2>
            <div class="mt-3">
                <a href="{{ route('keprod.produksi.create') }}"
                    class="bg-green-600 rounded-lg border text-white px-3 py-3 inline-flex items-center gap-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 470.744 470.743"
                        fill="currentColor" aria-hidden="true">
                        <path
                            d="M65.317,188.515l-29.328,44.379l19.202,19.145l43.375-28.765c7.306,4.562,15.271,8.549,23.734,10.997l10.566,52.604 h27.072l10.308-51.331c8.616-1.989,16.792-5.279,24.337-9.438l44.379,29.356l19.145-19.191l-28.765-43.366 c4.562-7.306,7.718-15.271,10.165-23.734l51.771-10.567v-27.071l-50.5-10.309c-1.988-8.616-4.867-16.792-9.017-24.327 l29.568-44.38l-19.088-19.144l-43.317,28.764c-7.306-4.562-15.243-7.812-23.706-10.27L164.671,0H137.6l-10.309,50.595 c-8.616,1.989-16.792,4.915-24.336,9.075l-44.38-29.539L39.431,49.228l28.764,43.318c-4.562,7.315-8.645,15.243-11.093,23.706 L4.404,126.799v27.071l51.427,10.309C57.82,172.794,61.157,180.97,65.317,188.515z M148.769,101.889 c22.539,0,40.812,18.273,40.812,40.812s-18.274,40.813-40.812,40.813c-22.539,0-40.813-18.274-40.813-40.813 S126.23,101.889,148.769,101.889z" />
                        <path
                            d="M263.834,202.361l9.228,51.188c-7.268,5.029-13.722,10.939-19.201,17.585l-52.106-10.996l-10.729,24.853l42.726,29.682 c-1.549,8.482-1.979,17.203-1.128,25.972l-44.667,29.09l9.983,25.158l51.169-9.218c5.029,7.268,10.93,13.731,17.575,19.201 l-11.007,52.106l24.854,10.729l29.682-42.725c8.482,1.549,17.184,1.95,25.962,1.109l29.08,44.647l25.159-9.983l-9.209-51.15 c7.268-5.029,13.731-10.92,19.211-17.566l52.116,11.007l10.729-24.853l-42.725-29.673c1.549-8.481,1.979-17.193,1.138-25.972 l44.666-29.089l-9.983-25.159l-51.169,9.218c-5.029-7.267-10.93-13.731-17.575-19.201l11.006-52.106l-24.853-10.729 l-29.682,42.726c-8.482-1.55-17.203-1.999-25.981-1.157l-29.099-44.686L263.834,202.361z M312.086,293.674 c20.952-8.319,44.677,1.932,52.996,22.883c8.319,20.952-1.932,44.677-22.884,52.996c-20.951,8.319-44.676-1.932-52.995-22.884 C280.894,325.708,291.135,301.983,312.086,293.674z" />
                    </svg>
                    Tambah Produksi
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($matrix as $index => $row)
                <div
                    class="bg-white rounded-xl shadow-xl overflow-hidden border border-gray-100 hover:shadow-2xl transition duration-300">
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-bold text-green-600">Progres #{{ $index + 1 }}</h3>
                            <span class="inline-block bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">
                                {{ $row['status_produksi'] }}
                            </span>
                        </div>

                        <h2 class="text-2xl font-extrabold text-gray-800 mb-1">{{ $row['nama_barang'] }}</h2>
                        <p class="text-lg text-gray-600 mb-1">Jumlah: <span
                                class="font-bold">{{ $row['jumlah_produksi'] }}</span></p>
                        <p class="text-lg text-gray-600 mb-4">Jenis Barang: <span
                                class="font-bold">{{ $row['jenis_barang'] }}</span></p>

                        <div class="grid grid-cols-2 gap-2 mb-4 text-sm">
                            @foreach ($subProsesList as $sp)
                                <div class="flex justify-between bg-gray-50 px-3 py-1 rounded-lg">
                                    <span class="text-gray-600">{{ $sp->nama_sub_proses }}</span>
                                    <span class="font-bold text-gray-800">
                                        {{ $row['subproses'][$sp->sub_proses_id] ?? 0 }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        <hr class="my-4 border-gray-100">
                    </div>
                </div>
            @empty
                {{-- Empty state (tidak ada progres) di sini --}}
            @endforelse
        </div>
    </div>
@endsection