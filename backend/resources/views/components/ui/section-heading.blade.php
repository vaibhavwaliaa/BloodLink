@props([
    'eyebrow' => null,
    'title' => null,
    'description' => null,
])

<div {{ $attributes->class('flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between') }}>
    <div>
        @if ($eyebrow)
            <span class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-bold uppercase tracking-[0.22em] text-red-700 ring-1 ring-red-100">{{ $eyebrow }}</span>
        @endif
        @if ($title)
            <h2 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">{{ $title }}</h2>
        @endif
        @if ($description)
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">{{ $description }}</p>
        @endif
    </div>

    @if (trim($slot))
        <div class="flex flex-wrap gap-2">{{ $slot }}</div>
    @endif
</div>