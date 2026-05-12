<footer class="relative z-10 border-t border-white/70 bg-white/70 backdrop-blur-xl">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div>
                <div class="text-lg font-extrabold tracking-tight text-slate-900">BloodLink</div>
                <p class="mt-2 max-w-xl text-sm leading-6 text-slate-500">A premium Laravel healthcare experience for donors, patients, and coordinators.</p>
            </div>
            <div class="flex flex-wrap gap-2 text-sm font-semibold text-slate-500">
                <a href="{{ route('dashboard') }}" class="rounded-xl px-4 py-2 transition hover:bg-slate-100 hover:text-slate-900">Dashboard</a>
                <a href="{{ route('requests.create') }}" class="rounded-xl px-4 py-2 transition hover:bg-slate-100 hover:text-slate-900">Request Blood</a>
                <a href="{{ route('donor.registration') }}" class="rounded-xl px-4 py-2 transition hover:bg-slate-100 hover:text-slate-900">Register Donor</a>
            </div>
        </div>
        <div class="mt-6 flex flex-col gap-3 border-t border-slate-200/80 pt-6 text-xs font-medium uppercase tracking-[0.22em] text-slate-400 sm:flex-row sm:items-center sm:justify-between">
            <span>© {{ date('Y') }} BloodLink. Built for rapid response.</span>
            <span>Laravel 12 · Blade · Tailwind · Alpine</span>
        </div>
    </div>
</footer>