<nav x-data="BloodLink.nav()" x-init="init()" class="sticky top-0 z-40 border-b border-white/70 bg-white/80 backdrop-blur-2xl">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="group flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-red-600 to-rose-600 text-xl text-white shadow-lg shadow-red-600/20 transition group-hover:-translate-y-0.5">🩸</span>
                <div>
                    <div class="text-lg font-extrabold tracking-tight text-slate-900">BloodLink</div>
                    <div class="text-xs font-medium uppercase tracking-[0.26em] text-slate-500">Healthcare network</div>
                </div>
            </a>

            <div class="hidden items-center gap-2 md:flex">
                <a href="{{ route('home') }}" class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">Home</a>
                <a href="{{ route('dashboard') }}" class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">Dashboard</a>
                <a href="{{ route('requests.create') }}" class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">Request Blood</a>
                <a href="{{ route('donor.registration') }}" class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">Register</a>
            </div>

            <div class="hidden items-center gap-3 md:flex">
                <button type="button" @click="registerDonor()" class="inline-flex items-center gap-2 rounded-xl border border-red-100 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-700 transition hover:border-red-200 hover:bg-red-100">
                    <span>❤️</span>
                    <span>Be a Donor</span>
                </button>

                <template x-if="user">
                    <div class="relative">
                        <button type="button" @click="userMenu = !userMenu" class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-left shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-slate-900 to-slate-700 text-sm font-bold text-white" x-text="(user?.name || user?.email || 'U').slice(0, 1).toUpperCase()"></span>
                            <span class="hidden sm:block">
                                <span class="block text-xs font-medium uppercase tracking-[0.2em] text-slate-400">Signed in</span>
                                <span class="block max-w-40 truncate text-sm font-semibold text-slate-900" x-text="user?.name || user?.email"></span>
                            </span>
                        </button>

                        <div x-show="userMenu" @click.outside="userMenu = false" x-transition class="absolute right-0 mt-3 w-64 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">
                            <div class="border-b border-slate-100 px-4 py-3">
                                <div class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Profile</div>
                                <div class="mt-1 truncate text-sm font-semibold text-slate-900" x-text="user?.email || 'User'"></div>
                            </div>
                            <div class="p-2">
                                <button type="button" @click="window.location.href='{{ route('dashboard') }}'" class="flex w-full items-center rounded-xl px-3 py-2 text-left text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">Dashboard</button>
                                <button type="button" @click="registerDonor()" class="flex w-full items-center rounded-xl px-3 py-2 text-left text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">Register as donor</button>
                                <button type="button" @click="logout()" class="flex w-full items-center rounded-xl px-3 py-2 text-left text-sm font-medium text-rose-600 transition hover:bg-rose-50">Logout</button>
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="!user">
                    <button type="button" @click="login()" class="inline-flex items-center rounded-xl bg-gradient-to-r from-red-600 to-rose-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-red-600/20 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-red-600/25">Login / Register</button>
                </template>
            </div>

            <button type="button" @click="mobileOpen = !mobileOpen" class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 transition hover:bg-slate-50 md:hidden" aria-label="Toggle menu">
                <span class="text-xl" x-text="mobileOpen ? '✕' : '☰'"></span>
            </button>
        </div>

        <div x-show="mobileOpen" x-transition class="pb-5 md:hidden">
            <div class="space-y-2 rounded-3xl border border-slate-200 bg-white p-3 shadow-lg">
                <a href="{{ route('home') }}" class="block rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Home</a>
                <a href="{{ route('dashboard') }}" class="block rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Dashboard</a>
                <a href="{{ route('requests.create') }}" class="block rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Request Blood</a>
                <a href="{{ route('donor.registration') }}" class="block rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Register Donor</a>
                <button type="button" @click="registerDonor()" class="mt-2 w-full rounded-2xl bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">Be a Donor</button>
            </div>
        </div>
    </div>
</nav>