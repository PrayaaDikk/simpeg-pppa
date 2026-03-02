@extends('layouts.app')

@section('content')
    <x-ui.header>Selamat Datang di SIMPEG-PPPA Kota Kendari</x-ui.header>


    <section>
        <div class="flex gap-6 items-center bg-white p-6 rounded-lg max-sm:flex-col">
            <div>
                @if ($pegawai->foto)
                    <img src="{{ route('pegawai.showFile', $pegawai->foto) }}" alt="{{ $pegawai->nama }}"
                        class="h-32 w-32 object-cover rounded-lg border-2 border-gray-300">
                @else
                    <div class="size-32 rounded-lg border-2 border-gray-300"></div>
                @endif
            </div>
            <div class="space-y-2">
                <div class="flex items-center gap-4">
                    <h2 class="font-bold">Nama Lengkap :</h2>
                    <p>{{ $pegawai->nama }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <h2 class="font-bold">NIP :</h2>
                    <p>{{ $pegawai->nip }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <h2 class="font-bold">Jabatan</h2>
                    <p>{{ $pegawai->jabatan->nama_jabatan }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <h2 class="font-bold">Bidang</h2>
                    <p>{{ $pegawai->bidang->nama_bidang }}</p>
                </div>
            </div>

        </div>

        <div class="flex items-center justify-end gap-4 max-xs:justify-start mb-8 mt-6">
            <a href="{{ route('cuti.create', $pegawai) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90 focus:ring-4 focus:ring-primary-medium transition-colors">
                <div>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="size-5 text-white"
                        xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path
                                d="M10 21H6.2C5.0799 21 4.51984 21 4.09202 20.782C3.71569 20.5903 3.40973 20.2843 3.21799 19.908C3 19.4802 3 18.9201 3 17.8V8.2C3 7.0799 3 6.51984 3.21799 6.09202C3.40973 5.71569 3.71569 5.40973 4.09202 5.21799C4.51984 5 5.0799 5 6.2 5H17.8C18.9201 5 19.4802 5 19.908 5.21799C20.2843 5.40973 20.5903 5.71569 20.782 6.09202C21 6.51984 21 7.0799 21 8.2V10M7 3V5M17 3V5M3 9H21M13.5 13.0001L7 13M10 17.0001L7 17M14 21L16.025 20.595C16.2015 20.5597 16.2898 20.542 16.3721 20.5097C16.4452 20.4811 16.5147 20.4439 16.579 20.399C16.6516 20.3484 16.7152 20.2848 16.8426 20.1574L21 16C21.5523 15.4477 21.5523 14.5523 21 14C20.4477 13.4477 19.5523 13.4477 19 14L14.8426 18.1574C14.7152 18.2848 14.6516 18.3484 14.601 18.421C14.5561 18.4853 14.5189 18.5548 14.4903 18.6279C14.458 18.7102 14.4403 18.7985 14.405 18.975L14 21Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                        </g>
                    </svg>
                </div>
                <span>
                    Ajukan Cuti
                </span>
            </a>
            <a href="{{ route('cuti.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90 focus:ring-4 focus:ring-primary-medium transition-colors">
                <div>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="size-5 text-white"
                        xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path
                                d="M3 9H21M7 3V5M17 3V5M6 12H8M11 12H13M16 12H18M6 15H8M11 15H13M16 15H18M6 18H8M11 18H13M16 18H18M6.2 21H17.8C18.9201 21 19.4802 21 19.908 20.782C20.2843 20.5903 20.5903 20.2843 20.782 19.908C21 19.4802 21 18.9201 21 17.8V8.2C21 7.07989 21 6.51984 20.782 6.09202C20.5903 5.71569 20.2843 5.40973 19.908 5.21799C19.4802 5 18.9201 5 17.8 5H6.2C5.0799 5 4.51984 5 4.09202 5.21799C3.71569 5.40973 3.40973 5.71569 3.21799 6.09202C3 6.51984 3 7.07989 3 8.2V17.8C3 18.9201 3 19.4802 3.21799 19.908C3.40973 20.2843 3.71569 20.5903 4.09202 20.782C4.51984 21 5.07989 21 6.2 21Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                        </g>
                    </svg>
                </div>
                <span>
                    Riwayat Cuti
                </span>
            </a>
            <a href="{{ route('kgb.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90 focus:ring-4 focus:ring-primary-medium transition-colors">
                <div>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="size-5 text-white"
                        xmlns="http://www.w3.org/2000/svg">
                        <<g id="SVGRepo_bgCarrier" stroke-width="0">
                            </g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M11 8L16 8.00053M11 12L16 12.0005M11 16L16 16.0005M8 16H8.01M8 12H8.01M8 8H8.01M7.2 4H16.8C17.9201 4 18.4802 4 18.908 4.21799C19.2843 4.40973 19.5903 4.71569 19.782 5.09202C20 5.51984 20 6.0799 20 7.2V16.8C20 17.9201 20 18.4802 19.782 18.908C19.5903 19.2843 19.2843 19.5903 18.908 19.782C18.4802 20 17.9201 20 16.8 20H7.2C6.0799 20 5.51984 20 5.09202 19.782C4.71569 19.5903 4.40973 19.2843 4.21799 18.908C4 18.4802 4 17.9201 4 16.8V7.2C4 6.0799 4 5.51984 4.21799 5.09202C4.40973 4.71569 4.71569 4.40973 5.09202 4.21799C5.51984 4 6.0799 4 7.2 4Z"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                            </g>
                    </svg>
                </div>
                <span>
                    Riwayat KGB
                </span>
            </a>
        </div>
    </section>
@endsection
