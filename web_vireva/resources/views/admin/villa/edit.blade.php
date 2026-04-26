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
                            <option value="1-Bedroom Villa" {{ $villa->tipe_villa == '1-Bedroom Villa' ? 'selected' : '' }}>Villa 1 Kamar</option>
                            <option value="2-Bedroom Villa" {{ $villa->tipe_villa == '2-Bedroom Villa' ? 'selected' : '' }}>Villa 2 Kamar</option>
                            <option value="3-Bedroom Villa" {{ $villa->tipe_villa == '3-Bedroom Villa' ? 'selected' : '' }}>Villa 3 Kamar</option>
                            <option value="Family Suite Villa" {{ $villa->tipe_villa == 'Family Suite Villa' ? 'selected' : '' }}>Villa Suite Keluarga</option>
                            <option value="Presidential Villa" {{ $villa->tipe_villa == 'Presidential Villa' ? 'selected' : '' }}>Villa Kepresidenan</option>
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
                        <option value="maintenance" {{ $villa->status_villa == 'maintenance' ? 'selected' : '' }}>Perbaikan</option>
                    </select>
                    <x-input-error :messages="$errors->get('status_villa')" class="mt-2" />
                </div>

                <div class="mt-8">
                    <x-input-label for="foto" value="Perbarui Foto Villa" class="text-slate-700 font-bold" />
                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($villa->foto)
                            <div id="current-photo">
                                <div class="flex items-center gap-2 mb-3">
                                    <i data-lucide="image" class="w-4 h-4 text-slate-400"></i>
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Foto Saat Ini</span>
                                </div>
                                <img src="{{ Storage::url($villa->foto) }}" class="w-full max-h-48 object-cover rounded-[2rem] border border-slate-200 shadow-sm">
                            </div>
                        @endif

                        <div class="{{ $villa->foto ? '' : 'md:col-span-2' }}">
                            <div class="flex items-center gap-2 mb-3">
                                <i data-lucide="upload-cloud" class="w-4 h-4 text-emerald-600"></i>
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Upload Foto Baru</span>
                            </div>
                            <label for="foto" class="flex flex-col items-center justify-center w-full h-48 border-2 border-slate-200 border-dashed rounded-[2rem] cursor-pointer bg-slate-50 hover:bg-emerald-50/50 hover:border-emerald-200 transition-all group">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-400 group-hover:text-emerald-600 shadow-sm mb-3 transition-colors">
                                        <i data-lucide="image-plus" class="w-5 h-5"></i>
                                    </div>
                                    <p class="text-xs text-slate-600 font-bold">Pilih file baru</p>
                                </div>
                                <input id="foto" type="file" name="foto" class="hidden" onchange="previewImage(this)" />
                            </label>
                        </div>
                    </div>

                    <div id="preview-container" class="mt-6 hidden">
                        <div class="flex items-center gap-2 mb-3">
                            <i data-lucide="eye" class="w-4 h-4 text-emerald-600"></i>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Pratinjau Foto Baru</span>
                        </div>
                        <img id="preview-img" src="#" class="w-full max-h-80 object-cover rounded-[2rem] border border-slate-200 shadow-sm" />
                    </div>
                    <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                </div>

                <script>
                    function previewImage(input) {
                        const container = document.getElementById('preview-container');
                        const img = document.getElementById('preview-img');
                        
                        if (input.files && input.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                img.src = e.target.result;
                                container.classList.remove('hidden');
                                // Optionally hide current photo
                                const currentPhoto = document.getElementById('current-photo');
                                if (currentPhoto) currentPhoto.style.opacity = '0.5';
                            }
                            reader.readAsDataURL(input.files[0]);
                        }
                    }
                </script>

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
