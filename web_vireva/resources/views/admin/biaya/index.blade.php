<x-admin-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 animate__animated animate__fadeIn">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Catatan <span class="text-emerald-600">Biaya Operasional</span></h1>
                <p class="text-slate-500 text-sm mt-1 font-medium">Manajemen pengeluaran untuk perhitungan laba rugi villa.</p>
            </div>
            <a href="{{ route('admin.biaya.create') }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-emerald-600 text-white px-6 py-3 rounded-xl font-bold text-sm transition-all shadow-lg">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Pengeluaran
            </a>
        </div>

        <!-- Expense Table Card -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-slate-500">Tanggal</th>
                            <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-slate-500">Item / Deskripsi</th>
                            <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-slate-500">Kategori</th>
                            <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-slate-500 text-right">Jumlah</th>
                            <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-slate-500 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($biayas as $biaya)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="text-slate-900 font-bold">{{ $biaya->tanggal->format('d M Y') }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="font-bold text-slate-900">{{ $biaya->item_biaya }}</div>
                                    <div class="text-xs text-slate-400 font-medium truncate max-w-[200px]">{{ $biaya->keterangan ?? '-' }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider rounded-full border border-slate-200">
                                        {{ $biaya->kategori }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right font-black text-red-600">
                                    Rp {{ number_format($biaya->jumlah, 0, ',', '.') }}
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex justify-center items-center gap-3">
                                        <form action="{{ route('admin.biaya.destroy', $biaya->id) }}" method="POST" onsubmit="return confirm('Hapus catatan biaya ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 transition-colors">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                            <i data-lucide="receipt" class="w-10 h-10 text-slate-200"></i>
                                        </div>
                                        <p class="font-bold text-slate-900">Belum ada catatan pengeluaran.</p>
                                        <p class="text-sm mt-1">Mulai catat biaya operasional Anda untuk melihat performa laba rugi.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($biayas->hasPages())
                <div class="px-8 py-6 bg-slate-50 border-t border-slate-100">
                    {{ $biayas->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
