<x-app-layout>
    <div class="py-10 md:py-16 animate__animated animate__fadeIn">
        <div class="max-w-lg mx-auto px-6">
            
            <div class="mb-8 no-print flex justify-between items-center">
                <a href="{{ route('bookings.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-900 transition-colors">
                    <i data-lucide="arrow-left" class="w-3 h-3"></i> Kembali
                </a>
                <div class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Pemesanan #{{ $pemesanan->id }}</div>
            </div>

            @if($pemesanan->status_pembayaran == 'pending')
                <!-- Billing / Payment Instruction Card -->
                <div class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden shadow-sm p-8 md:p-12">
                    <div class="text-center mb-10">
                        <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 border border-amber-100">
                            <i data-lucide="clock" class="w-8 h-8"></i>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Menunggu Pembayaran</h2>
                        <p class="text-sm text-slate-500 font-medium mt-1">Silakan selesaikan pembayaran untuk mengonfirmasi reservasi Anda.</p>
                    </div>

                    <div class="space-y-6 max-w-sm mx-auto">
                        <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Tagihan</span>
                            <span class="text-xl font-black text-slate-900">Rp {{ number_format($pemesanan->total_biaya, 0, ',', '.') }}</span>
                        </div>

                        <div class="p-6 bg-emerald-50 rounded-3xl border border-emerald-100">
                            <div class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-4">Transfer Ke Rekening</div>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-emerald-800">SeaBank</span>
                                    <span class="text-sm font-black text-slate-900">901880332521</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-emerald-800">Atas Nama</span>
                                    <span class="text-sm font-black text-slate-900">VIREVA VILLA</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6">
                            @if($pemesanan->bukti_pembayaran)
                                <div class="text-center p-4 bg-blue-50 text-blue-600 rounded-2xl border border-blue-100">
                                    <div class="flex items-center justify-center gap-2 text-xs font-black uppercase tracking-widest">
                                        <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                                        Menunggu Verifikasi Admin
                                    </div>
                                </div>
                            @else
                                <div class="p-4 bg-red-50 text-red-600 rounded-2xl border border-red-100 text-center mb-4">
                                    <div class="text-[10px] font-black uppercase tracking-widest">⚠️ Belum Upload Bukti</div>
                                </div>
                                <form action="{{ route('bookings.upload-proof', $pemesanan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    <div class="relative">
                                        <input type="file" name="bukti_pembayaran" required class="block w-full text-[10px] text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-slate-900 file:text-white cursor-pointer">
                                    </div>
                                    <button type="submit" class="w-full py-4 bg-emerald-600 text-white font-black text-[10px] uppercase tracking-widest rounded-2xl shadow-lg shadow-emerald-500/20 hover:bg-emerald-700 transition-all">Upload Bukti Sekarang</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <!-- Thermal Receipt Container (Only for Confirmed/Selesai) -->
                <div id="receipt" class="bg-white mx-auto overflow-hidden relative transition-all" style="width: 320px; font-family: 'Courier New', Courier, monospace; color: #000;">
                    
                    <!-- Jagged Edge Top -->
                    <div class="h-2 bg-slate-50 w-full" style="background-image: radial-gradient(circle at 10px -7px, transparent 12px, #fff 13px); background-size: 20px 20px;"></div>

                    <div class="p-6">
                        <!-- Logo -->
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('img/logo.png') }}" class="w-20 h-auto" alt="Vireva Logo">
                        </div>

                        <!-- Header -->
                        <div class="text-center mb-6">
                            <div class="text-2xl font-black uppercase tracking-widest mb-1">VIREVA VILLA</div>
                            <div class="text-[10px] font-bold leading-tight">
                                LUXURY STAY & EXPERIENCE<br>
                                Curahbamban, Tanggul Wetan<br>
                                0851-9814-9402
                            </div>
                        </div>

                        <!-- Separator -->
                        <div class="text-center text-xs mb-4">================================</div>

                        <!-- Invoice Info -->
                        <div class="text-xs space-y-1 mb-4">
                            <div class="flex justify-between">
                                <span>INVOICE:</span>
                                <span class="font-bold">#VRV-{{ str_pad($pemesanan->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>TANGGAL:</span>
                                <span>{{ $pemesanan->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>TAMU:</span>
                                <span class="font-bold uppercase text-right">{{ $pemesanan->tamu->nama_tamu }}</span>
                            </div>
                        </div>

                        <div class="text-center text-xs mb-4">--------------------------------</div>

                        <!-- Items / Details -->
                        <div class="text-xs space-y-2 mb-4">
                            <div class="font-bold uppercase">{{ $pemesanan->villa->nama_villa }}</div>
                            <div class="flex justify-between text-[10px]">
                                <span>{{ $pemesanan->total_hari }} MALAM @ Rp {{ number_format($pemesanan->villa->harga_permalam, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>CHECK-IN:</span>
                                <span>{{ $pemesanan->tanggal_checkin->format('d/m/y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>CHECK-OUT:</span>
                                <span>{{ $pemesanan->tanggal_checkout->format('d/m/y') }}</span>
                            </div>
                        </div>

                        <div class="text-center text-xs mb-4">================================</div>

                        <!-- Total -->
                        <div class="space-y-1 mb-6">
                            <div class="flex justify-between text-base font-bold">
                                <span>TOTAL:</span>
                                <span>Rp {{ number_format($pemesanan->total_biaya, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-[10px]">
                                <span>STATUS:</span>
                                <span class="font-bold">{{ strtoupper($pemesanan->status_pembayaran) }}</span>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="text-center space-y-1">
                            <div class="text-[10px] font-bold">TERIMA KASIH</div>
                            <div class="text-[8px] opacity-50 uppercase tracking-widest">Simpan struk ini sebagai bukti check-in</div>
                        </div>
                    </div>
                    
                    <!-- Jagged Edge Bottom -->
                    <div class="h-2 bg-slate-50 w-full" style="background-image: radial-gradient(circle at 10px 27px, transparent 12px, #fff 13px); background-size: 20px 20px;"></div>
                </div>

                <!-- Bottom Actions (Print) -->
                @if($pemesanan->status_pembayaran == 'settlement')
                    <div class="mt-10 flex justify-center no-print">
                        <button onclick="window.print()" class="px-8 py-4 bg-slate-900 text-white font-black text-[10px] uppercase tracking-widest hover:bg-slate-800 transition-all shadow-2xl shadow-slate-900/20 flex items-center justify-center gap-3">
                            <i data-lucide="printer" class="w-4 h-4"></i> Cetak Thermal Struk
                        </button>
                    </div>
                @endif
            @endif

            <!-- Global Cancel Button -->
            <div class="mt-8 flex justify-center no-print">
                @if($pemesanan->status_pemesanan == 'menunggu')
                    <form action="{{ route('bookings.cancel', $pemesanan->id) }}" method="POST" onsubmit="return confirm('Batalkan reservasi?');">
                        @csrf
                        <button type="submit" class="text-[9px] font-black text-red-400 hover:text-red-600 uppercase tracking-widest transition-colors">Batalkan Reservasi</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
        </div>
    </div>

    <style>
        @media print {
            nav, header, footer, .no-print, .lucide { display: none !important; }
            body { background: white !important; padding: 0 !important; margin: 0 !important; }
            .max-w-lg { max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
            #receipt { 
                box-shadow: none !important; 
                border: none !important; 
                width: 58mm !important; /* Standar Thermal 58mm */
                margin: 0 auto !important;
                padding: 0 !important;
            }
            .p-6 { padding: 5mm !important; }
            .text-center { text-align: center !important; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>

    <script>
        setTimeout(() => {
            const alerts = document.querySelectorAll('.bg-emerald-50, .bg-red-50, .bg-blue-50');
            alerts.forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-20px)';
                el.style.transition = 'all 0.5s ease';
                setTimeout(() => el.remove(), 500);
            });
        }, 3000);
    </script>
</x-app-layout>
