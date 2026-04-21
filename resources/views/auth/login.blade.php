<x-guest-layout>
    <div class="w-full max-w-md bg-white p-8 md:p-12 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 space-y-8 animate__animated animate__fadeInUp relative z-10">
        <div class="text-center">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Selamat Datang</h1>
            <p class="text-slate-500 mt-3 font-medium text-sm">Masuk untuk mengelola reservasi Anda.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email" class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Email</label>
                <div class="relative group">
                    <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-emerald-600 transition-colors"></i>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                        class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 pl-12 pr-4 text-slate-900 placeholder:text-slate-300 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-medium text-sm">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <label for="password" class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Kata Sandi</label>
                <div class="relative group">
                    <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-emerald-600 transition-colors"></i>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 pl-12 pr-4 text-slate-900 placeholder:text-slate-300 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-medium text-sm">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between ml-1">
                <label for="remember_me" class="flex items-center cursor-pointer group">
                    <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-slate-200 text-emerald-600 focus:ring-emerald-500 transition-colors" name="remember">
                    <span class="ms-3 text-[12px] font-medium text-slate-500 group-hover:text-slate-900 transition-colors">Ingat saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-[11px] font-bold text-emerald-600 hover:text-emerald-700 transition-colors" href="{{ route('password.request') }}">
                        Lupa sandi?
                    </a>
                @endif
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-slate-900 hover:bg-emerald-600 text-white font-bold py-4 rounded-2xl shadow-xl shadow-slate-900/10 hover:shadow-emerald-500/20 transition-all duration-300 transform active:scale-[0.98]">
                    Masuk Sekarang
                </button>
                
                <p class="text-center font-medium text-slate-400 text-[13px] mt-8">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-emerald-600 font-bold hover:text-emerald-700 transition-colors ml-1 underline underline-offset-4">Daftar</a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
