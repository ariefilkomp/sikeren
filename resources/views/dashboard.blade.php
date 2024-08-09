<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="max-w-3xl mx-auto text-center py-8">
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
                            <svg aria-hidden="true" class="w-5 h-5 ml-2" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>

                    @role('admin')
                        <div class="mt-4">
                            <a href="{{ route('aktivitas.create') }}" title=""
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Tambah Aktivitas &nbsp;
                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24"
                                    viewBox="0 0 48 48">
                                    <path fill="#4caf50"
                                        d="M44,24c0,11.045-8.955,20-20,20S4,35.045,4,24S12.955,4,24,4S44,12.955,44,24z">
                                    </path>
                                    <path fill="#fff" d="M21,14h6v20h-6V14z"></path>
                                    <path fill="#fff" d="M14,21h20v6H14V21z"></path>
                                </svg>
                            </a>
                        </div>
                    @endrole

                    @if (session('success'))
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                            class="text-sm text-gray-600 bg-green-400 p-4 rounded-xl my-8">{{ session('success') }}</p>
                    @endif
                </div>

                <div class="flow-root max-w-3xl mx-auto mt-4 sm:mt-12 lg:mt-6" x-data="{ lightbox: false, imgModalSrc: '', imgModalAlt: '', imgModalDesc: '' }">
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
                            <div id="id-{{ $act->id }}"
                                class="flex flex-col gap-2 py-4 sm:gap-6 sm:flex-row sm:items-start"
                                x-data="{ reportsOpen: false }">
                                <p
                                    class="w-32 text-lg font-normal text-gray-500 sm:text-right dark:text-gray-400 shrink-0">
                                    {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $act->waktu_mulai)->format('H:i') }}
                                    -
                                    {{ $act->waktu_selesai ? Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $act->waktu_selesai)->format('H:i') : 'Selesai' }}
                                </p>
                                <div class="flex flex-col gap-2 w-full">
                                    <div class="flex flex-row justify-between gap-2 w-full"
                                        @click="reportsOpen = !reportsOpen">
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

                                    <div class="flex p-5 md:p-0 w-full transform transition duration-300 ease-in-out pb-10"
                                        x-cloak x-show="reportsOpen" x-collapse x-collapse.duration.500ms>
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td class="text-small text-slate-800 dark:text-white">
                                                        Tempat
                                                    </td>
                                                    <td>:</td>
                                                    <td class="text-small font-semibold text-slate-800 dark:text-white">
                                                        {{ $act->tempat }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-small text-slate-800 dark:text-white">
                                                        Penyelenggara
                                                    </td>
                                                    <td>:</td>
                                                    <td class="text-small font-semibold text-slate-800 dark:text-white">
                                                        {{ $act->penyelenggara }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-small text-slate-800 dark:text-white align-top">
                                                        Peserta
                                                    </td>
                                                    <td class="w-2 align-top">:</td>
                                                    <td
                                                        class="text-small font-semibold text-slate-800 dark:text-white align-top">
                                                        <div class="flex flex-col">
                                                            @if ($act->peserta)
                                                                @foreach ($act->peserta as $p)
                                                                    <p>{{ $p->user->name }}</p>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-small text-slate-800 dark:text-white">
                                                        Disposisi
                                                    </td>
                                                    <td>:</td>
                                                    <td class="text-small font-semibold text-slate-800 dark:text-white">
                                                        {{ $act->disposisi }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-small text-slate-800 dark:text-white">
                                                        Catatan
                                                    </td>
                                                    <td>:</td>
                                                    <td class="text-small font-semibold text-slate-800 dark:text-white">
                                                        {{ $act->catatan ?? '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-small text-slate-800 dark:text-white">
                                                        Surat
                                                    </td>
                                                    <td>:</td>
                                                    <td class="text-small font-semibold text-slate-800 dark:text-white">
                                                        @if ($act->file != null)
                                                            @php
                                                                $ext = pathinfo($act->file, PATHINFO_EXTENSION);
                                                            @endphp
                                                            @if ($ext == 'pdf')
                                                                <a href="{{ url('/storage/files/' . $act->file) }}"
                                                                    target="_blank">
                                                                    <img src="{{ url('assets/images/pdf.png') }}"
                                                                        alt="" width="24px"
                                                                        class="cursor-pointer" />
                                                                </a>
                                                            @else
                                                                <img src="{{ url('assets/images/mail.png') }}"
                                                                    alt="" width="24px" class="cursor-pointer"
                                                                    @click="$dispatch('lightbox',  {  imgModalSrc: '{{ url('/storage/files/' . $act->file) }}' })" />
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                                @role('admin')
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="flex flex-row gap-4">
                                                            <form action="{{ route('aktivitas.edit', $act->id) }}" method="GET">
                                                                <x-primary-button type="submit" class="mt-2">
                                                                    <svg class="feather feather-edit" fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                                    &nbsp; Edit</x-secondary-button>
                                                            </form>
                                                            
                                                                <form action="{{ route('aktivitas.delete') }}"
                                                                    method="POST" onsubmit="return confirm('Yakin, mau hapus aktivitas ini?');">
                                                                    @csrf
                                                                    <input type="hidden" name="id"
                                                                        value="{{ $act->id }}">
                                                                    <x-danger-button type="submit" class="mt-2">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" x="0px"
                                                                            y="0px" width="16" height="16"
                                                                            viewBox="0 0 24 24" fill="#fff">
                                                                            <path
                                                                                d="M 10 2 L 9 3 L 4 3 L 4 5 L 20 5 L 20 3 L 15 3 L 14 2 L 10 2 z M 5 7 L 5 22 L 19 22 L 19 7 L 5 7 z M 8 9 L 10 9 L 10 20 L 8 20 L 8 9 z M 14 9 L 16 9 L 16 20 L 14 20 L 14 9 z">
                                                                            </path>
                                                                        </svg>&nbsp; Hapus</x-danger-button>
                                                                </form>
                                                        </td>
                                                    </tr>
                                                @endrole

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script></script>
</x-app-layout>
