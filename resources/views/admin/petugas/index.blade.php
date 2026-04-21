<x-admin-layout>
    <div class="space-y-6 sm:space-y-8 animate__animated animate__fadeIn">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 mb-1">Manajemen Petugas</h1>
                <p class="text-sm text-slate-500 font-medium">Kelola akun administrator dan staf untuk sistem Vireva.</p>
            </div>
            <a href="{{ route('admin.petugas.create') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 px-5 py-2.5 rounded-xl font-bold text-sm text-white transition-all transform hover:-translate-y-1 shadow-md shadow-emerald-500/20">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                <span>Tambah Petugas</span>
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 p-4 rounded-2xl border-l-4 border-emerald-500 flex items-center gap-3 shadow-sm">
                <div class="p-2 bg-white rounded-full text-emerald-600 shadow-sm border border-emerald-100">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
                <div class="text-sm font-bold text-emerald-700">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 p-4 rounded-2xl border-l-4 border-red-500 flex items-center gap-3 shadow-sm">
                <div class="p-2 bg-white rounded-full text-red-600 shadow-sm border border-red-100">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                </div>
                <div class="text-sm font-bold text-red-700">{{ session('error') }}</div>
            </div>
        @endif

        <div class="bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 bg-slate-50 uppercase tracking-widest border-b border-slate-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold">Identitas Staf</th>
                            <th scope="col" class="px-6 py-4 font-bold">Jabatan</th>
                            <th scope="col" class="px-6 py-4 font-bold">Hak Akses</th>
                            <th scope="col" class="px-6 py-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center text-white font-black shrink-0 shadow-sm">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 text-base mb-0.5">{{ $user->name }}</div>
                                        <div class="text-slate-500 text-xs">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-700">
                                    {{ $user->petugas ? $user->petugas->jabatan : 'Administrator Sistem' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->id === 1 || $user->email === 'admin@gmail.com')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200 shadow-sm">
                                        <i data-lucide="shield-alert" class="w-3.5 h-3.5"></i> Super Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 shadow-sm">
                                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> Staff Admin
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.petugas.edit', $user->id) }}" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit Staf">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    
                                    @if($user->id !== 1 && $user->email !== 'admin@gmail.com' && auth()->id() !== $user->id)
                                    <form action="{{ route('admin.petugas.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus petugas ini? Hak akses mereka ke Dasbor akan dicabut secara permanen.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Staf">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mb-4">
                                        <i data-lucide="users" class="w-8 h-8 text-slate-300"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-600">Belum ada akun petugas.</p>
                                    <p class="text-xs mt-1">Tambahkan akun melalui tombol Tambah Petugas.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                <p class="text-xs text-slate-500 font-medium">
                    * Akun <span class="font-bold text-slate-700">Super Admin</span> membawa hak absolut dan tidak dapat dihapus secara sistem.
                </p>
            </div>
        </div>
    </div>
</x-admin-layout>
