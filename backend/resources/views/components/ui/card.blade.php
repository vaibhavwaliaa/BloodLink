@props(['padding' => 'p-6'])

<div {{ $attributes->class(['rounded-[1.75rem] border border-white/70 bg-white/85 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur-xl', $padding]) }}>
    {{ $slot }}
</div>