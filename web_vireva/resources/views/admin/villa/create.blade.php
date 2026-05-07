<x-admin-layout>
    <div class="space-y-6 sm:space-y-8 animate__animated animate__fadeIn">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 mb-1">Tambah Unit Villa</h1>
                <p class="text-sm text-slate-500 font-medium">Buat listing villa baru ke dalam koleksi Vireva.</p>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm border border-slate-200 sm:rounded-3xl">
            <form action="{{ route('admin.villa.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <x-input-label for="nama_villa" value="Nama Villa" class="text-slate-700 font-bold" />
                        <x-text-input id="nama_villa" class="block mt-2 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="text" name="nama_villa" :value="old('nama_villa')" placeholder="Contoh: Villa Amethyst" required autofocus />
                        <x-input-error :messages="$errors->get('nama_villa')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tipe_villa" value="Tipe Villa" class="text-slate-700 font-bold" />
                        <select id="tipe_villa" name="tipe_villa" class="border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm block mt-2 w-full">
                            <option value="1-Bedroom Villa">Villa 1 Kamar</option>
                            <option value="2-Bedroom Villa">Villa 2 Kamar</option>
                            <option value="3-Bedroom Villa">Villa 3 Kamar</option>
                            <option value="Family Suite Villa">Villa Suite Keluarga</option>
                            <option value="Presidential Villa">Villa Kepresidenan</option>
                        </select>
                        <x-input-error :messages="$errors->get('tipe_villa')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="harga_permalam" value="Harga Per Malam (Rp)" class="text-slate-700 font-bold" />
                        <x-text-input id="harga_permalam" class="block mt-2 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="number" name="harga_permalam" :value="old('harga_permalam')" min="1000" required />
                        <x-input-error :messages="$errors->get('harga_permalam')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="kapasitas" value="Kapasitas Tamu Maksimal" class="text-slate-700 font-bold" />
                        <x-text-input id="kapasitas" class="block mt-2 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="number" name="kapasitas" :value="old('kapasitas', 2)" min="1" max="50" required />
                        <x-input-error :messages="$errors->get('kapasitas')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="jumlah_bedroom" value="Jumlah Kamar Tidur" class="text-slate-700 font-bold" />
                        <x-text-input id="jumlah_bedroom" class="block mt-2 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="number" name="jumlah_bedroom" :value="old('jumlah_bedroom', 1)" min="1" max="20" required />
                        <x-input-error :messages="$errors->get('jumlah_bedroom')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="jumlah_bathroom" value="Jumlah Kamar Mandi" class="text-slate-700 font-bold" />
                        <x-text-input id="jumlah_bathroom" class="block mt-2 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="number" name="jumlah_bathroom" :value="old('jumlah_bathroom', 1)" min="1" max="20" required />
                        <x-input-error :messages="$errors->get('jumlah_bathroom')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="luas_bangunan" value="Luas Bangunan (m²)" class="text-slate-700 font-bold" />
                        <x-text-input id="luas_bangunan" class="block mt-2 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="number" name="luas_bangunan" :value="old('luas_bangunan')" min="1" max="5000" placeholder="Opsional" />
                        <x-input-error :messages="$errors->get('luas_bangunan')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-8">
                    <x-input-label for="foto" value="Galeri Foto Villa" class="text-slate-700 font-bold" />
                    <div class="mt-2">
                        <label for="foto" class="flex flex-col items-center justify-center w-full h-48 border-2 border-slate-200 border-dashed rounded-[2rem] cursor-pointer bg-slate-50 hover:bg-emerald-50/50 hover:border-emerald-200 transition-all group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-slate-400 group-hover:text-emerald-600 shadow-sm mb-4 transition-colors">
                                    <i data-lucide="images" class="w-6 h-6"></i>
                                </div>
                                <p class="mb-1 text-sm text-slate-600 font-bold">Pilih Foto-foto Villa</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest text-center px-4">Pilih banyak foto sekaligus. PNG, JPG atau JPEG (Maks. 10MB per file)</p>
                            </div>
                            <input id="foto" type="file" name="foto[]" class="hidden" multiple onchange="previewImages(this)" />
                        </label>
                        
                        <div id="preview-container-wrapper" class="mt-6 hidden">
                            <div class="flex items-center gap-2 mb-4">
                                <i data-lucide="eye" class="w-4 h-4 text-emerald-600"></i>
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Pratinjau Galeri Terpilih</span>
                            </div>
                            <div id="preview-grid" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <!-- Previews will be injected here -->
                            </div>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                </div>

                <script>
                    let villaFilesDataTransfer = new DataTransfer();

                    function previewImages(input) {
                        if (input.files && input.files.length > 0) {
                            // Append new files to our DataTransfer object
                            Array.from(input.files).forEach(file => {
                                villaFilesDataTransfer.items.add(file);
                            });
                            
                            // Update input files to match our DataTransfer object
                            input.files = villaFilesDataTransfer.files;
                            
                            renderPreviews();
                        }
                    }

                    function renderPreviews() {
                        const wrapper = document.getElementById('preview-container-wrapper');
                        const grid = document.getElementById('preview-grid');
                        const input = document.getElementById('foto');
                        
                        grid.innerHTML = '';
                        
                        if (villaFilesDataTransfer.files.length > 0) {
                            wrapper.classList.remove('hidden');
                            
                            Array.from(villaFilesDataTransfer.files).forEach((file, index) => {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    const div = document.createElement('div');
                                    div.className = 'relative group aspect-video overflow-hidden rounded-2xl border border-slate-200 shadow-sm';
                                    div.innerHTML = `
                                        <img src="${e.target.result}" class="w-full h-full object-cover transition-transform group-hover:scale-110" />
                                        <button type="button" onclick="removeNewImage(${index})" class="absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600 shadow-lg z-10">
                                            <i data-lucide="x" class="w-4 h-4"></i>
                                        </button>
                                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                            <i data-lucide="maximize-2" class="w-5 h-5 text-white"></i>
                                        </div>
                                    `;
                                    grid.appendChild(div);
                                    if (window.lucide) window.lucide.createIcons();
                                }
                                reader.readAsDataURL(file);
                            });
                        } else {
                            wrapper.classList.add('hidden');
                            input.value = ''; // Reset input
                        }
                    }

                    function removeNewImage(index) {
                        const newDataTransfer = new DataTransfer();
                        const files = villaFilesDataTransfer.files;
                        
                        for (let i = 0; i < files.length; i++) {
                            if (i !== index) {
                                newDataTransfer.items.add(files[i]);
                            }
                        }
                        
                        villaFilesDataTransfer = newDataTransfer;
                        document.getElementById('foto').files = villaFilesDataTransfer.files;
                        renderPreviews();
                    }
                </script>

                <div class="mt-8">
                    <x-input-label for="deskripsi" value="Deskripsi Eksklusif" class="text-slate-700 font-bold" />
                    <textarea id="deskripsi" name="deskripsi" rows="4" class="border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm block mt-2 w-full" placeholder="Ceritakan keistimewaan villa ini...">{{ old('deskripsi') }}</textarea>
                    <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                </div>

                <div class="mt-10 flex justify-end gap-4 border-t border-slate-100 pt-6">
                    <a href="{{ route('admin.villa.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-colors">Batal</a>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-colors shadow-md shadow-emerald-500/20">
                        Simpan Unit Villa
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
