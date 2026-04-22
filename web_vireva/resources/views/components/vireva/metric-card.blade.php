@props([
    'title',
    'value',
    'icon' => 'activity',
    'color' => 'slate',
    'subtitle' => null,
    'trend' => null,
    'isCurrency' => false
])

@php
    $colorClasses = [
        'slate' => 'hover:border-slate-300',
        'emerald' => 'hover:border-emerald-300',
        'red' => 'hover:border-red-300',
        'blue' => 'hover:border-blue-300',
        'orange' => 'hover:border-orange-300',
    ];
    
    $valueColor = [
        'slate' => 'text-slate-900',
        'emerald' => 'text-emerald-600',
        'red' => 'text-red-600',
        'blue' => 'text-blue-600',
        'orange' => 'text-orange-600',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white border border-slate-200 p-6 rounded-3xl relative overflow-hidden group transition-all duration-500 hover:shadow-md ' . ($colorClasses[$color] ?? 'hover:border-emerald-300')]) }}>
    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">{{ $title }}</div>
    <div class="text-3xl font-extrabold tracking-tight mb-2 {{ $valueColor[$color] ?? 'text-slate-900' }}">
        @if($isCurrency)<span class="text-sm text-slate-400 mr-1">Rp</span>@endif{{ is_numeric($value) ? number_format($value, 0, ',', '.') : $value }}
    </div>
    
    @if($trend)
        <div class="flex items-center gap-2 text-[10px] font-bold {{ $trend > 0 ? 'text-emerald-500' : 'text-red-500' }}">
            <i data-lucide="{{ $trend > 0 ? 'trending-up' : 'trending-down' }}" class="w-3 h-3"></i> 
            {{ $trend }}% dari periode lalu
        </div>
    @elseif($subtitle)
        <div class="text-[10px] font-bold text-slate-500">{{ $subtitle }}</div>
    @endif

    <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
        <i data-lucide="{{ $icon }}" class="w-16 h-16 {{ $valueColor[$color] ?? 'text-slate-900' }}"></i>
    </div>
</div>
