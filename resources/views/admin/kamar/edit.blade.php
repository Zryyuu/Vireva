<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Kamar ') . $kamar->nomor_kamar }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-gray-900">
                <form action="{{ route('admin.kamar.update', $kamar->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="nomor_kamar" value="Nomor Kamar" />
                            <x-text-input id="nomor_kamar" class="block mt-1 w-full" type="text" name="nomor_kamar" :value="old('nomor_kamar', $kamar->nomor_kamar)" required autofocus />
                            <x-input-error :messages="$errors->get('nomor_kamar')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tipe_kamar" value="Tipe Kamar" />
                            <select id="tipe_kamar" name="tipe_kamar" class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm block mt-1 w-full">
                                <option value="Standard" {{ $kamar->tipe_kamar == 'Standard' ? 'selected' : '' }}>Standard</option>
                                <option value="Deluxe" {{ $kamar->tipe_kamar == 'Deluxe' ? 'selected' : '' }}>Deluxe</option>
                                <option value="Suite" {{ $kamar->tipe_kamar == 'Suite' ? 'selected' : '' }}>Suite</option>
                                <option value="Penthouse" {{ $kamar->tipe_kamar == 'Penthouse' ? 'selected' : '' }}>Penthouse</option>
                            </select>
                            <x-input-error :messages="$errors->get('tipe_kamar')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="harga_permalam" value="Harga Per Malam" />
                            <x-text-input id="harga_permalam" class="block mt-1 w-full" type="number" name="harga_permalam" :value="old('harga_permalam', $kamar->harga_permalam)" required />
                            <x-input-error :messages="$errors->get('harga_permalam')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="kapasitas" value="Kapasitas Tamu" />
                            <x-text-input id="kapasitas" class="block mt-1 w-full" type="number" name="kapasitas" :value="old('kapasitas', $kamar->kapasitas)" required />
                            <x-input-error :messages="$errors->get('kapasitas')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-6">
                        <x-input-label for="status_kamar" value="Status Kamar" />
                        <select id="status_kamar" name="status_kamar" class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm block mt-1 w-full">
                            <option value="tersedia" {{ $kamar->status_kamar == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="terisi" {{ $kamar->status_kamar == 'terisi' ? 'selected' : '' }}>Terisi</option>
                            <option value="maintenance" {{ $kamar->status_kamar == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        <x-input-error :messages="$errors->get('status_kamar')" class="mt-2" />
                    </div>

                    <div class="mt-6">
                        <x-input-label for="foto" value="Foto Kamar" />
                        @if($kamar->foto)
                            <img src="{{ Storage::url($kamar->foto) }}" class="w-32 h-20 mb-2 rounded-lg object-cover">
                        @endif
                        <input id="foto" type="file" name="foto" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-primary hover:file:bg-blue-100" />
                        <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                    </div>

                    <div class="mt-6">
                        <x-input-label for="deskripsi" value="Deskripsi Kamar" />
                        <textarea id="deskripsi" name="deskripsi" rows="4" class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm block mt-1 w-full">{{ old('deskripsi', $kamar->deskripsi) }}</textarea>
                        <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('admin.kamar.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Batal</a>
                        <x-primary-button>Update Kamar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
