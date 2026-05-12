@extends('layouts.app')

@section('title', 'OAuth Callback')

@section('content')
<div class="mx-auto max-w-2xl" data-reveal>
    <x-ui.card class="overflow-hidden p-0 text-center">
        <div class="bg-gradient-to-br from-red-600 via-rose-600 to-slate-900 px-8 py-8 text-white">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-white/15 bg-white/10 text-3xl backdrop-blur">⏳</div>
            <h1 class="mt-6 text-3xl font-black tracking-tight sm:text-4xl">Signing you in...</h1>
            <p class="mt-3 text-sm leading-7 text-white/80">Your session is being secured. The app will continue automatically after the token is stored.</p>
        </div>

        <div class="grid gap-4 p-6 sm:grid-cols-3">
            <div class="rounded-3xl bg-slate-50 p-4">
                <div class="mx-auto h-3 w-3 animate-pulse rounded-full bg-emerald-500"></div>
                <div class="mt-3 text-sm font-semibold text-slate-900">Authenticating</div>
            </div>
            <div class="rounded-3xl bg-slate-50 p-4">
                <div class="mx-auto h-3 w-3 animate-pulse rounded-full bg-red-500"></div>
                <div class="mt-3 text-sm font-semibold text-slate-900">Storing token</div>
            </div>
            <div class="rounded-3xl bg-slate-50 p-4">
                <div class="mx-auto h-3 w-3 animate-pulse rounded-full bg-slate-400"></div>
                <div class="mt-3 text-sm font-semibold text-slate-900">Redirecting</div>
            </div>
        </div>

        <div class="border-t border-slate-100 px-6 py-5">
            <p class="text-sm text-slate-500">If you are not redirected automatically, use the button below.</p>
            <a href="{{ route('dashboard') }}" class="mt-4 inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-red-600 to-rose-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-red-600/20 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-red-600/25">Continue to dashboard</a>
        </div>
    </x-ui.card>
</div>

@push('scripts')
<script>
(function () {
    const token = new URLSearchParams(window.location.search).get('token');
    if (!token) {
        window.location.href = "{{ route('home') }}";
        return;
    }

    localStorage.setItem('bloodlink_token', token);
    window.BloodLink.flash('Signed in successfully.', 'success');
    const intent = localStorage.getItem('auth_intent');

    if (intent === 'donor') {
        localStorage.removeItem('auth_intent');
        window.location.href = "{{ route('donor.registration') }}";
        return;
    }

    window.location.href = "{{ route('dashboard') }}";
})();
</script>
@endpush

@endsection
