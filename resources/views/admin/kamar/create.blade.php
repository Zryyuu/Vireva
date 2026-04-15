<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Kamar Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.kamar.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="nomor_kamar" value="Nomor Kamar" />
                            <x-text-input id="nomor_kamar" class="block mt-1 w-full" type="text" name="nomor_kamar" :value="old('nomor_kamar')" required autofocus />
                            <x-input-error :messages="$errors->get('nomor_kamar')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tipe_kamar" value="Tipe Kamar" />
                            <select id="tipe_kamar" name="tipe_kamar" class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm block mt-1 w-full">
                                <option value="Standard">Standard</option>
                                <option value="Deluxe">Deluxe</option>
                                <option value="Suite">Suite</option>
                                <option value="Penthouse">Penthouse</option>
                            </select>
                            <x-input-error :messages="$errors->get('tipe_kamar')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="harga_permalam" value="Harga Per Malam" />
                            <x-text-input id="harga_permalam" class="block mt-1 w-full" type="number" name="harga_permalam" :value="old('harga_permalam')" required />
                            <x-input-error :messages="$errors->get('harga_permalam')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="kapasitas" value="Kapasitas Tamu" />
                            <x-text-input id="kapasitas" class="block mt-1 w-full" type="number" name="kapasitas" :value="old('kapasitas', 2)" required />
                            <x-input-error :messages="$errors->get('kapasitas')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-6">
                        <x-input-label for="foto" value="Foto Kamar" />
                        <input id="foto" type="file" name="foto" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-primary hover:file:bg-blue-100" />
                        <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                    </div>

                    <div class="mt-6">
                        <x-input-label for="deskripsi" value="Deskripsi Kamar" />
                        <textarea id="deskripsi" name="deskripsi" rows="4" class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm block mt-1 w-full">{{ old('deskripsi') }}</textarea>
                        <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('admin.kamar.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Batal</a>
                        <x-primary-button>Simpan Kamar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
