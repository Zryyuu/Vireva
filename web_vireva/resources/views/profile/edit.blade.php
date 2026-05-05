<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 p-8 md:p-12 shadow-2xl animate__animated animate__fadeInDown">
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                <!-- Profile Avatar -->
                <div class="w-24 h-24 md:w-32 md:h-32 rounded-3xl bg-emerald-500 border-4 border-emerald-400/30 flex items-center justify-center text-white text-4xl md:text-5xl font-black shadow-xl shadow-emerald-500/20">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>

                <!-- Profile Info -->
                <div class="text-center md:text-left space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-widest">
                        <i data-lucide="shield-check" class="w-3 h-3"></i>
                        {{ Auth::user()->role === 'user' ? 'Member Premium' : 'Administrator' }}
                    </div>
                    <h2 class="text-3xl md:text-5xl font-black text-white tracking-tight">
                        {{ Auth::user()->name }}
                    </div>
                    <p class="text-slate-400 text-sm md:text-base font-medium max-w-md">
                        Kelola informasi pribadi, kredensial keamanan, dan preferensi akun Anda di satu tempat yang aman.
                    </p>
                </div>
            </div>
            
            <!-- Decorative Elements -->
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-emerald-500/10 rounded-full blur-[100px] pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-blue-500/10 rounded-full blur-[100px] pointer-events-none"></div>
        </div>
    </x-slot>

    <div class="py-12 space-y-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-12 animate__animated animate__fadeInUp">
            <!-- Profile Information -->
            <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-sm border border-slate-200">
                <div class="max-w-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Password Update -->
            <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-sm border border-slate-200">
                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-sm border border-red-100 bg-red-50/30">
                <div class="max-w-2xl text-red-900">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    @if (session('status') === 'profile-updated')
        <script>
            Swal.fire({
                title: 'Profil Diperbarui!',
                text: 'Informasi akun Anda telah berhasil disimpan dengan aman.',
                icon: 'success',
                confirmButtonColor: '#10b981', // emerald-500
                background: '#ffffff',
                borderRadius: '1.5rem',
                customClass: {
                    popup: 'rounded-[1.5rem]',
                    confirmButton: 'rounded-xl font-bold px-6 py-3'
                }
            });
        </script>
    @endif
</x-app-layout>
