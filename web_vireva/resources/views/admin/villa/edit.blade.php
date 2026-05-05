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
                        <x-text-input id="harga_permalam" class="block mt-2 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="number" name="harga_permalam" :value="old('harga_permalam', $villa->harga_permalam)" min="1000" required />
                        <x-input-error :messages="$errors->get('harga_permalam')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="kapasitas" value="Kapasitas Tamu" class="text-slate-700 font-bold" />
                        <x-text-input id="kapasitas" class="block mt-2 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="number" name="kapasitas" :value="old('kapasitas', $villa->kapasitas)" min="1" max="50" required />
                        <x-input-error :messages="$errors->get('kapasitas')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="jumlah_bedroom" value="Jumlah Kamar Tidur" class="text-slate-700 font-bold" />
                        <x-text-input id="jumlah_bedroom" class="block mt-2 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="number" name="jumlah_bedroom" :value="old('jumlah_bedroom', $villa->jumlah_bedroom)" min="1" max="20" required />
                        <x-input-error :messages="$errors->get('jumlah_bedroom')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="jumlah_bathroom" value="Jumlah Kamar Mandi" class="text-slate-700 font-bold" />
                        <x-text-input id="jumlah_bathroom" class="block mt-2 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="number" name="jumlah_bathroom" :value="old('jumlah_bathroom', $villa->jumlah_bathroom)" min="1" max="20" required />
                        <x-input-error :messages="$errors->get('jumlah_bathroom')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="luas_bangunan" value="Luas Bangunan (m²)" class="text-slate-700 font-bold" />
                        <x-text-input id="luas_bangunan" class="block mt-2 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="number" name="luas_bangunan" :value="old('luas_bangunan', $villa->luas_bangunan)" min="1" max="5000" />
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
                    <div class="flex items-center justify-between mb-4">
                        <x-input-label value="Manajemen Galeri Villa" class="text-slate-700 font-bold" />
                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg uppercase tracking-wider">Multi-Image Mode</span>
                    </div>

                    <!-- Existing Gallery -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 mb-2">
                            <i data-lucide="image" class="w-4 h-4 text-slate-400"></i>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Foto Saat Ini (Klik X untuk Hapus)</span>
                        </div>
                        
                        <div id="existing-gallery-grid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            @if($villa->foto && is_array($villa->foto))
                                @foreach($villa->foto as $index => $path)
                                    <div class="relative group aspect-video rounded-2xl overflow-hidden border border-slate-200 shadow-sm transition-all hover:shadow-md" id="photo-{{ $index }}">
                                        <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-cover">
                                        <input type="hidden" name="old_foto[]" value="{{ $path }}">
                                        <button type="button" onclick="removeExistingImage('photo-{{ $index }}')" class="absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600 shadow-lg">
                                            <i data-lucide="x" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @elseif($villa->foto)
                                <div class="relative group aspect-video rounded-2xl overflow-hidden border border-slate-200 shadow-sm" id="photo-0">
                                    <img src="{{ asset('storage/' . $villa->foto) }}" class="w-full h-full object-cover">
                                    <input type="hidden" name="old_foto[]" value="{{ $villa->foto }}">
                                    <button type="button" onclick="removeExistingImage('photo-0')" class="absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600 shadow-lg">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Upload New Photos -->
                    <div class="mt-8">
                        <div class="flex items-center gap-2 mb-3">
                            <i data-lucide="upload-cloud" class="w-4 h-4 text-emerald-600"></i>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Tambah Foto Baru ke Galeri</span>
                        </div>
                        <label for="foto" class="flex flex-col items-center justify-center w-full h-40 border-2 border-slate-200 border-dashed rounded-[2rem] cursor-pointer bg-slate-50 hover:bg-emerald-50/50 hover:border-emerald-200 transition-all group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-400 group-hover:text-emerald-600 shadow-sm mb-3 transition-colors">
                                    <i data-lucide="images" class="w-5 h-5"></i>
                                </div>
                                <p class="text-xs text-slate-600 font-bold text-center px-4">Seret atau pilih banyak foto baru untuk ditambahkan</p>
                            </div>
                            <input id="foto" type="file" name="foto[]" class="hidden" multiple onchange="previewNewImages(this)" />
                        </label>
                    </div>

                    <!-- New Photos Preview -->
                    <div id="new-preview-wrapper" class="mt-6 hidden">
                        <div class="flex items-center gap-2 mb-4">
                            <i data-lucide="eye" class="w-4 h-4 text-emerald-600"></i>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Pratinjau Foto Baru</span>
                        </div>
                        <div id="new-preview-grid" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <!-- New previews injected here -->
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                </div>

                <script>
                    function removeExistingImage(id) {
                        if (confirm('Hapus foto ini dari galeri? (Perubahan akan disimpan setelah Anda mengklik Update)')) {
                            const element = document.getElementById(id);
                            element.style.transform = 'scale(0.8)';
                            element.style.opacity = '0';
                            setTimeout(() => element.remove(), 300);
                        }
                    }

                    function previewNewImages(input) {
                        const wrapper = document.getElementById('new-preview-wrapper');
                        const grid = document.getElementById('new-preview-grid');
                        grid.innerHTML = '';
                        
                        if (input.files && input.files.length > 0) {
                            wrapper.classList.remove('hidden');
                            
                            Array.from(input.files).forEach(file => {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    const div = document.createElement('div');
                                    div.className = 'relative group aspect-video overflow-hidden rounded-2xl border border-slate-200 shadow-sm';
                                    div.innerHTML = `
                                        <img src="${e.target.result}" class="w-full h-full object-cover transition-transform group-hover:scale-110" />
                                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold">
                                            BARU
                                        </div>
                                    `;
                                    grid.appendChild(div);
                                }
                                reader.readAsDataURL(file);
                            });
                        } else {
                            wrapper.classList.add('hidden');
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
