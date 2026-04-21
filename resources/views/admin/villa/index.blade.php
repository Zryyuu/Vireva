<x-admin-layout>
    <div class="space-y-6 sm:space-y-8 animate__animated animate__fadeIn">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 mb-1">Katalog Unit Villa</h1>
                <p class="text-sm text-slate-500 font-medium">Kelola inventaris villa, harga, dan ketersediaan unit Anda.</p>
            </div>
            <a href="{{ route('admin.villa.create') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 px-5 py-2.5 rounded-xl font-bold text-sm text-white transition-all transform hover:-translate-y-1 shadow-md shadow-emerald-500/20">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Villa</span>
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 p-4 rounded-2xl border-l-4 border-emerald-500 flex items-center gap-3 shadow-sm">
                <div class="p-2 bg-white rounded-full text-emerald-600 shadow-sm border border-emerald-100">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
                <div class="text-sm font-bold text-emerald-700">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 p-4 rounded-2xl border-l-4 border-red-500 flex items-center gap-3 shadow-sm">
                <div class="p-2 bg-white rounded-full text-red-600 shadow-sm border border-red-100">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                </div>
                <div class="text-sm font-bold text-red-700">{{ session('error') }}</div>
            </div>
        @endif

        @if($villas->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($villas as $item)
                    <!-- Premium Grand Card -->
                    <div class="bg-white rounded-3xl overflow-hidden border border-slate-200 hover:border-emerald-300 transition-all duration-300 group flex flex-col shadow-sm hover:shadow-lg">
                        
                        <!-- Image Area -->
                        <div class="relative h-56 xl:h-64 overflow-hidden bg-slate-100">
                            @if($item->foto)
                                <img src="{{ asset('storage/' . $item->foto) }}" alt="Villa {{ $item->nama_villa }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                                    <i data-lucide="image" class="w-12 h-12 mb-2"></i>
                                    <span class="text-xs uppercase tracking-widest font-bold">No Image</span>
                                </div>
                            @endif
                            <!-- Overlay Gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
                            
                            <!-- Badges -->
                            <div class="absolute top-4 left-4 z-10">
                                <div class="bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest text-slate-800 shadow-sm">
                                    {{ $item->tipe_villa }}
                                </div>
                            </div>
                            <div class="absolute top-4 right-4 z-10">
                                @if($item->status_villa === 'tersedia')
                                    <div class="bg-emerald-500 text-white px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest shadow-md shadow-emerald-500/30">
                                        Tersedia
                                    </div>
                                @elseif($item->status_villa === 'terisi')
                                    <div class="bg-blue-500 text-white px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest shadow-md shadow-blue-500/30">
                                        Occupied
                                    </div>
                                @else
                                    <div class="bg-red-500 text-white px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest shadow-md shadow-red-500/30">
                                        Maintenance
                                    </div>
                                @endif
                            </div>

                            <!-- Title area -->
                            <div class="absolute bottom-4 left-6 right-6 z-20">
                                <div class="text-[10px] text-emerald-300 font-bold tracking-widest uppercase mb-1 drop-shadow-md">Vireva Collection</div>
                                <h3 class="text-2xl font-extrabold text-white drop-shadow-md">{{ $item->nama_villa }}</h3>
                            </div>
                        </div>
                        
                        <!-- Content Area -->
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="mb-5 flex justify-between items-end">
                                <div class="font-extrabold text-xl text-slate-900">Rp {{ number_format($item->harga_permalam, 0, ',', '.') }}<span class="text-[10px] text-slate-400 uppercase tracking-widest ml-1 font-bold">/ Malam</span></div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $item->luas_bangunan }} m²</div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-2 mb-6">
                                <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-50 border border-slate-100 text-[10px] font-bold text-slate-600 uppercase tracking-tight">
                                    <i data-lucide="bed" class="w-3.5 h-3.5 text-emerald-600"></i>
                                    {{ $item->jumlah_bedroom }} Bedroom
                                </div>
                                <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-50 border border-slate-100 text-[10px] font-bold text-slate-600 uppercase tracking-tight">
                                    <i data-lucide="bath" class="w-3.5 h-3.5 text-emerald-600"></i>
                                    {{ $item->jumlah_bathroom }} Bath
                                </div>
                                <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-50 border border-slate-100 text-[10px] font-bold text-slate-600 uppercase tracking-tight">
                                    <i data-lucide="users" class="w-3.5 h-3.5 text-emerald-600"></i>
                                    {{ $item->kapasitas }} Tamu
                                </div>
                                <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-50 border border-slate-100 text-[10px] font-bold text-slate-600 uppercase tracking-tight">
                                    <i data-lucide="waves" class="w-3.5 h-3.5 text-emerald-600"></i>
                                    Pool
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3 pt-4 border-t border-slate-100 mt-auto">
                                <a href="{{ route('admin.villa.edit', $item->id) }}" class="flex items-center justify-center gap-2 py-3 rounded-xl bg-slate-50 border border-slate-200 hover:bg-slate-100 hover:border-slate-300 text-sm font-bold text-slate-700 transition-all">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i> Edit
                                </a>
                                <form action="{{ route('admin.villa.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus unit villa eksklusif ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 rounded-xl bg-red-50 border border-red-100 hover:bg-red-500 hover:text-white hover:border-red-500 text-sm font-bold text-red-500 transition-all">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white flex flex-col items-center justify-center p-16 text-center rounded-3xl border border-slate-200 border-dashed mt-8 max-w-3xl mx-auto shadow-sm">
                <div class="w-24 h-24 rounded-full bg-slate-50 flex items-center justify-center mb-6 text-emerald-600 border border-emerald-100 relative">
                    <div class="absolute inset-0 rounded-full border border-emerald-200 animate-ping opacity-50"></div>
                    <i data-lucide="home" class="w-12 h-12"></i>
                </div>
                <h4 class="text-xl font-bold mb-2 text-slate-900">Katalog Unit Kosong</h4>
                <p class="text-slate-500 text-sm max-w-sm mb-8 font-medium">Anda belum mendaftarkan unit villa apapun ke dalam sistem Vireva. Mulai bangun katalog properti eksklusif Anda.</p>
                <a href="{{ route('admin.villa.create') }}" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-sm rounded-xl transition-all hover:-translate-y-1 shadow-lg shadow-emerald-500/20">
                    Tambah Villa Pertama
                </a>
            </div>
        @endif
        
    </div>
</x-admin-layout>
