<x-admin-layout>
    <div class="max-w-4xl mx-auto space-y-8 animate__animated animate__fadeIn">
        
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.biaya.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-emerald-600 transition-colors shadow-sm">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Koreksi Catatan Biaya</h1>
                <p class="text-sm text-slate-500 font-medium">Perbarui detail pengeluaran operasional villa.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
            <form action="{{ route('admin.biaya.update', $biaya->id) }}" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Item Biaya -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Nama Item / Pengeluaran</label>
                        <input type="text" name="item_biaya" value="{{ old('item_biaya', $biaya->item_biaya) }}" required
                            class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 font-medium text-slate-700"
                            placeholder="Contoh: Perbaikan AC Villa A">
                        @error('item_biaya') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Kategori</label>
                        <select name="kategori" required
                            class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 font-medium text-slate-700">
                            <option value="perbaikan" {{ old('kategori', $biaya->kategori) == 'perbaikan' ? 'selected' : '' }}>Perbaikan & Maintenance</option>
                            <option value="listrik" {{ old('kategori', $biaya->kategori) == 'listrik' ? 'selected' : '' }}>Listrik & Air</option>
                            <option value="gaji" {{ old('kategori', $biaya->kategori) == 'gaji' ? 'selected' : '' }}>Gaji Karyawan</option>
                            <option value="perlengkapan" {{ old('kategori', $biaya->kategori) == 'perlengkapan' ? 'selected' : '' }}>Perlengkapan / Amenities</option>
                            <option value="Lainnya" {{ old('kategori', $biaya->kategori) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <!-- Jumlah -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Jumlah Biaya (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">Rp</span>
                            <input type="number" name="jumlah" value="{{ old('jumlah', $biaya->jumlah) }}" required
                                class="w-full bg-slate-50 border-slate-200 rounded-xl pl-12 focus:ring-emerald-500 focus:border-emerald-500 font-bold text-slate-700">
                        </div>
                        @error('jumlah') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Tanggal Pengeluaran</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', $biaya->tanggal->format('Y-m-d')) }}" required
                            class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 font-medium text-slate-700">
                        @error('tanggal') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Keterangan Tambahan (Opsional)</label>
                        <textarea name="keterangan" rows="3"
                            class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 font-medium text-slate-700"
                            placeholder="Detail mengenai pengeluaran ini...">{{ old('keterangan', $biaya->keterangan) }}</textarea>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <a href="{{ route('admin.biaya.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 font-bold text-slate-600 hover:bg-slate-50 transition-all">
                        Batal
                    </a>
                    <button type="submit" class="px-10 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl transition-all shadow-lg shadow-emerald-600/20">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
