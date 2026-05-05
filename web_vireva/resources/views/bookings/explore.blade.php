<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold flex items-center gap-2 text-2xl text-slate-900 leading-tight">
            Eksplorasi <span class="text-emerald-600">Keistimewaan</span>
        </h2>
    </x-slot>

    <div class="py-12 animate__animated animate__fadeIn">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-10 text-center max-w-2xl mx-auto px-4">
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight mb-4">Temukan Villa Impian Anda.</h1>
                <p class="text-slate-500 text-lg">Jelajahi koleksi unit villa premium Vireva. Fasilitas pribadi, privasi total, dan kenyamanan mewah menanti Anda.</p>
            </div>

            @if($villas->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 px-4 sm:px-0">
                    @foreach($villas as $item)
                        <div class="bg-white rounded-[2rem] overflow-hidden border border-slate-200 hover:border-emerald-300 transition-all duration-500 group flex flex-col shadow-sm hover:shadow-xl hover:-translate-y-2">
                            
                            <!-- Image Area -->
                            <div class="relative h-64 overflow-hidden bg-slate-100">
                                @if($item->foto)
                                    <img src="{{ $item->image_url }}" alt="Villa {{ $item->nama_villa }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                                        <i data-lucide="image" class="w-12 h-12 mb-2"></i>
                                        <span class="text-xs uppercase tracking-widest font-bold">Tidak Ada Gambar</span>
                                    </div>
                                @endif
                                
                                <!-- Tags -->
                                <div class="absolute top-4 left-4 z-10">
                                    <span class="px-3 py-1.5 bg-white/90 backdrop-blur-md text-emerald-700 text-xs font-bold rounded-xl shadow-sm border border-emerald-100/50 uppercase tracking-widest">
                                        {{ $item->tipe_villa }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content Area -->
                            <div class="p-6 sm:p-8 flex flex-col flex-1">
                                <div class="flex items-start justify-between gap-4 mb-4">
                                    <div>
                                        <h3 class="text-xl font-black text-slate-900 mb-1 group-hover:text-emerald-700 transition-colors">{{ $item->nama_villa }}</h3>
                                        <div class="flex items-center gap-4 text-[10px] text-slate-500 font-bold uppercase tracking-widest">
                                            <span class="flex items-center gap-1.5"><i data-lucide="bed" class="w-3.5 h-3.5 text-emerald-600"></i> {{ $item->jumlah_bedroom }} Kamar</span>
                                            <span class="flex items-center gap-1.5"><i data-lucide="bath" class="w-3.5 h-3.5 text-emerald-600"></i> {{ $item->jumlah_bathroom }} Mandi</span>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-sm text-slate-600 line-clamp-3 mb-6 leading-relaxed">
                                    {{ $item->deskripsi ?? 'Nikmati fasilitas berstandar tinggi dengan privasi penuh di unit villa eksklusif kami.' }}
                                </p>

                                <div class="mt-auto pt-6 border-t border-slate-100 flex items-center justify-between">
                                    <div>
                                        <span class="text-xs text-slate-400 font-bold uppercase tracking-widest block mb-0.5">Mulai Dari</span>
                                        <div class="text-xl font-black text-emerald-600">Rp {{ number_format($item->harga_permalam, 0, ',', '.') }}<span class="text-sm text-slate-400 font-medium">/mlm</span></div>
                                    </div>
                                    <a href="{{ route('bookings.create', $item->id) }}" class="bg-slate-900 hover:bg-emerald-600 text-white font-bold px-5 py-3 rounded-xl transition-colors shadow-md">
                                        Reservasi
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-2xl mx-auto mt-12 shadow-sm">
                    <div class="w-24 h-24 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="home" class="w-12 h-12 text-slate-300"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-2">Semua Unit Telah Terpesan</h3>
                    <p class="text-slate-500 mb-8 max-w-md mx-auto">Kami memohon maaf, saat ini semua unit villa mewah kami sedang direservasi. Silakan periksa kembali di lain waktu.</p>
                    <a href="{{ route('dashboard') }}" class="inline-flex py-3 px-6 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all shadow-md">
                        Kembali ke Dashboard
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
