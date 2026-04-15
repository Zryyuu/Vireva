<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Statistics Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-primary">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i data-lucide="door-open" class="text-primary w-6 h-6"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 uppercase">Total Kamar</p>
                            <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Kamar::count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-full">
                            <i data-lucide="check-circle" class="text-green-500 w-6 h-6"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 uppercase">Booking Aktif</p>
                            <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Pemesanan::where('status_pemesanan', 'aktif')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-accent">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <i data-lucide="wallet" class="text-accent w-6 h-6"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 uppercase">Total Pendapatan</p>
                            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format(\App\Models\Pemesanan::where('status_pemesanan', 'selesai')->sum('total_biaya'), 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Aksi Cepat</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('admin.kamar.index') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-xl hover:bg-primary hover:text-white transition-all group">
                            <i data-lucide="layout-grid" class="w-8 h-8 mb-2 text-primary group-hover:text-white"></i>
                            <span class="text-sm font-medium">Kelola Kamar</span>
                        </a>
                        <a href="{{ route('admin.kamar.create') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-xl hover:bg-primary hover:text-white transition-all group">
                            <i data-lucide="plus-circle" class="w-8 h-8 mb-2 text-primary group-hover:text-white"></i>
                            <span class="text-sm font-medium">Tambah Kamar</span>
                        </a>
                        <!-- Add more actions as needed -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</x-app-layout>
