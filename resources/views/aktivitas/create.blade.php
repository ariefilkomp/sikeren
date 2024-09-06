<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Aktivitas / Kegiatan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="max-w-xl mx-auto py-8 px-4">

                    <form method="post" action="{{ route('aktivitas.create') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <x-input-label for="aktifitas" :value="__('Rincian Kegiatan')" />
                            <x-text-input id="aktivitas" name="aktivitas" type="text" class="mt-1 block w-full"
                                :value="old('aktivitas')" required autofocus autocomplete="aktivitas" />
                            <x-input-error class="mt-2" :messages="$errors->get('aktivitas')" />
                        </div>

                        <div>
                            <x-input-label for="penyelenggara" :value="__('OPD Penyelenggara')" />
                            <x-text-input id="penyelenggara" name="penyelenggara" type="text"
                                class="mt-1 block w-full" :value="old('penyelenggara')" autofocus
                                autocomplete="penyelenggara"
                                placeholder="Contoh: Diskominfo" />
                            <x-input-error class="mt-2" :messages="$errors->get('penyelenggara')" />
                        </div>

                        <div x-data x-init="flatpickr($refs.datetimewidget, { wrap: true, enableTime: true, dateFormat: 'Y-m-d H:i', time_24hr: true });" x-ref="datetimewidget"
                            class="flatpickr container mx-auto col-span-6 sm:col-span-6 mt-5">
                            <label for="datetime" class="flex-grow  block font-medium text-sm text-gray-700 mb-1">Waktu
                                Mulai</label>
                            <div class="flex align-middle align-content-center">
                                <input name="waktu_mulai" value="{{ old('waktu_mulai') }}" x-ref="datetime"
                                    type="text" id="datetime" data-input placeholder="Select.."
                                    class="block w-full px-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-l-md shadow-sm">

                                <a class="h-11 w-10 input-button cursor-pointer rounded-r-md bg-transparent border-gray-300 border-t border-b border-r"
                                    title="clear" data-clear>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mt-2 ml-1"
                                        viewBox="0 0 20 20" fill="#c53030">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('waktu_mulai')" />

                        </div>

                        <div x-data x-init="flatpickr($refs.datetimewidget, { wrap: true, enableTime: true, dateFormat: 'Y-m-d H:i', time_24hr: true });" x-ref="datetimewidget"
                            class="flatpickr container mx-auto col-span-6 sm:col-span-6 mt-5">
                            <label for="datetime"
                                class="flex-grow  font-medium text-sm text-gray-700 mb-1 flex flex-row">Waktu Selesai <p
                                    class="text-red-500">*</p></label>
                            <div class="flex align-middle align-content-center">
                                <input name="waktu_selesai" value="{{ old('waktu_selesai') }}" x-ref="datetime"
                                    type="text" id="datetime" data-input placeholder="Select.."
                                    class="block w-full px-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-l-md shadow-sm">

                                <a class="h-11 w-10 input-button cursor-pointer rounded-r-md bg-transparent border-gray-300 border-t border-b border-r"
                                    title="clear" data-clear>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mt-2 ml-1"
                                        viewBox="0 0 20 20" fill="#c53030">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('waktu_selesai')" />

                        </div>

                        <div>
                            <x-input-label for="tempat" :value="__('Tempat')" />
                            <x-text-input id="tempat" name="tempat" type="text" class="mt-1 block w-full"
                                :value="old('tempat')" required autofocus autocomplete="tempat" />
                            <x-input-error class="mt-2" :messages="$errors->get('tempat')" />
                        </div>

                        <div>
                            <x-input-label for="catatan" :value="__('Catatan')" />
                            <textarea id="catatan" name="catatan" type="text" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                :value="old('catatan')" autofocus autocomplete="catatan" ></textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('catatan')" />
                        </div>

                        <div>
                            <x-input-label for="disposisi" :value="__('Disposisi')" />
                            <select
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm mt-1 block w-full"
                                name="disposisi[]" id="disposisi" multiple="multiple">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="file" :value="__('File Surat')" />
                            <input type="file" name="file" accept="pdf,jpg,jpeg,png"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm mt-1 block w-full">
                            <x-input-error class="mt-2" :messages="$errors->get('file')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Simpan') }}</x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#disposisi').select2();
        });
    </script>

</x-app-layout>
