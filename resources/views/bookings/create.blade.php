@extends('layouts.bohot')

@section('title', 'Booking Kamar ' . $kamar->nomor_kamar)

@section('content')
<div class="py-32 px-6 bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto">
        <div class="mb-12 animate__animated animate__fadeInLeft">
            <h2 class="text-4xl font-bold mb-4">Konfirmasi <span class="text-primary italic">Reservasi</span></h2>
            <p class="text-gray-500">Lengkapi detail pemesanan Anda untuk kamar nomor {{ $kamar->nomor_kamar }} ({{ $kamar->tipe_kamar }}).</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Form Section -->
            <div class="lg:col-span-2 bg-white p-10 rounded-3xl shadow-xl animate__animated animate__fadeInUp">
                <form action="{{ route('bookings.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kamar_id" value="{{ $kamar->id }}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                        <div class="space-y-3">
                            <label class="block text-sm font-bold text-gray-700 uppercase tracking-widest" for="tanggal_checkin">Tanggal Check-in</label>
                            <div class="relative">
                                <i data-lucide="calendar" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-accent"></i>
                                <input type="date" id="tanggal_checkin" name="tanggal_checkin" class="w-full pl-12 p-4 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-gray-700 font-bold" min="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-sm font-bold text-gray-700 uppercase tracking-widest" for="tanggal_checkout">Tanggal Check-out</label>
                            <div class="relative">
                                <i data-lucide="calendar" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-accent"></i>
                                <input type="date" id="tanggal_checkout" name="tanggal_checkout" class="w-full pl-12 p-4 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-gray-700 font-bold" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-8 rounded-2xl border border-gray-100 mb-10">
                        <h4 class="font-bold mb-6 text-primary uppercase text-xs tracking-[0.2em]">Ringkasan Biaya</h4>
                        <div class="flex justify-between items-center text-lg font-bold">
                            <span class="text-gray-400">Harga Per Malam</span>
                            <span class="text-dark">Rp {{ number_format($kamar->harga_permalam, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-accent hover:bg-accent-hover text-primary p-5 rounded-2xl font-bold text-lg shadow-xl transform hover:-translate-y-1 transition-all flex justify-center items-center gap-3">
                        Lanjutkan Pembayaran <i data-lucide="shield-check" class="w-6 h-6"></i>
                    </button>
                    <p class="mt-6 text-center text-xs text-gray-400 font-medium">Pembatalan dapat dilakukan sesuai dengan kebijakan hotel kami.</p>
                </form>
            </div>

            <!-- Room Info Sidebar -->
            <div class="lg:col-span-1 space-y-8 animate__animated animate__fadeInRight">
                <div class="bg-white rounded-3xl overflow-hidden shadow-xl aspect-square relative">
                    @if($kamar->foto)
                        <img src="{{ Storage::url($kamar->foto) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center italic text-gray-400">No Image</div>
                    @endif
                </div>
                <div class="bg-primary p-10 rounded-3xl text-white shadow-2xl relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
                    <h3 class="text-2xl font-bold mb-6">Detail Room</h3>
                    <ul class="space-y-4">
                        <li class="flex items-center gap-4 text-white/70">
                            <i data-lucide="info" class="w-5 h-5 text-accent"></i> <span>No. {{ $kamar->nomor_kamar }}</span>
                        </li>
                        <li class="flex items-center gap-4 text-white/70">
                            <i data-lucide="layers" class="w-5 h-5 text-accent"></i> <span>{{ $kamar->tipe_kamar }} Type</span>
                        </li>
                        <li class="flex items-center gap-4 text-white/70">
                            <i data-lucide="users" class="w-5 h-5 text-accent"></i> <span>{{ $kamar->kapasitas }} Kapasitas Max</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
