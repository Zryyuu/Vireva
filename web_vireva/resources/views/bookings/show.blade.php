<x-app-layout>
    <div class="py-10 md:py-16 animate__animated animate__fadeIn">
        <div class="max-w-lg mx-auto px-6">
            
            <div class="mb-8 no-print flex justify-between items-center">
                <a href="{{ route('bookings.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-900 transition-colors">
                    <i data-lucide="arrow-left" class="w-3 h-3"></i> Kembali
                </a>
                <div class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Pemesanan #{{ $pemesanan->id }}</div>
            </div>

            <!-- Receipt Container -->
            <div id="receipt" class="bg-white shadow-[0_32px_64px_-15px_rgba(0,0,0,0.1)] border border-slate-100 overflow-hidden relative transition-all">
                
                <div class="p-8 md:p-12 text-slate-800">
                    <!-- Header -->
                    <div class="text-center mb-10 pb-10 border-b border-slate-100">
                        <div class="text-3xl font-black uppercase tracking-tighter mb-2 text-slate-900 font-serif">VIREVA VILLA</div>
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] leading-relaxed">
                            Luxury Stay & Experience<br>
                            Bali, Indonesia • www.vireva.com
                        </div>
                    </div>

                    <div class="space-y-8">
                        <!-- Invoice Info -->
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-1">Invoice</div>
                                <div class="text-sm font-black text-slate-900">#VRV-{{ str_pad($pemesanan->id, 5, '0', STR_PAD_LEFT) }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-1">Tanggal</div>
                                <div class="text-sm font-black text-slate-900">{{ $pemesanan->created_at->format('d/m/Y') }}</div>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="space-y-4 pt-4">
                            <div class="flex justify-between items-baseline">
                                <span class="text-slate-400 font-bold uppercase text-[9px] tracking-widest">Tamu</span>
                                <span class="text-sm font-black text-slate-800">{{ $pemesanan->tamu->nama_tamu }}</span>
                            </div>
                            <div class="flex justify-between items-baseline">
                                <span class="text-slate-400 font-bold uppercase text-[9px] tracking-widest">Unit</span>
                                <span class="text-sm font-black text-slate-800">{{ $pemesanan->villa->nama_villa }}</span>
                            </div>
                            <div class="flex justify-between items-baseline">
                                <span class="text-slate-400 font-bold uppercase text-[9px] tracking-widest">Jadwal</span>
                                <span class="text-sm font-black text-slate-800">{{ $pemesanan->tanggal_checkin->format('d M') }} - {{ $pemesanan->tanggal_checkout->format('d M Y') }}</span>
                            </div>
                        </div>

                        <!-- Divider Dash -->
                        <div class="border-t-2 border-dashed border-slate-100 my-8"></div>

                        <!-- Total -->
                        <div class="space-y-6">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Pembayaran</span>
                                <span class="text-2xl font-black text-slate-900 tracking-tighter">Rp {{ number_format($pemesanan->total_biaya, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full {{ $pemesanan->status_pembayaran == 'settlement' ? 'bg-emerald-500' : 'bg-amber-500' }}"></div>
                                    <span class="text-[10px] font-black uppercase tracking-widest {{ $pemesanan->status_pembayaran == 'settlement' ? 'text-emerald-600' : 'text-amber-600' }}">
                                        {{ $pemesanan->status_pembayaran == 'settlement' ? 'LUNAS / PAID' : 'PENDING' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if($pemesanan->status_pembayaran == 'pending')
                             <div class="mt-8 p-6 bg-slate-50 border border-slate-100 no-print text-center">
                                @if($pemesanan->bukti_pembayaran)
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-100">
                                            <i data-lucide="loader-2" class="w-5 h-5 text-amber-500 animate-spin"></i>
                                        </div>
                                        <div>
                                            <div class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Menunggu Verifikasi</div>
                                            <div class="text-[9px] text-slate-400 font-bold uppercase tracking-tight mt-1">Bukti bayar Anda sedang diperiksa oleh tim kami.</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-start gap-3 mb-4 text-left">
                                        <i data-lucide="info" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                                        <p class="text-[9px] text-slate-500 leading-relaxed font-bold uppercase tracking-tight">
                                            Silakan transfer ke <b>BCA 123456789</b> a/n <b>Vireva</b> kemudian upload bukti bayar di bawah ini.
                                        </p>
                                    </div>
                                    <form action="{{ route('bookings.upload-proof', $pemesanan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                        @csrf
                                        <input type="file" name="bukti_pembayaran" required class="block w-full text-[9px] text-slate-400 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-[9px] file:font-black file:bg-white file:text-slate-600 border border-slate-200">
                                        <button type="submit" class="w-full py-3 bg-slate-900 text-white font-black text-[10px] uppercase tracking-widest hover:bg-slate-800 transition-all">Upload Bukti</button>
                                    </form>
                                @endif
                             </div>
                        @endif

                        <!-- Footer -->
                        <div class="text-center pt-10 border-t border-slate-50">
                            <div class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] mb-1">Official Receipt</div>
                            <div class="text-[8px] text-slate-300 font-bold uppercase tracking-widest">Terima kasih telah memilih Vireva Villa</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Actions -->
            <div class="mt-10 flex flex-col sm:flex-row justify-between items-center gap-4 no-print">
                @if($pemesanan->status_pemesanan == 'menunggu')
                    <form action="{{ route('bookings.cancel', $pemesanan->id) }}" method="POST" onsubmit="return confirm('Batalkan reservasi?');">
                        @csrf
                        <button type="submit" class="text-[9px] font-black text-red-400 hover:text-red-600 uppercase tracking-[0.2em] transition-colors">Batalkan Reservasi</button>
                    </form>
                @else
                    <div></div>
                @endif
                
                @if($pemesanan->status_pembayaran == 'settlement')
                    <button onclick="window.print()" class="w-full sm:w-auto px-10 py-4 bg-slate-900 text-white font-black text-[10px] uppercase tracking-[0.2em] hover:bg-slate-800 transition-all shadow-2xl shadow-slate-900/20 flex items-center justify-center gap-3">
                        <i data-lucide="printer" class="w-4 h-4"></i> Cetak Struk
                    </button>
                @endif
            </div>
        </div>
    </div>

    <style>
        @media print {
            nav, header, footer, .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; color: black !important; }
            .max-w-lg { max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
            #receipt { 
                box-shadow: none !important; 
                border: none !important; 
                width: 100% !important;
                margin: 0 !important;
            }
            .border-b, .border-t, .border-slate-100 { border-color: #eee !important; }
            .border-dashed { border-style: dashed !important; border-color: #ddd !important; }
            .text-slate-900 { color: black !important; }
            .text-slate-400 { color: #888 !important; }
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
