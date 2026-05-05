<x-app-layout>
    <div class="py-12 animate__animated animate__fadeIn">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 no-print">
                <a href="{{ route('bookings.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-emerald-600 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Reservasi Saya
                </a>
            </div>

            @if(session('success'))
                <div class="bg-emerald-50 p-4 rounded-2xl border-l-4 border-emerald-500 flex items-center gap-3 shadow-sm mb-6">
                    <div class="p-2 bg-white rounded-full text-emerald-600 shadow-sm border border-emerald-100">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                    </div>
                    <div class="text-sm font-bold text-emerald-700">{{ session('success') }}</div>
                </div>
            @endif

            <!-- E-Ticket Main Container -->
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl border border-slate-200 relative mb-8">
                
                <!-- Ticket Header -->
                <div class="bg-slate-900 px-8 py-8 md:p-10 text-white relative overflow-hidden">
                    <div class="absolute -right-16 -top-16 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl"></div>
                    <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-emerald-600/20 rounded-full blur-3xl"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border-b border-white/10 pb-8 mb-8">
                        <div>
                            <div class="text-[10px] uppercase tracking-widest text-emerald-400 font-bold mb-1">E-Resi & Tiket Menginap</div>
                            <h1 class="text-3xl lg:text-4xl font-black tracking-tight mb-2">#VRV-{{ str_pad($pemesanan->id, 5, '0', STR_PAD_LEFT) }}</h1>
                            <div class="flex items-center gap-2 text-sm text-slate-400">
                                <i data-lucide="clock" class="w-4 h-4"></i> Diterbitkan {{ $pemesanan->created_at->format('d M Y, H:i') }}
                            </div>
                        </div>
                        <div class="text-right flex flex-col md:items-end">
                            @if($pemesanan->status_pembayaran == 'settlement')
                                <div class="bg-emerald-500 text-white px-4 py-2 rounded-xl border border-emerald-400 font-bold text-sm tracking-wide shadow-lg shadow-emerald-500/30 inline-flex items-center gap-2">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i> LUNAS
                                </div>
                            @elseif($pemesanan->status_pembayaran == 'pending')
                                <div class="bg-amber-500 text-white px-4 py-2 rounded-xl border border-amber-400 font-bold text-sm tracking-wide shadow-lg shadow-amber-500/30 inline-flex items-center gap-2">
                                    <i data-lucide="clock" class="w-4 h-4"></i> MENUNGGU PEMBAYARAN
                                </div>
                            @else
                                <div class="bg-red-500 text-white px-4 py-2 rounded-xl border border-red-400 font-bold text-sm tracking-wide shadow-lg shadow-red-500/30 inline-flex items-center gap-2">
                                    <i data-lucide="x-circle" class="w-4 h-4"></i> DIBATALKAN
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="relative z-10 grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1.5">Check-In</div>
                            <div class="text-lg font-bold">{{ $pemesanan->tanggal_checkin->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1.5">Check-Out</div>
                            <div class="text-lg font-bold">{{ $pemesanan->tanggal_checkout->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1.5">Unit Villa</div>
                            <div class="text-lg font-bold">{{ $pemesanan->villa->nama_villa }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1.5">Status Menginap</div>
                            <div class="text-lg font-bold uppercase">{{ $pemesanan->status_pemesanan }}</div>
                        </div>
                    </div>
                </div>

                <!-- Perforated Line -->
                <div class="relative h-8 bg-slate-50">
                    <div class="absolute -left-4 top-0 w-8 h-8 rounded-full bg-slate-50 border-r border-slate-200"></div>
                    <div class="absolute left-8 right-8 top-1/2 border-t-2 border-dashed border-slate-300"></div>
                    <div class="absolute -right-4 top-0 w-8 h-8 rounded-full bg-slate-50 border-l border-slate-200"></div>
                </div>

                <!-- Payment Info -->
                <div class="p-8 md:p-10 bg-slate-50">
                    <div class="grid md:grid-cols-2 gap-10">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-6">Instruksi Pembayaran</h3>
                            <div class="bg-white p-6 rounded-2xl border border-slate-200 space-y-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 font-black">BCA</div>
                                    <div>
                                        <div class="text-xs text-slate-500 font-bold uppercase">Nomor Rekening</div>
                                        <div class="text-lg font-black text-slate-900">123456789</div>
                                    </div>
                                </div>
                                <div class="text-sm text-slate-600">
                                    A/N <b>Vireva Villa Management</b><br>
                                    Total: <b class="text-emerald-600 text-lg">Rp {{ number_format($pemesanan->total_biaya, 0, ',', '.') }}</b>
                                </div>
                                <div class="text-[10px] text-slate-400 italic">
                                    *Harap transfer sesuai nominal hingga digit terakhir.
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-6">Upload Bukti Transfer</h3>
                            
                            @if($pemesanan->status_pembayaran == 'pending')
                                @if($pemesanan->bukti_pembayaran)
                                    <div class="bg-blue-50 p-4 rounded-2xl border border-blue-200 flex items-start gap-3">
                                        <i data-lucide="info" class="w-5 h-5 text-blue-600 shrink-0"></i>
                                        <div class="text-sm text-blue-800">
                                            <b>Bukti sudah diupload!</b><br>
                                            Menunggu verifikasi admin. Anda bisa mengupload ulang jika salah.
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <img src="{{ asset('storage/' . $pemesanan->bukti_pembayaran) }}" class="w-32 h-32 object-cover rounded-xl border border-slate-200">
                                    </div>
                                @endif

                                <form action="{{ route('bookings.upload-proof', $pemesanan->id) }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                                    @csrf
                                    <input type="file" name="bukti_pembayaran" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                    <button type="submit" class="w-full py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-slate-800 transition-all">Upload Bukti</button>
                                </form>
                            @else
                                <div class="bg-emerald-50 p-6 rounded-2xl border border-emerald-200 text-center">
                                    <i data-lucide="check-circle" class="w-12 h-12 text-emerald-600 mx-auto mb-3"></i>
                                    <div class="text-emerald-800 font-bold">Pembayaran Terverifikasi</div>
                                    <div class="text-xs text-emerald-600">Terima kasih telah melakukan pembayaran.</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Actions -->
            <div class="flex justify-between items-center no-print">
                @if($pemesanan->status_pemesanan == 'menunggu')
                    <form action="{{ route('bookings.cancel', $pemesanan->id) }}" method="POST" onsubmit="return confirm('Batalkan reservasi?');">
                        @csrf
                        <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-700 transition-colors">Batalkan Reservasi</button>
                    </form>
                @endif
                <button onclick="window.print()" class="px-6 py-3 bg-white border border-slate-200 rounded-xl font-bold text-slate-900 shadow-sm hover:bg-slate-50 transition-all flex items-center gap-2">
                    <i data-lucide="printer" class="w-4 h-4"></i> Cetak E-Tiket
                </button>
            </div>

            <style>
                @media print {
                    nav, header, footer, .no-print, button, form { display: none !important; }
                    body { background: white !important; padding: 0 !important; }
                    .max-w-4xl { max-width: 100% !important; margin: 0 !important; }
                    .shadow-xl { shadow: none !important; }
                    .rounded-\[2rem\] { border-radius: 12px !important; }
                }
            </style>
        </div>
    </div>
</x-app-layout>
