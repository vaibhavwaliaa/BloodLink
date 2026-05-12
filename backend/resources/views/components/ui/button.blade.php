@props([
    'href' => null,
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-xl font-semibold transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-red-100';
    $sizes = [
        'sm' => 'px-4 py-2 text-sm',
        'md' => 'px-5 py-3 text-sm',
        'lg' => 'px-6 py-3.5 text-base',
    ];
    $variants = [
        'primary' => 'bg-gradient-to-r from-red-600 to-rose-600 text-white shadow-lg shadow-red-600/20 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-red-600/25',
        'secondary' => 'border border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50',
        'ghost' => 'bg-transparent text-slate-700 hover:bg-slate-100',
        'danger' => 'bg-rose-600 text-white shadow-lg shadow-rose-600/20 hover:-translate-y-0.5 hover:bg-rose-700',
    ];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class([$base, $sizes[$size] ?? $sizes['md'], $variants[$variant] ?? $variants['primary']]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class([$base, $sizes[$size] ?? $sizes['md'], $variants[$variant] ?? $variants['primary']]) }}>
        {{ $slot }}
    </button>
@endif