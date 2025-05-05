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
                                @foreach($bidangs as $bidang)
                                    <optgroup label="{{ $bidang->name }}">
                                        @foreach($users->where('bidang_id', $bidang->id) as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="file" :value="__('File Surat')" />
                            <input type="file" name="file" accept="pdf,jpg,jpeg,png"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm mt-1 block w-full">
                            <x-input-error class="mt-2" :messages="$errors->get('file')" />
                        </div>

                        <div>
                            <x-input-label for="min_jam" :value="__('Pengingat')" />
                            <div class="flex gap-2 items-center">
                                <p>-</p>
                                <x-text-input id="min_jam" name="min_jam" type="number" class="w-20"
                                    :value="old('min_jam',2)" required autofocus autocomplete="min_jam" />
                                <x-input-error class="mt-2" :messages="$errors->get('min_jam')" />
                                <p>Jam</p>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="recurrence_type" :value="__('Ingatkan Berulang Setiap ')" />

                            <div class="flex gap-2 items-center">
                                <x-text-input id="recurrence_interval" name="recurrence_interval" type="number" class="w-20"
                                    :value="old('recurrence_interval', 1)" required autofocus autocomplete="recurrence_interval" />
                                <x-input-error class="mt-2" :messages="$errors->get('recurrence_interval')" />
                                <select
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm mt-1 block w-full"
                                    name="recurrence_type" id="recurrence_type">
                                    <option value="none">-- Pengingat Tidak Berulang --</option>
                                    <option value="daily">Hari</option>
                                    <option value="weekly">Pekan</option>
                                    <option value="monthly">Bulan</option>
                                    <option value="yearly">Tahun</option>
                                </select>
                            </div>

                        </div>

                        <div>
                            <label class="cursor-pointer">
                                <x-input-label for="notif_on_publish" :value="__('Kirim Pemberitahuan Saat Dipublish')" />
                                <input type="checkbox" id="notif_on_publish" value="1"
                                    name="notif_on_publish" class="sr-only peer" checked>
                                <div
                                    class="mt-2 relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                </div>
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('notif_on_publish')" />
                        </div>

                        <div>
                            <label class="cursor-pointer">
                                <x-input-label for="published" :value="__('Published')" />
                                <input type="checkbox" id="published" value="1"
                                    name="published" class="sr-only peer" checked>
                                <div
                                    class="mt-2 relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                </div>
                            </label>
                            <x-input-error class="mt-2" :messages="$errors->get('published')" />
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
