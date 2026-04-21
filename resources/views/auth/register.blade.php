<x-guest-layout>
    <div class="w-full max-w-lg bg-white p-8 md:p-12 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 space-y-8 animate__animated animate__fadeInUp relative z-10 my-10">
        <div class="text-center">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Bergabung Sekarang</h1>
            <p class="text-slate-500 mt-3 font-medium text-sm">Dapatkan akses eksklusif ke layanan premium kami.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div class="space-y-2">
                <label for="name" class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Nama Lengkap</label>
                <div class="relative group">
                    <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-emerald-600 transition-colors"></i>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                        class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 pl-12 pr-4 text-slate-900 placeholder:text-slate-300 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-medium text-sm">
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Email Address -->
                <div class="space-y-2">
                    <label for="email" class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Email</label>
                    <div class="relative group">
                        <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-emerald-600 transition-colors"></i>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 pl-12 pr-4 text-slate-900 placeholder:text-slate-300 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-medium text-sm">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label for="password" class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Kata Sandi</label>
                    <div class="relative group">
                        <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-emerald-600 transition-colors"></i>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 pl-12 pr-4 text-slate-900 placeholder:text-slate-300 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-medium text-sm">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <label for="password_confirmation" class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Konfirmasi Kata Sandi</label>
                <div class="relative group">
                    <i data-lucide="check" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-emerald-600 transition-colors"></i>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 pl-12 pr-4 text-slate-900 placeholder:text-slate-300 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-medium text-sm">
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-slate-900 hover:bg-emerald-600 text-white font-bold py-4 rounded-2xl shadow-xl shadow-slate-900/10 hover:shadow-emerald-500/20 transition-all duration-300 transform active:scale-[0.98]">
                    Daftar Akun
                </button>
                
                <p class="text-center font-medium text-slate-400 text-[13px] mt-8">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-emerald-600 font-bold hover:text-emerald-700 transition-colors ml-1 underline underline-offset-4">Masuk</a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
