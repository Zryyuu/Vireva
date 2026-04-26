<x-app-layout>
    <div class="py-0 animate__animated animate__fadeIn">
        
        <!-- Hero Section -->
        <div class="relative w-full h-[50vh] min-h-[400px] bg-slate-900 border-b border-slate-200">
            @if($villa->foto)
                <img src="{{ asset('storage/' . $villa->foto) }}" class="absolute inset-0 w-full h-full object-cover opacity-60" alt="{{ $villa->nama_villa }}">
            @else
                <div class="absolute inset-0 w-full h-full bg-slate-800 opacity-60"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>
            
            <div class="absolute bottom-0 left-0 w-full p-8 md:p-12 max-w-7xl mx-auto">
                <a href="{{ route('bookings.explore') }}" class="inline-flex items-center gap-2 text-white/70 hover:text-white font-bold text-sm mb-4 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Eksplorasi
                </a>
                <div class="flex flex-wrap gap-3 mb-3">
                    <span class="px-3 py-1 bg-emerald-500 text-white font-bold text-xs rounded-full uppercase tracking-widest shadow-sm">{{ $villa->tipe_villa }}</span>
                    <span class="px-3 py-1 bg-white/20 backdrop-blur text-white font-bold text-xs rounded-full uppercase tracking-widest">Premium Retreat</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight">{{ $villa->nama_villa }}.</h1>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            
            @if(session('error'))
                <div class="mb-8 bg-red-50 p-4 rounded-2xl border-l-4 border-red-500 flex items-center gap-3 shadow-sm">
                    <div class="p-2 bg-white rounded-full text-red-600 shadow-sm">
                        <i data-lucide="alert-circle" class="w-5 h-5"></i>
                    </div>
                    <div class="text-sm font-bold text-red-700">{{ session('error') }}</div>
                </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-12">
                <!-- Left: Details -->
                <div class="w-full lg:w-3/5 space-y-12">
                    <section>
                        <h2 class="text-2xl font-bold text-slate-900 mb-4">Mengenai Villa Ini</h2>
                        <p class="text-slate-600 leading-relaxed text-lg">{{ $villa->deskripsi }}</p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold text-slate-900 mb-6">Spesifikasi & Fasilitas</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-emerald-600 shadow-sm">
                                    <i data-lucide="bed" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm">Kamar Tidur</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $villa->jumlah_bedroom }} Kamar Tidur</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-emerald-600 shadow-sm">
                                    <i data-lucide="bath" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm">Kamar Mandi</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $villa->jumlah_bathroom }} Kamar Mandi</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-emerald-600 shadow-sm">
                                    <i data-lucide="users" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm">Kapasitas Maksimal</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Hingga {{ $villa->kapasitas }} Tamu</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-emerald-600 shadow-sm">
                                    <i data-lucide="maximize" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm">Luas Bangunan</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $villa->luas_bangunan ?? '-' }} m² Area</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Right: Booking Form (Checkout Cart Style) -->
                <div class="w-full lg:w-2/5">
                    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl p-8 sticky top-8">
                        <div class="mb-6 pb-6 border-b border-slate-100">
                            <div class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-1">Tarif Reservasi</div>
                            <div class="text-3xl font-black text-emerald-600">
                                Rp {{ number_format($villa->harga_permalam, 0, ',', '.') }}
                                <span class="text-sm font-medium text-slate-400">/malam</span>
                            </div>
                        </div>

                        <form action="{{ route('bookings.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="villa_id" value="{{ $villa->id }}">
                            
                            <div class="space-y-5 mb-8">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Check-In</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                            <i data-lucide="calendar" class="w-5 h-5"></i>
                                        </div>
                                        <input type="date" name="tanggal_checkin" id="checkin" class="block w-full pl-11 border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" required min="{{ date('Y-m-d') }}">
                                    </div>
                                    <x-input-error :messages="$errors->get('tanggal_checkin')" class="mt-2" />
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Check-Out</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                            <i data-lucide="calendar-check" class="w-5 h-5"></i>
                                        </div>
                                        <input type="date" name="tanggal_checkout" id="checkout" class="block w-full pl-11 border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" required>
                                    </div>
                                    <x-input-error :messages="$errors->get('tanggal_checkout')" class="mt-2" />
                                </div>
                            </div>

                            <div class="bg-slate-50 p-4 rounded-2xl mb-8">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-slate-500 font-medium">Tarif Dasar x <span id="daysCount">1</span> malam</span>
                                    <span class="text-slate-900 font-bold" id="totalBase">Rp {{ number_format($villa->harga_permalam, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm mb-4">
                                    <span class="text-slate-500 font-medium">Layanan Tax (0%)</span>
                                    <span class="text-slate-900 font-bold">Rp 0</span>
                                </div>
                                <div class="pt-4 border-t border-slate-200 flex justify-between items-center">
                                    <span class="text-slate-900 font-bold">Total Pembayaran</span>
                                    <span class="text-xl font-black text-emerald-600" id="totalPrice">Rp {{ number_format($villa->harga_permalam, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-4 px-6 bg-slate-900 hover:bg-emerald-600 text-white font-bold rounded-xl transition-all shadow-md flex justify-center items-center gap-2 group">
                                Buat Reservasi Sekarang <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                            </button>
                            <p class="text-center text-[10px] text-slate-500 uppercase tracking-widest font-bold mt-4">Vireva Luxury Stay Experience</p>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Script to calculate price dynamically -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkin = document.getElementById('checkin');
            const checkout = document.getElementById('checkout');
            const pricePerNight = {{ $villa->harga_permalam }};
            const formatter = new Intl.NumberFormat('id-ID');

            function calculatePrice() {
                if(checkin.value && checkout.value) {
                    const start = new Date(checkin.value);
                    const end = new Date(checkout.value);
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                    
                    if(diffDays > 0 && start <= end) {
                        document.getElementById('daysCount').innerText = diffDays;
                        const total = diffDays * pricePerNight;
                        document.getElementById('totalBase').innerText = 'Rp ' + formatter.format(total);
                        document.getElementById('totalPrice').innerText = 'Rp ' + formatter.format(total);
                    }
                }
            }

            checkin.addEventListener('change', function() {
                // Ensure checkout is strictly after checkin
                let nextDay = new Date(this.value);
                nextDay.setDate(nextDay.getDate() + 1);
                checkout.min = nextDay.toISOString().split('T')[0];
                if(checkout.value && checkout.value <= this.value) {
                    checkout.value = checkout.min;
                }
                calculatePrice();
            });

            checkout.addEventListener('change', calculatePrice);
        });
    </script>
</x-app-layout>
