<x-common-layout>

    <div class="max-w-3xl mx-auto text-center">
        <h2 class="text-4xl font-extrabold leading-tight tracking-tight text-slate-800 dark:text-white">
            Aktivitas {{ $labelHari }}
        </h2>
        <h3 class="text-xl font-extrabold leading-tight tracking-tight text-slate-800 dark:text-white">
            {{ $today }}
        </h3>

        <div class="mt-2">
            <a href="{{ route('aktivitas.perBulan') }}" title=""
                class="inline-flex items-center text-lg font-medium text-blue-600 hover:underline dark:text-blue-500">
                Lihat Tanggal Lain
                <svg aria-hidden="true" class="w-5 h-5 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    </div>

    <div class="flow-root max-w-3xl mx-auto mt-8 sm:mt-12 lg:mt-16" x-data="{ lightbox: false, imgModalSrc: '', imgModalAlt: '', imgModalDesc: '' }">
        <div x-show="lightbox"
            @lightbox.window="lightbox = true; imgModalSrc = $event.detail.imgModalSrc; imgModalDesc = $event.detail.imgModalDesc;">
            <div x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90"
                class="fixed inset-0 z-50 flex items-center justify-center w-full p-2 overflow-hidden bg-black bg-opacity-75 h-100">
                <div @click.away="lightbox = ''" class="">
                    <img class="h-screen object-contain" :src="imgModalSrc" :alt="imgModalAlt">
                </div>
            </div>
        </div>
        <div class="-my-4 divide-y divide-gray-200 dark:divide-gray-700">

            @foreach ($aktivitas as $act)
                <div class="flex flex-col gap-2 py-4 sm:gap-6 sm:flex-row sm:items-start" x-data="{ reportsOpen: false }">
                    <p class="w-32 text-lg font-normal text-gray-500 sm:text-right dark:text-gray-400 shrink-0">
                        {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $act->waktu_mulai)->format('H:i') }} -
                        {{ $act->waktu_selesai ? Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $act->waktu_selesai)->format('H:i') : 'Selesai' }}
                    </p>
                    <div class="flex flex-col gap-2 w-full">
                        <div class="flex flex-row justify-between gap-2 w-full" @click="reportsOpen = !reportsOpen">
                            <h3
                                class="w-full text-lg font-semibold text-slate-800 dark:text-white hover:underline hover:cursor-pointer">
                                {{ $act->aktivitas }}
                            </h3>
                            <div class='w-10 px-2 transform transition duration-300 ease-in-out hover:cursor-pointer'
                                :class="{ 'rotate-180': reportsOpen, ' -translate-y-0.0': !reportsOpen }">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                        </div>

                        <div class="flex flex-col p-5 md:p-0 w-full transform transition duration-300 ease-in-out pb-10" x-cloak
                            x-show="reportsOpen" x-collapse x-collapse.duration.500ms>

                            <div class="flex flex-col gap-2 sm:gap-6 sm:flex-row sm:items-start">
                                <p class="w-32 sm:w-60 text-lg font-normal text-gray-500 sm:text-left dark:text-gray-400">
                                    Tempat
                                </p>
                                <p class="w-full text-small font-semibold text-slate-800 dark:text-white">
                                   <span class="hidden sm:inline">:&nbsp;</span> {{ $act->tempat }}
                                </p>
                            </div>

                            <div class="flex flex-col gap-2 sm:gap-6 sm:flex-row sm:items-start">
                                <p class="w-32 sm:w-60 text-lg font-normal text-gray-500 sm:text-left dark:text-gray-400">
                                    OPD Penyelenggara
                                </p>
                                <p class="w-full text-small font-semibold text-slate-800 dark:text-white">
                                   <span class="hidden sm:inline">:&nbsp;</span> {{ $act->penyelenggara }}
                                </p>
                            </div>

                            <div class="flex flex-col gap-2 sm:gap-6 sm:flex-row sm:items-start">
                                <p
                                    class="w-32 sm:w-60 text-lg font-normal text-gray-500 sm:text-left dark:text-gray-400">
                                    Disposisi
                                </p>
                                <p class="w-full text-small font-semibold text-slate-800 dark:text-white">
                                    <span class="hidden sm:inline">:&nbsp;</span>
                                    @if($act->disposisi)
                                        @foreach ($act->disposisi as $dis)
                                            {{ $dis->user->name }}; &nbsp;
                                        @endforeach
                                    @endif
                                </p>
                            </div>

                            <div class="flex flex-col gap-2 sm:gap-6 sm:flex-row sm:items-start">
                                <p class="w-32 sm:w-60 text-lg font-normal text-gray-500 sm:text-left dark:text-gray-400">
                                    Catatan / NB
                                </p>
                                <p class="w-full text-small font-semibold text-slate-800 dark:text-white">
                                   <span class="hidden sm:inline">:&nbsp;</span>
                                   {{ $act->catatan ?? '-' }}
                                </p>
                            </div>

                            <div class="flex flex-col gap-2 sm:gap-6 sm:flex-row sm:items-start">
                                <p class="w-32 sm:w-60 text-lg font-normal text-gray-500 sm:text-left dark:text-gray-400">
                                    Surat
                                </p>
                                <p class="flex w-full text-small font-semibold text-slate-800 dark:text-white">
                                   <span class="hidden sm:inline">:&nbsp;</span>
                                   @if ($act->file != null)
                                        @php
                                            $ext = pathinfo($act->file, PATHINFO_EXTENSION);
                                        @endphp
                                        @if ($ext == 'pdf')
                                        <a href="{{ url('/storage/files/' . $act->file) }}" target="_blank">
                                            <img src="{{ url('assets/images/pdf.png') }}" alt=""
                                                width="24px" class="cursor-pointer"/>
                                        </a>
                                        @else
                                            <img src="{{ url('assets/images/mail.png') }}" alt=""
                                                width="24px" class="cursor-pointer"
                                                @click="$dispatch('lightbox',  {  imgModalSrc: '{{ url('/storage/files/' . $act->file) }}' })" />
                                        @endif
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

</x-common-layout>