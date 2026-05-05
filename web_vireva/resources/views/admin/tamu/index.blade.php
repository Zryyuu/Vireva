<x-admin-layout>
    <div class="space-y-6 sm:space-y-8 animate__animated animate__fadeIn">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 mb-1">Database <span class="text-emerald-600">Pelanggan</span></h1>
                <p class="text-sm text-slate-500 font-medium">Monitoring seluruh tamu yang terdaftar di sistem manajemen Vireva.</p>
            </div>
            <div class="bg-emerald-50 px-4 py-2 rounded-2xl border border-emerald-100">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-widest">Total: {{ $tamu->total() }} Tamu</span>
            </div>
        </div>

        <!-- Guest Table Card -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-500">Identitas Tamu</th>
                            <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-500">Kontak</th>
                            <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-500">Identitas Resmi (KTP/Lainnya)</th>
                            <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-500">Alamat Tinggal</th>
                            <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-slate-500">Bergabung</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($tamu as $t)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center text-white font-black shrink-0 shadow-sm transition-transform group-hover:scale-110">
                                            {{ substr($t->nama_tamu, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900">{{ $t->nama_tamu }}</div>
                                            <div class="text-[10px] font-bold text-emerald-600 uppercase tracking-tighter">UID: #{{ str_pad($t->id, 5, '0', STR_PAD_LEFT) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2 text-xs font-medium text-slate-700">
                                            <i data-lucide="mail" class="w-3.5 h-3.5 text-slate-400"></i>
                                            {{ $t->user->email ?? '-' }}
                                        </div>
                                        <div class="flex items-center gap-2 text-xs font-medium text-slate-700">
                                            <i data-lucide="phone" class="w-3.5 h-3.5 text-slate-400"></i>
                                            @if($t->no_hape)
                                                {{ $t->no_hape }}
                                            @else
                                                <span class="text-[10px] italic text-slate-400 font-normal">Belum Dilengkapi</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    @if($t->no_identitas)
                                        <span class="px-3 py-1.5 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold border border-slate-200">
                                            {{ $t->no_identitas }}
                                        </span>
                                    @else
                                        <span class="text-[10px] italic text-slate-400 font-normal">Belum Dilengkapi</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5">
                                    @if($t->alamat)
                                        <p class="text-xs text-slate-600 font-medium max-w-[200px] truncate" title="{{ $t->alamat }}">{{ $t->alamat }}</p>
                                    @else
                                        <span class="text-[10px] italic text-slate-400 font-normal">Belum Dilengkapi</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-xs font-bold text-slate-900">{{ $t->created_at->format('d M Y') }}</div>
                                    <div class="text-[10px] text-slate-500 uppercase tracking-tight">{{ $t->created_at->diffForHumans() }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center text-slate-400">
                                    <i data-lucide="users" class="w-12 h-12 mx-auto mb-4 opacity-20"></i>
                                    <p class="font-medium">Belum ada data tamu terdaftar.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($tamu->hasPages())
                <div class="px-8 py-4 bg-slate-50 border-t border-slate-100">
                    {{ $tamu->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
