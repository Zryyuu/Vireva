<x-admin-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate__animated animate__fadeIn">
        
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.biaya.index') }}" class="p-3 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-emerald-600 transition-colors shadow-sm">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Catat <span class="text-emerald-600">Pengeluaran</span></h1>
                <p class="text-slate-500 text-sm mt-1 font-medium">Masukkan detail pengeluaran operasional villa.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-8 md:p-10">
            <form action="{{ route('admin.biaya.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Item Name -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 mb-2">Item Pengeluaran</label>
                        <input type="text" name="item_biaya" required placeholder="Contoh: Tagihan Listrik" 
                            class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-emerald-500 focus:ring-0 transition-all font-bold text-slate-900">
                    </div>

                    <!-- Amount -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 mb-2">Jumlah (Rp)</label>
                        <input type="text" id="jumlah_display" required placeholder="0" 
                            class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-emerald-500 focus:ring-0 transition-all font-bold text-slate-900">
                        <input type="hidden" name="jumlah" id="jumlah_raw">
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 mb-2">Kategori</label>
                        <select name="kategori" required 
                            class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-emerald-500 focus:ring-0 transition-all font-bold text-slate-900 appearance-none">
                            <option value="Listrik">Listrik</option>
                            <option value="Air">Air</option>
                            <option value="Gaji Staff">Gaji Staff</option>
                            <option value="Maintenance">Maintenance Villa</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 mb-2">Tanggal</label>
                        <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}"
                            class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-emerald-500 focus:ring-0 transition-all font-bold text-slate-900">
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 mb-2">Keterangan Tambahan (Opsional)</label>
                    <textarea name="keterangan" rows="3" placeholder="Tambahkan catatan detail jika perlu..." 
                        class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:border-emerald-500 focus:ring-0 transition-all font-medium text-slate-600"></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-slate-900 hover:bg-emerald-600 text-white py-5 rounded-2xl font-black text-lg transition-all shadow-xl hover:shadow-emerald-200/50">
                        Simpan Catatan Biaya
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const displayInput = document.getElementById('jumlah_display');
            const rawInput = document.getElementById('jumlah_raw');

            displayInput.addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, '');
                rawInput.value = value;
                if (value !== '') {
                    this.value = new Intl.NumberFormat('id-ID').format(value);
                } else {
                    this.value = '';
                }
            });
        });
    </script>
    @endpush
</x-admin-layout>

