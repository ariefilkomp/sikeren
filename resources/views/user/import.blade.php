<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Import') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-600 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="max-w-3xl mx-auto text-center py-8">
                    <h2 class="text-4xl font-extrabold leading-tight tracking-tight text-slate-800 dark:text-white">
                        Import User
                    </h2>

                    @if (session('success'))
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                            class="text-sm text-gray-600 bg-green-400 p-4 rounded-xl my-8">{{ session('success') }}</p>
                    @endif
                </div>

                <div class="flow-root max-w-3xl mx-auto mt-4 sm:mt-12 lg:mt-6">
                    
                    <div class="-my-4 divide-y divide-gray-200 dark:divide-gray-700 px-4 pb-12 ">
                        <form method="POST" action="{{ route('import.user') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mt-4">
                                <x-input-label for="file" :value="__('File Surat')" />
                                <input type="file" name="file" accept="pdf,jpg,jpeg,png"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm mt-1 block w-full">
                                <x-input-error class="mt-2" :messages="$errors->get('file')" />
                            </div>
    
                            <div class="flex items-center gap-4 mt-4">
                                <x-primary-button>{{ __('Import') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
