@extends('layouts.bohot')

@section('title', 'Booking Saya')

@section('content')
<div class="py-32 px-6 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-16 animate__animated animate__fadeInLeft">
            <div>
                <h2 class="text-4xl font-bold mb-4">Riwayat <span class="text-primary italic">Booking</span></h2>
                <p class="text-gray-500">Pantau status reservasi hotel Anda di sini.</p>
            </div>
            <a href="/" class="px-8 py-3 bg-white text-primary font-bold rounded-xl border border-primary/10 shadow-sm hover:shadow-md transition-all flex items-center gap-3">
                <i data-lucide="plus" class="w-5 h-5 text-accent"></i> Booking Baru
            </a>
        </div>

        @if(session('success'))
            <div class="mb-8 p-6 bg-green-50 rounded-2xl text-green-700 font-bold border border-green-100 animate__animated animate__shakeX">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-8">
            @forelse($bookings as $item)
                <div class="bg-white p-8 rounded-3xl shadow-xl hover:shadow-2xl transition-all border border-gray-100 flex flex-col md:flex-row gap-10 items-center group animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.1 }}s">
                    <div class="w-full md:w-64 h-44 rounded-2xl overflow-hidden bg-gray-100">
                        @if($item->kamar->foto)
                            <img src="{{ Storage::url($item->kamar->foto) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 italic text-sm">No Image</div>
                        @endif
                    </div>
                    <div class="flex-1 space-y-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-bold mb-2">Room No. {{ $item->kamar->nomor_kamar }}</h3>
                                <div class="flex gap-4 text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    <span>{{ $item->kamar->tipe_kamar }}</span>
                                    <span>•</span>
                                    <span>{{ $item->total_hari }} Malam</span>
                                </div>
                            </div>
                            <span class="px-5 py-2 rounded-full text-xs font-bold uppercase tracking-widest bg-gray-100 
                                {{ $item->status_pemesanan == 'aktif' ? 'bg-blue-100 text-blue-800' : ($item->status_pemesanan == 'menunggu' ? 'bg-yellow-100 text-yellow-800' : ($item->status_pemesanan == 'selesai' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                                {{ $item->status_pemesanan }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Check In</span>
                                <p class="font-bold text-dark">{{ $item->tanggal_checkin->format('d M Y') }}</p>
                            </div>
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Check Out</span>
                                <p class="font-bold text-dark">{{ $item->tanggal_checkout->format('d M Y') }}</p>
                            </div>
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Bayar</span>
                                <p class="font-bold text-accent">Rp {{ number_format($item->total_biaya, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex items-end justify-end md:col-span-1">
                                @if($item->status_pemesanan == 'menunggu')
                                    <form action="{{ route('bookings.cancel', $item->id) }}" method="POST" onsubmit="return confirm('Batalkan booking ini?')">
                                        @csrf
                                        <button type="submit" class="text-red-500 font-bold hover:text-red-700 flex items-center gap-2 group transition-all">
                                            Batalkan <i data-lucide="x-circle" class="w-5 h-5 group-hover:rotate-90 duration-300"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-20 text-center animate__animated animate__fadeIn">
                    <div class="p-10 bg-white inline-block rounded-full shadow-2xl mb-8">
                        <i data-lucide="box" class="w-16 h-16 text-gray-100"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-300">Belum ada pemesanan.</h3>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
