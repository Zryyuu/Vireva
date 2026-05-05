<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Pusat Manajemen - {{ config('app.name', 'Vireva') }}</title>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <script src="https://unpkg.com/lucide@latest"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { 
                font-family: 'Plus Jakarta Sans', sans-serif; 
                background-color: #F8FAFC; /* Slate 50 */
                color: #0F172A; /* Slate 900 */
            }
            .bg-light-pattern {
                background-image: radial-gradient(#E2E8F0 1px, transparent 1px);
                background-size: 32px 32px;
            }
            .white-card {
                background: #FFFFFF;
                border: 1px solid #F1F5F9;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            }
            .sidebar-link {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.75rem 1rem;
                border-radius: 0.75rem;
                color: #64748B; /* Slate 500 */
                font-weight: 600;
                font-size: 0.875rem;
                transition: all 0.3s ease;
            }
            .sidebar-link:hover {
                background-color: #F1F5F9; /* Slate 100 */
                color: #0F172A;
            }
            .sidebar-link.active {
                background-color: #0F172A; 
                color: #FFFFFF;
                box-shadow: 0 4px 15px rgba(15, 23, 42, 0.15);
            }
            /* Custom Scrollbar */
            .custom-scrollbar::-webkit-scrollbar { width: 6px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
            /* Custom Form Select Styling */
            .form-select {
                appearance: none;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
                background-position: right 0.5rem center;
                background-repeat: no-repeat;
                background-size: 1.5em 1.5em;
                padding-right: 2.5rem;
                transition: all 0.2s ease;
            }
            .form-select:hover {
                border-color: #059669; /* emerald-600 */
            }
            .form-select:focus {
                outline: none;
                border-color: #059669;
                ring: 2px;
                ring-color: rgba(5, 150, 105, 0.2);
            }
        </style>
    </head>
    <body class="antialiased selection:bg-slate-200">
        <div class="flex min-h-screen bg-light-pattern bg-slate-50" x-data="{ sidebarOpen: false }">
            
            <!-- Mobile Sidebar Overlay -->
            <div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false" style="display: none;"></div>

            <!-- Sidebar -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-72 min-h-screen transition-transform duration-300 lg:sticky lg:top-0 lg:translate-x-0 flex flex-col bg-white border-r border-slate-200 relative">
                
                <!-- Logo Area -->
                <div class="h-20 flex items-center px-8 border-b border-slate-100 shrink-0">
                    <a href="{{ route('admin.dashboard') }}" class="text-2xl font-black tracking-tighter text-slate-900">
                        VIREVA<span class="text-emerald-600">.</span> <span class="text-[10px] font-bold text-slate-400 tracking-widest uppercase ml-2 px-2 py-1 border border-slate-200 rounded-full bg-slate-50">Admin</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 overflow-y-auto py-6 px-4 pb-32 space-y-1 custom-scrollbar">
                    
                    <div class="text-[10px] uppercase font-bold tracking-widest text-slate-400 px-4 mb-2 mt-2">Utama</div>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dasbor
                    </a>
                    <a href="{{ route('admin.villa.index') }}" class="sidebar-link {{ request()->routeIs('admin.villa.*') ? 'active' : '' }}">
                        <i data-lucide="home" class="w-5 h-5"></i> Kelola Villa
                    </a>

                    @if(Auth::user()->isSuperAdmin())
                    <div class="text-[10px] uppercase font-bold tracking-widest text-slate-400 px-4 mt-8 mb-2">Super Admin</div>
                    <a href="{{ route('admin.petugas.index') }}" class="sidebar-link {{ request()->routeIs('admin.petugas.*') ? 'active' : '' }}">
                        <i data-lucide="shield-check" class="w-5 h-5"></i> Kelola Staff
                    </a>
                    <a href="{{ route('admin.tamu.index') }}" class="sidebar-link {{ request()->routeIs('admin.tamu.*') ? 'active' : '' }}">
                        <i data-lucide="users" class="w-5 h-5"></i> Lihat Tamu
                    </a>
                    @endif

                    <div class="text-[10px] uppercase font-bold tracking-widest text-slate-400 px-4 mt-8 mb-2">Manajemen Keuangan</div>
                    <a href="{{ route('admin.transaksi.index') }}" class="sidebar-link {{ request()->routeIs('admin.transaksi.*') ? 'active' : '' }}">
                        <i data-lucide="arrow-down-left" class="w-5 h-5 text-emerald-500"></i> Transaksi (In)
                    </a>
                    @if(Auth::user()->isSuperAdmin())
                    <a href="{{ route('admin.biaya.index') }}" class="sidebar-link {{ request()->routeIs('admin.biaya.*') ? 'active' : '' }}">
                        <i data-lucide="arrow-up-right" class="w-5 h-5 text-red-500"></i> Catatan Biaya (Out)
                    </a>
                    <a href="{{ route('admin.laporan.index') }}" class="sidebar-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                        <i data-lucide="bar-chart-3" class="w-5 h-5 text-blue-500"></i> Laporan Keuangan
                    </a>
                    @endif

                    <div class="text-[10px] uppercase font-bold tracking-widest text-slate-400 px-4 mt-8 mb-2">Operasional</div>
                    <a href="{{ route('admin.reservasi.index') }}" class="sidebar-link {{ request()->routeIs('admin.reservasi.*') ? 'active' : '' }}">
                        <i data-lucide="calendar-clock" class="w-5 h-5"></i> Reservasi
                        <span class="ml-auto bg-emerald-100 text-emerald-700 border border-emerald-200 text-[10px] font-bold px-2 py-0.5 rounded-full">Live</span>
                    </a>
                </nav>

                <!-- Admin Profile Area -->
                <div class="absolute bottom-0 left-0 w-full shrink-0 p-4 border-t border-slate-100 bg-slate-50/50">
                    <div class="white-card p-4 rounded-xl flex items-center gap-3 relative group hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 rounded-lg bg-slate-900 flex items-center justify-center text-white font-black">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <div class="text-sm font-bold truncate text-slate-900">{{ Auth::user()->name ?? 'Administrator' }}</div>
                            <div class="text-xs text-slate-500 truncate">{{ Auth::user()->email ?? 'admin@vireva.com' }}</div>
                        </div>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-colors cursor-pointer" title="Keluar">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1 flex flex-col h-screen overflow-hidden">
                <!-- Mobile Topbar Only -->
                <div class="lg:hidden h-16 border-b border-slate-200 flex items-center px-4 justify-between bg-white shrink-0 shadow-sm">
                    <a href="{{ route('admin.dashboard') }}" class="text-xl font-black tracking-tighter text-slate-900">
                        VIREVA<span class="text-emerald-600">.</span>
                    </a>
                    <button @click="sidebarOpen = true" class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 hover:text-slate-900 hover:bg-slate-200 transition-colors">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                </div>

                <!-- Page Content (Scrollable) -->
                <div class="flex-1 overflow-y-auto p-4 md:p-8 custom-scrollbar relative">
                    {{ $slot }}
                </div>
            </main>
        </div>
        
        <script>
            // Initialize Lucide icons
            document.addEventListener('DOMContentLoaded', () => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
            // Re-initialize when alpine mutates dom
            document.addEventListener('alpine:initialized', () => {
                lucide.createIcons();
            });
        </script>
    </body>
</html>
