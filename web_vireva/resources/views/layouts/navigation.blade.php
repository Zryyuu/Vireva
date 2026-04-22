<nav x-data="{ open: false }" class="glass sticky top-0 z-50 border-b border-slate-200 mx-4 mt-4 rounded-2xl bg-white/80">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="text-xl font-bold tracking-tighter text-slate-900 hover:text-emerald-700 transition-colors">
                        VIREVA<span class="text-emerald-600">.</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex items-center">
                    <x-nav-link :href="Auth::user()->role === 'admin' ? route('admin.dashboard') : route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('admin.dashboard')" class="text-xs uppercase tracking-widest font-bold {{ request()->routeIs('dashboard') ? 'text-emerald-600 border-b-2 border-emerald-500' : 'text-slate-500 hover:text-slate-900 focus:text-slate-900' }}">
                        {{ __('Beranda') }}
                    </x-nav-link>
                    
                    @if(Auth::user()->role === 'admin')
                        <!-- Admin doesn't use this topbar layout natively, but if they fall here, just direct to dashboard -->
                        <x-nav-link href="{{ route('admin.dashboard') }}" :active="false" class="text-xs uppercase tracking-widest font-bold text-slate-500 hover:text-slate-900">
                            Masuk Dasbor
                        </x-nav-link>
                    @else
                        <x-nav-link href="{{ route('bookings.explore') }}" :active="request()->routeIs('bookings.explore')" class="text-xs uppercase tracking-widest font-bold {{ request()->routeIs('bookings.explore') ? 'text-emerald-600 border-b-2 border-emerald-500' : 'text-slate-500 hover:text-slate-900 focus:text-slate-900' }}">
                            Cari Villa
                        </x-nav-link>
                        <x-nav-link href="{{ route('bookings.index') }}" :active="request()->routeIs('bookings.index')" class="text-xs uppercase tracking-widest font-bold {{ request()->routeIs('bookings.index') ? 'text-emerald-600 border-b-2 border-emerald-500' : 'text-slate-500 hover:text-slate-900 focus:text-slate-900' }}">
                            Booking Saya
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 border border-slate-200 text-sm font-bold rounded-xl text-slate-600 bg-white hover:bg-slate-50 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center text-[10px] text-emerald-800 font-bold">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    {{ Auth::user()->name }}
                                </div>

                                <div class="ms-2 text-slate-400">
                                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-xl">
                                <x-dropdown-link :href="route('profile.edit')" class="hover:bg-slate-50 text-slate-700 font-medium">
                                    {{ __('Profil Saya') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();"
                                            class="hover:bg-red-50 text-red-600 font-medium pb-2">
                                        {{ __('Keluar Akun') }}
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 focus:outline-none transition duration-150 ease-in-out border border-transparent shadow-sm hover:border-slate-200">
                    <i :class="{'hidden': open, 'block': ! open }" data-lucide="menu" class="w-6 h-6"></i>
                    <i :class="{'hidden': ! open, 'block': open }" data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-slate-100 rounded-b-2xl overflow-hidden mt-1 shadow-lg">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="Auth::user()->role === 'admin' ? route('admin.dashboard') : route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('admin.dashboard')" class="rounded-xl font-bold">
                {{ __('Beranda') }}
            </x-responsive-nav-link>
            
            @if(Auth::user()->role !== 'admin')
                <x-responsive-nav-link :href="route('bookings.explore')" :active="request()->routeIs('bookings.explore')" class="rounded-xl font-bold">
                    Cari Villa
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.index')" class="rounded-xl font-bold">
                    Booking Saya
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-slate-100 px-4 mb-4 bg-slate-50">
            <div class="flex items-center gap-3 px-2 mb-4">
                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-800 font-bold border border-emerald-200">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-bold text-slate-900">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-slate-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="rounded-xl font-bold text-slate-700">
                    {{ __('Profil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="rounded-xl font-bold text-red-600 bg-red-50 mt-2 border border-red-100">
                        {{ __('Keluar') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
