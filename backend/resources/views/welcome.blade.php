@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div class="space-y-10 lg:space-y-14">
    <section data-reveal class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-center translate-y-6 opacity-0 transition duration-700">
        <div class="space-y-8">
            <span class="inline-flex items-center gap-2 rounded-full border border-red-100 bg-white/80 px-4 py-2 text-xs font-bold uppercase tracking-[0.24em] text-red-700 shadow-sm">
                <span class="h-2 w-2 rounded-full bg-red-500"></span>
                Blood donation response platform
            </span>

            <div class="space-y-5">
                <h1 class="max-w-3xl text-4xl font-black tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
                    Premium healthcare coordination for urgent blood requests.
                </h1>
                <p class="max-w-2xl text-lg leading-8 text-slate-600">
                    A modern Blade interface with the elegance of a SaaS platform, built to help donors and recipients move fast without friction.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <x-ui.button href="{{ route('requests.create') }}" size="lg">
                    Request Blood
                </x-ui.button>
                <x-ui.button href="{{ route('donor.registration') }}" variant="secondary" size="lg">
                    Register as Donor
                </x-ui.button>
                <x-ui.button href="{{ url('/api/auth/google') }}" variant="ghost" size="lg">
                    Continue with Google
                </x-ui.button>
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                <x-ui.card class="group p-5 transition duration-300 hover:-translate-y-1 hover:shadow-[0_22px_60px_rgba(15,23,42,0.12)]" data-reveal>
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-red-50 text-xl transition group-hover:scale-105">📍</div>
                    <div class="mt-4 text-sm font-bold uppercase tracking-[0.2em] text-slate-400">Live matching</div>
                    <div class="mt-2 text-lg font-bold text-slate-900">Nearby donor discovery</div>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Prioritize city-level matches and emergency availability in one view.</p>
                </x-ui.card>

                <x-ui.card class="group p-5 transition duration-300 hover:-translate-y-1 hover:shadow-[0_22px_60px_rgba(15,23,42,0.12)]" data-reveal>
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-rose-50 text-xl transition group-hover:scale-105">⚡</div>
                    <div class="mt-4 text-sm font-bold uppercase tracking-[0.2em] text-slate-400">Fast alerts</div>
                    <div class="mt-2 text-lg font-bold text-slate-900">High-priority notifications</div>
                    <p class="mt-2 text-sm leading-6 text-slate-500">A quick broadcast path keeps the emergency workflow simple and direct.</p>
                </x-ui.card>

                <x-ui.card class="group p-5 transition duration-300 hover:-translate-y-1 hover:shadow-[0_22px_60px_rgba(15,23,42,0.12)]" data-reveal>
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-xl transition group-hover:scale-105">🛡️</div>
                    <div class="mt-4 text-sm font-bold uppercase tracking-[0.2em] text-slate-400">Trust layer</div>
                    <div class="mt-2 text-lg font-bold text-slate-900">Clean, server-rendered UX</div>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Blade-powered and stable, with no heavy client app dependency required.</p>
                </x-ui.card>
            </div>
        </div>

        <div class="relative">
            <div class="absolute -inset-6 rounded-[2.5rem] bg-gradient-to-br from-red-500/10 via-rose-400/10 to-white blur-3xl"></div>
            <x-ui.card class="relative overflow-hidden p-0">
                <div class="bg-gradient-to-br from-red-600 via-rose-600 to-slate-900 px-7 py-7 text-white">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-xs font-bold uppercase tracking-[0.28em] text-white/70">BloodLink command center</div>
                            <div class="mt-3 text-3xl font-black tracking-tight">Make a request. Save a life.</div>
                        </div>
                        <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-bold uppercase tracking-[0.2em]">Live</span>
                    </div>

                    <div class="mt-8 grid gap-3 sm:grid-cols-3">
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <div class="text-sm text-white/70">Requests today</div>
                            <div class="mt-2 text-2xl font-black">24</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <div class="text-sm text-white/70">Donors online</div>
                            <div class="mt-2 text-2xl font-black">128</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <div class="text-sm text-white/70">Avg response</div>
                            <div class="mt-2 text-2xl font-black">8m</div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 p-7">
                    <div class="rounded-3xl border border-slate-100 bg-slate-50 p-5">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">Critical request</div>
                                <div class="mt-2 text-xl font-bold text-slate-900">O+ needed at City Hospital</div>
                            </div>
                            <span class="rounded-full bg-red-100 px-3 py-1 text-sm font-bold text-red-700">Critical</span>
                        </div>
                        <div class="mt-4 flex items-center justify-between text-sm text-slate-500">
                            <span>Patient: Asha Verma</span>
                            <span>2 units · 8 minutes ago</span>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <div class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">Donor availability</div>
                                <div class="mt-2 text-xl font-bold text-slate-900">12 matched donors nearby</div>
                            </div>
                            <div class="h-12 w-12 animate-pulse rounded-full bg-red-100"></div>
                        </div>
                        <div class="mt-4 h-2 rounded-full bg-slate-100">
                            <div class="h-2 w-3/4 rounded-full bg-gradient-to-r from-red-500 to-rose-500"></div>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-[0.95fr_1.05fr]">
        <x-ui.card data-reveal class="translate-y-6 opacity-0 transition duration-700">
            <x-ui.section-heading eyebrow="How it works" title="A simple flow for urgent care" description="The experience stays focused: create the request, identify donors, and move fast." />
            <div class="mt-6 space-y-4">
                <div class="flex gap-4 rounded-3xl bg-slate-50 p-4">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-red-50 font-bold text-red-700">01</div>
                    <div>
                        <div class="font-bold text-slate-900">Request blood</div>
                        <p class="mt-1 text-sm leading-6 text-slate-500">Create a clean, guided request with the exact details donors need.</p>
                    </div>
                </div>
                <div class="flex gap-4 rounded-3xl bg-slate-50 p-4">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-red-50 font-bold text-red-700">02</div>
                    <div>
                        <div class="font-bold text-slate-900">Notify nearby donors</div>
                        <p class="mt-1 text-sm leading-6 text-slate-500">The notification flow is already wired through your existing API endpoints.</p>
                    </div>
                </div>
                <div class="flex gap-4 rounded-3xl bg-slate-50 p-4">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-red-50 font-bold text-red-700">03</div>
                    <div>
                        <div class="font-bold text-slate-900">Confirm and respond</div>
                        <p class="mt-1 text-sm leading-6 text-slate-500">Donors verify with OTP and become available for future emergency alerts.</p>
                    </div>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="overflow-hidden p-0" data-reveal>
            <div class="border-b border-slate-100 bg-white px-6 py-5">
                <div class="text-sm font-bold uppercase tracking-[0.2em] text-slate-400">System status</div>
                <div class="mt-2 text-2xl font-black text-slate-950">Healthy, responsive, and ready</div>
            </div>
            <div class="grid gap-4 p-6 sm:grid-cols-2">
                <div class="rounded-3xl bg-gradient-to-br from-red-50 to-white p-5 ring-1 ring-red-100">
                    <div class="text-sm font-semibold uppercase tracking-[0.18em] text-red-500">Latency</div>
                    <div class="mt-3 text-3xl font-black text-slate-900">Fast</div>
                    <div class="mt-2 text-sm leading-6 text-slate-500">Server-rendered Blade pages keep interactions snappy and predictable.</div>
                </div>
                <div class="rounded-3xl bg-gradient-to-br from-slate-50 to-white p-5 ring-1 ring-slate-100">
                    <div class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">UX</div>
                    <div class="mt-3 text-3xl font-black text-slate-900">Premium</div>
                    <div class="mt-2 text-sm leading-6 text-slate-500">Glassmorphism, strong hierarchy, and mobile-first spacing everywhere.</div>
                </div>
                <div class="rounded-3xl bg-slate-950 p-5 text-white sm:col-span-2">
                    <div class="text-sm font-semibold uppercase tracking-[0.18em] text-white/60">Next step</div>
                    <div class="mt-3 text-2xl font-black">Open the dashboard to see the interaction layer.</div>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <x-ui.button href="{{ route('dashboard') }}" size="md">Go to Dashboard</x-ui.button>
                        <x-ui.button href="{{ route('requests.create') }}" variant="secondary" size="md">Create Request</x-ui.button>
                    </div>
                </div>
            </div>
        </x-ui.card>
    </section>
</div>
@endsection
