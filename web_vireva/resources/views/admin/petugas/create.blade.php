<x-admin-layout>
    <div class="space-y-6 sm:space-y-8 animate__animated animate__fadeIn">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <a href="{{ route('admin.petugas.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-emerald-600 transition-colors mb-2">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar Petugas
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 mb-1">Tambah Petugas Baru</h1>
                <p class="text-sm text-slate-500 font-medium">Beri hak akses kepada manajer atau staf baru untuk mengelola sistem Vireva.</p>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm border border-slate-200 sm:rounded-3xl">
            <form action="{{ route('admin.petugas.store') }}" method="POST" class="p-6 sm:p-8">
                @csrf
                
                <div class="border-b border-slate-100 pb-6 mb-8">
                    <h3 class="text-lg font-bold text-slate-900 mb-1">Informasi Dasar</h3>
                    <p class="text-sm text-slate-500">Profil yang akan ditampilkan dalam sistem operasi.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <x-input-label for="nama_petugas" value="Nama Lengkap Staf" class="text-slate-700 font-bold" />
                        <x-text-input id="nama_petugas" class="block mt-2 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="text" name="nama_petugas" :value="old('nama_petugas')" placeholder="Misal: Budi Santoso" required autofocus />
                        <x-input-error :messages="$errors->get('nama_petugas')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="jabatan" value="Jabatan / Peran" class="text-slate-700 font-bold" />
                        <select id="jabatan" name="jabatan" class="border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm block mt-2 w-full">
                            <option value="Front Desk Concierge">Resepsionis / Front Desk</option>
                            <option value="Villa Manager">Manajer Villa</option>
                            <option value="Marketing Staff">Staf Pemasaran</option>
                            <option value="Finance & Admin">Keuangan & Admin</option>
                        </select>
                        <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />
                    </div>
                </div>

                <div class="border-b border-slate-100 pb-6 mb-8 mt-12">
                    <h3 class="text-lg font-bold text-slate-900 mb-1">Kredensial Akses</h3>
                    <p class="text-sm text-slate-500">Email dan kata sandi untuk otoritas sistem manajemen.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2">
                        <x-input-label for="email" value="Alamat Email Utama" class="text-slate-700 font-bold" />
                        <x-text-input id="email" class="block mt-2 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="email" name="email" :value="old('email')" placeholder="budi@vireva.com" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password" value="Kata Sandi Akses" class="text-slate-700 font-bold" />
                        <x-text-input id="password" class="block mt-2 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="password" name="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" class="text-slate-700 font-bold" />
                        <x-text-input id="password_confirmation" class="block mt-2 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="password" name="password_confirmation" required />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-12 flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                    <a href="{{ route('admin.petugas.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-colors">Batalkan</a>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-colors shadow-md shadow-emerald-500/20">
                        Daftarkan Akses Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
