<x-app-layout>
    <div class="bg-white min-h-screen pb-24 font-sans animate__animated animate__fadeIn">
        
        {{-- ========= TOP NAVIGATION ========= --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <a href="{{ route('bookings.explore') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-900 transition-colors font-medium">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Katalog
            </a>
        </div>

        {{-- ========= MAIN CONTENT ========= --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="mb-8 bg-red-50 p-4 rounded-xl border border-red-100 flex items-center gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                    <span class="text-sm font-medium text-red-700">{{ session('error') }}</span>
                </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-16 items-center">

                {{-- ===== LEFT: VILLA DETAILS ===== --}}
                <div class="w-full lg:w-3/5 space-y-10">
                    
                    {{-- Header Info --}}
                    <div>
                        <div class="mb-4">
                            <span class="px-3 py-1 bg-slate-100 text-slate-700 font-bold text-[10px] uppercase tracking-widest rounded-md">{{ $villa->tipe_villa }}</span>
                        </div>
                        <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">{{ $villa->nama_villa }}</h1>
                        <p class="text-slate-500 mt-3 text-lg">{{ $villa->lokasi ?? 'Vireva Luxury Estate' }}</p>
                    </div>

                    {{-- Main Image Slider --}}
                    <div class="relative group" x-data="{ 
                        active: 0,
                        total: {{ is_array($villa->foto) ? count($villa->foto) : 1 }},
                        next() {
                            this.active = (this.active + 1) % this.total;
                            this.scrollToActive();
                        },
                        prev() {
                            this.active = (this.active - 1 + this.total) % this.total;
                            this.scrollToActive();
                        },
                        scrollToActive() {
                            this.$refs.slider.scrollTo({
                                left: this.$refs.slider.offsetWidth * this.active,
                                behavior: 'smooth'
                            });
                        }
                    }">
                        <div class="w-full aspect-[16/9] rounded-3xl overflow-x-auto flex snap-x snap-mandatory no-scrollbar bg-slate-100 border border-slate-200 shadow-sm" x-ref="slider" 
                             @scroll.debounce.150ms="active = Math.round($refs.slider.scrollLeft / $refs.slider.offsetWidth)">
                            @if(is_array($villa->foto) && count($villa->foto) > 0)
                                @foreach($villa->foto as $path)
                                    <div class="w-full h-full shrink-0 snap-center snap-always">
                                        <img src="{{ url('storage/' . $path) }}" class="w-full h-full object-cover" alt="{{ $villa->nama_villa }}">
                                    </div>
                                @endforeach
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                                    <i data-lucide="image" class="w-12 h-12 mb-2 opacity-50"></i>
                                    <span class="text-sm font-medium">Foto tidak tersedia</span>
                                </div>
                            @endif
                        </div>

                        {{-- Next/Prev Buttons --}}
                        @if(is_array($villa->foto) && count($villa->foto) > 1)
                            <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/90 backdrop-blur shadow-lg rounded-full flex items-center justify-center text-slate-900 hover:bg-white hover:scale-110 transition-all z-20 opacity-0 group-hover:opacity-100 border border-slate-100">
                                <i data-lucide="chevron-left" class="w-5 h-5"></i>
                            </button>
                            <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/90 backdrop-blur shadow-lg rounded-full flex items-center justify-center text-slate-900 hover:bg-white hover:scale-110 transition-all z-20 opacity-0 group-hover:opacity-100 border border-slate-100">
                                <i data-lucide="chevron-right" class="w-5 h-5"></i>
                            </button>

                            <div class="absolute top-4 right-4 bg-slate-900/80 backdrop-blur-md text-white px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-widest border border-white/10 z-20">
                                <span x-text="active + 1"></span> / <span x-text="total"></span>
                            </div>
                        @endif
                    </div>

                    {{-- Specs (Clean, inline) --}}
                    <div class="flex flex-wrap gap-x-8 gap-y-4 py-6 border-y border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100 text-slate-600 shadow-sm"><i data-lucide="bed-double" class="w-5 h-5"></i></div>
                            <div>
                                <div class="text-xs text-slate-500 font-medium uppercase tracking-wider mb-0.5">Kamar Tidur</div>
                                <div class="text-sm font-bold text-slate-900">{{ $villa->jumlah_bedroom }} Kamar</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100 text-slate-600 shadow-sm"><i data-lucide="bath" class="w-5 h-5"></i></div>
                            <div>
                                <div class="text-xs text-slate-500 font-medium uppercase tracking-wider mb-0.5">Kamar Mandi</div>
                                <div class="text-sm font-bold text-slate-900">{{ $villa->jumlah_bathroom }} Kamar</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100 text-slate-600 shadow-sm"><i data-lucide="users" class="w-5 h-5"></i></div>
                            <div>
                                <div class="text-xs text-slate-500 font-medium uppercase tracking-wider mb-0.5">Kapasitas</div>
                                <div class="text-sm font-bold text-slate-900">Maks {{ $villa->kapasitas }} Tamu</div>
                            </div>
                        </div>
                        @if($villa->luas_bangunan)
                            <div class="flex items-center gap-3">
                                <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100 text-slate-600 shadow-sm"><i data-lucide="maximize-2" class="w-5 h-5"></i></div>
                                <div>
                                    <div class="text-xs text-slate-500 font-medium uppercase tracking-wider mb-0.5">Luas Area</div>
                                    <div class="text-sm font-bold text-slate-900">{{ $villa->luas_bangunan }} m²</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Description --}}
                    <div class="space-y-4">
                        <h2 class="text-xl font-bold text-slate-900">Tentang Villa Ini</h2>
                        <p class="text-slate-600 leading-relaxed text-base">{{ $villa->deskripsi ?? 'Informasi deskripsi villa belum tersedia.' }}</p>
                    </div>
                    
                </div>

                {{-- ===== RIGHT: BOOKING FORM ===== --}}
                <div class="w-full lg:w-2/5 lg:sticky lg:top-28">
                    <div class="bg-white border border-slate-200 rounded-[2rem] p-8 shadow-2xl shadow-slate-200/50">
                        
                        {{-- Price Header --}}
                        <div class="mb-8">
                            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Mulai Dari</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-black text-slate-900">Rp {{ number_format($villa->harga_permalam, 0, ',', '.') }}</span>
                                <span class="text-base font-medium text-slate-500">/ malam</span>
                            </div>
                        </div>

                        {{-- Form --}}
                        <form action="{{ route('bookings.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="villa_id" value="{{ $villa->id }}">

                            {{-- High Quality Inputs --}}
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-2">Check-in</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                            <i data-lucide="calendar" class="w-4 h-4"></i>
                                        </div>
                                        <input type="date" name="tanggal_checkin" id="checkin" 
                                            class="block w-full pl-11 pr-3 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-900 focus:bg-white focus:border-slate-900 focus:ring-1 focus:ring-slate-900 transition-all shadow-sm" 
                                            required min="{{ date('Y-m-d') }}" max="2030-12-31">
                                    </div>
                                    <x-input-error :messages="$errors->get('tanggal_checkin')" class="mt-2" />
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-2">Check-out</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                            <i data-lucide="calendar-check" class="w-4 h-4"></i>
                                        </div>
                                        <input type="date" name="tanggal_checkout" id="checkout" 
                                            class="block w-full pl-11 pr-3 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-900 focus:bg-white focus:border-slate-900 focus:ring-1 focus:ring-slate-900 transition-all shadow-sm" 
                                            required max="2030-12-31">
                                    </div>
                                    <x-input-error :messages="$errors->get('tanggal_checkout')" class="mt-2" />
                                </div>
                            </div>

                            {{-- Price Breakdown (Receipt Style) --}}
                            <div class="bg-slate-50 rounded-2xl border border-slate-100 p-6 mb-8">
                                <div class="space-y-4 text-sm">
                                    <div class="flex justify-between text-slate-600">
                                        <span>Rp {{ number_format($villa->harga_permalam, 0, ',', '.') }} x <span id="daysCountSmall" class="font-bold">0</span> malam</span>
                                        <span class="font-bold text-slate-900" id="totalBase">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between text-slate-600">
                                        <span>Biaya Layanan & Pajak</span>
                                        <span class="font-bold text-emerald-600">Termasuk</span>
                                    </div>
                                </div>
                                <div class="pt-4 mt-4 border-t border-slate-200 flex justify-between items-center">
                                    <span class="font-bold text-slate-900">Total Pembayaran</span>
                                    <span class="text-2xl font-black text-slate-900" id="totalPrice">Rp {{ number_format($villa->harga_permalam, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <button type="submit" 
                                class="w-full py-4 bg-slate-900 hover:bg-slate-800 active:bg-black text-white font-bold rounded-2xl transition-all shadow-xl shadow-slate-900/20 flex justify-center items-center gap-2 group text-base">
                                Pesan Sekarang <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                            </button>
                            

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ========= STYLE & SCRIPT ========= --}}
    <style>
        nav { display: none !important; }
        main { padding-top: 0 !important; }
        .bg-light-grid { background-image: none !important; background-color: white !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .snap-always { scroll-snap-stop: always; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkin = document.getElementById('checkin');
            const checkout = document.getElementById('checkout');
            const pricePerNight = {{ $villa->harga_permalam }};
            const formatter = new Intl.NumberFormat('id-ID');

            function calculatePrice() {
                if (checkin.value && checkout.value) {
                    const start = new Date(checkin.value);
                    const end = new Date(checkout.value);
                    const diffDays = Math.ceil((end - start) / (1000 * 60 * 60 * 24));

                    if (diffDays > 0) {
                        const total = diffDays * pricePerNight;
                        document.getElementById('daysCountSmall').innerText = diffDays;
                        document.getElementById('totalBase').innerText = 'Rp ' + formatter.format(total);
                        document.getElementById('totalPrice').innerText = 'Rp ' + formatter.format(total);
                    } else {
                        resetPrice();
                    }
                } else {
                    resetPrice();
                }
            }

            function resetPrice() {
                document.getElementById('daysCountSmall').innerText = '0';
                document.getElementById('totalBase').innerText = 'Rp 0';
                document.getElementById('totalPrice').innerText = 'Rp ' + formatter.format(pricePerNight);
            }

            checkin.addEventListener('change', function () {
                let nextDay = new Date(this.value);
                nextDay.setDate(nextDay.getDate() + 1);
                checkout.min = nextDay.toISOString().split('T')[0];
                if (checkout.value && checkout.value <= this.value) {
                    checkout.value = checkout.min;
                }
                calculatePrice();
            });

            checkout.addEventListener('change', calculatePrice);
        });
    </script>
</x-app-layout>
