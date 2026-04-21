<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Vireva') }} | Authentication</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/lucide@latest"></script>
        
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            [x-cloak] { display: none !important; }
            .bg-auth-mesh {
                background: radial-gradient(at 0% 0%, hsla(158, 64%, 90%, 1) 0px, transparent 50%),
                            radial-gradient(at 100% 0%, hsla(210, 40%, 96%, 1) 0px, transparent 50%),
                            radial-gradient(at 100% 100%, hsla(158, 64%, 90%, 1) 0px, transparent 50%),
                            radial-gradient(at 0% 100%, hsla(210, 40%, 96%, 1) 0px, transparent 50%);
            }
        </style>
    </head>
    <body class="antialiased bg-slate-50 text-slate-900 selection:bg-emerald-200">
        <div class="min-h-screen flex flex-col items-center justify-center p-6 bg-auth-mesh relative overflow-hidden">
            <!-- Logo/Branding Header -->
            <div class="mb-8 animate__animated animate__fadeInDown">
                <a href="/" class="text-3xl font-black tracking-tighter text-slate-900">
                    VIREVA<span class="text-emerald-600">.</span>
                </a>
            </div>
            
            {{ $slot }}
        </div>
        
        <script>
            lucide.createIcons();
        </script>
    </body>
</html>
