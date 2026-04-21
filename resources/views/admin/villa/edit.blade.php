<x-admin-layout>
    <div class="space-y-6 sm:space-y-8 animate__animated animate__fadeIn">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 mb-1">Edit Unit Villa</h1>
                <p class="text-sm text-slate-500 font-medium">Perbarui informasi, harga, dan ketersediaan unit {{ $villa->nama_villa }}.</p>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm border border-slate-200 sm:rounded-3xl">
            <form action="{{ route('admin.villa.update', $villa->id) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <x-input-label for="nama_villa" value="Nama Villa" class="text-slate-700 font-bold" />
                        <x-text-input id="nama_villa" class="block mt-2 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="text" name="nama_villa" :value="old('nama_villa', $villa->nama_villa)" required autofocus />
                        <x-input-error :messages="$errors->get('nama_villa')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tipe_villa" value="Tipe Villa" class="text-slate-700 font-bold" />
                        <select id="tipe_villa" name="tipe_villa" class="border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm block mt-2 w-full">
                            <option value="1-Bedroom Villa" {{ $villa->tipe_villa == '1-Bedroom Villa' ? 'selected' : '' }}>1-Bedroom Villa</option>
                            <option value="2-Bedroom Villa" {{ $villa->tipe_villa == '2-Bedroom Villa' ? 'selected' : '' }}>2-Bedroom Villa</option>
                            <option value="3-Bedroom Villa" {{ $villa->tipe_villa == '3-Bedroom Villa' ? 'selected' : '' }}>3-Bedroom Villa</option>
                            <option value="Family Suite Villa" {{ $villa->tipe_villa == 'Family Suite Villa' ? 'selected' : '' }}>Family Suite Villa</option>
                            <option value="Presidential Villa" {{ $villa->tipe_villa == 'Presidential Villa' ? 'selected' : '' }}>Presidential Villa</option>
                        </select>
                        <x-input-error :messages="$errors->get('tipe_villa')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="harga_permalam" value="Harga Per Malam (Rp)" class="text-slate-700 font-bold" />
                        <x-text-input id="harga_permalam" class="block mt-2 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="number" name="harga_permalam" :value="old('harga_permalam', $villa->harga_permalam)" required />
                        <x-input-error :messages="$errors->get('harga_permalam')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="kapasitas" value="Kapasitas Tamu" class="text-slate-700 font-bold" />
                        <x-text-input id="kapasitas" class="block mt-2 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="number" name="kapasitas" :value="old('kapasitas', $villa->kapasitas)" required />
                        <x-input-error :messages="$errors->get('kapasitas')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="jumlah_bedroom" value="Jumlah Kamar Tidur" class="text-slate-700 font-bold" />
                        <x-text-input id="jumlah_bedroom" class="block mt-2 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="number" name="jumlah_bedroom" :value="old('jumlah_bedroom', $villa->jumlah_bedroom)" required />
                        <x-input-error :messages="$errors->get('jumlah_bedroom')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="jumlah_bathroom" value="Jumlah Kamar Mandi" class="text-slate-700 font-bold" />
                        <x-text-input id="jumlah_bathroom" class="block mt-2 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="number" name="jumlah_bathroom" :value="old('jumlah_bathroom', $villa->jumlah_bathroom)" required />
                        <x-input-error :messages="$errors->get('jumlah_bathroom')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="luas_bangunan" value="Luas Bangunan (m²)" class="text-slate-700 font-bold" />
                        <x-text-input id="luas_bangunan" class="block mt-2 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="number" name="luas_bangunan" :value="old('luas_bangunan', $villa->luas_bangunan)" />
                        <x-input-error :messages="$errors->get('luas_bangunan')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-8">
                    <x-input-label for="status_villa" value="Status Villa" class="text-slate-700 font-bold" />
                    <select id="status_villa" name="status_villa" class="border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm block mt-2 w-full">
                        <option value="tersedia" {{ $villa->status_villa == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="terisi" {{ $villa->status_villa == 'terisi' ? 'selected' : '' }}>Terisi</option>
                        <option value="maintenance" {{ $villa->status_villa == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    <x-input-error :messages="$errors->get('status_villa')" class="mt-2" />
                </div>

                <div class="mt-8">
                    <x-input-label for="foto" value="Foto Villa" class="text-slate-700 font-bold" />
                    <div class="mt-3 bg-slate-50 p-4 border border-slate-200 border-dashed rounded-2xl">
                        @if($villa->foto)
                            <div class="mb-4">
                                <span class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-2 block">Foto Saat Ini</span>
                                <img src="{{ Storage::url($villa->foto) }}" class="w-48 h-32 rounded-xl object-cover shadow-sm border border-slate-200">
                            </div>
                        @endif
                        <input id="foto" type="file" name="foto" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-colors" />
                    </div>
                    <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                </div>

                <div class="mt-8">
                    <x-input-label for="deskripsi" value="Deskripsi Eksklusif" class="text-slate-700 font-bold" />
                    <textarea id="deskripsi" name="deskripsi" rows="5" class="border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm block mt-2 w-full">{{ old('deskripsi', $villa->deskripsi) }}</textarea>
                    <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                </div>

                <div class="mt-10 flex justify-end gap-4 border-t border-slate-100 pt-6">
                    <a href="{{ route('admin.villa.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-colors">Batal</a>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-colors shadow-md shadow-emerald-500/20">
                        Update Unit Villa
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
