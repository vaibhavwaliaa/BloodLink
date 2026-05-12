@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div x-data="dashboardState()" x-init="init()" class="space-y-8">

    <section data-reveal class="translate-y-6 opacity-0 transition duration-700">
        <div class="grid gap-4 md:grid-cols-3">
            <x-ui.card class="p-5">
                <div class="text-sm font-bold uppercase tracking-[0.2em] text-slate-400">Active requests</div>
                <div class="mt-3 text-4xl font-black text-slate-950" x-text="stats.activeRequests"></div>
                <div class="mt-2 text-sm text-slate-500" x-text="city ? `Live requests in ${city}.` : 'Detecting your city.'"></div>
            </x-ui.card>
            <x-ui.card class="p-5">
                <div class="text-sm font-bold uppercase tracking-[0.2em] text-slate-400">Donors available</div>
                <div class="mt-3 text-4xl font-black text-slate-950" x-text="stats.availableDonors"></div>
                <div class="mt-2 text-sm text-slate-500" x-text="city ? `Verified donors in ${city}.` : 'Matched and ready to respond.'"></div>
            </x-ui.card>
            <x-ui.card class="p-5">
                <div class="text-sm font-bold uppercase tracking-[0.2em] text-slate-400">Current city</div>
                <div class="mt-3 text-3xl font-black text-slate-950" x-text="city || 'Locating…'"></div>
                <div class="mt-2 text-sm text-slate-500" x-text="locationStatus"></div>
            </x-ui.card>
        </div>
    </section>

    <div class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr]">
        <section class="space-y-6">
            <x-ui.card class="overflow-hidden p-0" data-reveal>
                <div class="border-b border-slate-100 bg-gradient-to-r from-white to-slate-50 px-6 py-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <span class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-bold uppercase tracking-[0.22em] text-red-700 ring-1 ring-red-100">Dashboard</span>
                            <h1 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">Active requests near you</h1>
                            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500" x-text="city ? `Showing live requests around ${city}.` : 'Allow location access to load city-specific live data.'"></p>
                        </div>
                        <div class="flex flex-wrap gap-2 items-center">
                            <x-ui.button type="button" variant="secondary" size="sm" @click="refreshLocation()">Refresh location</x-ui.button>
                            <div class="flex items-center gap-2">
                                <input x-model="manualCity" placeholder="Or enter a city (e.g. Jalandhar)" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm outline-none" />
                                <x-ui.button type="button" variant="ghost" size="sm" @click="setCity(manualCity)">Set city</x-ui.button>
                            </div>
                            <x-ui.button href="{{ route('requests.create') }}" size="sm">Create request</x-ui.button>
                            <x-ui.button href="{{ route('donor.registration') }}" variant="secondary" size="sm">Register donor</x-ui.button>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-3">
                        <div class="rounded-3xl bg-white p-4 ring-1 ring-slate-100">
                            <div class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Critical</div>
                            <div class="mt-2 text-2xl font-black text-red-600" x-text="stats.criticalRequests"></div>
                            <div class="mt-1 text-sm text-slate-500">Requires immediate attention.</div>
                        </div>
                        <div class="rounded-3xl bg-white p-4 ring-1 ring-slate-100">
                            <div class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">High priority</div>
                            <div class="mt-2 text-2xl font-black text-slate-900" x-text="stats.highRequests"></div>
                            <div class="mt-1 text-sm text-slate-500">Needs rapid donor matching.</div>
                        </div>
                        <div class="rounded-3xl bg-white p-4 ring-1 ring-slate-100">
                            <div class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Normal</div>
                            <div class="mt-2 text-2xl font-black text-slate-900" x-text="stats.normalRequests"></div>
                            <div class="mt-1 text-sm text-slate-500">Stable request queue.</div>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid gap-3 lg:grid-cols-[1fr_auto]">
                        <label class="relative block">
                            <span class="sr-only">Search requests</span>
                            <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">⌕</span>
                            <input x-model="query" type="search" placeholder="Search patient, hospital, or blood type" class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 text-sm font-medium text-slate-900 outline-none transition focus:border-red-300 focus:bg-white focus:ring-4 focus:ring-red-100">
                        </label>

                        <select x-model="urgency" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-red-300 focus:bg-white focus:ring-4 focus:ring-red-100">
                            <option value="all">All priorities</option>
                            <option value="critical">Critical</option>
                            <option value="high">High</option>
                            <option value="normal">Normal</option>
                        </select>
                    </div>

                    <div class="mt-6 space-y-4">
                        <template x-if="loading">
                            <div class="space-y-4">
                                <div class="animate-pulse rounded-[1.75rem] border border-slate-100 bg-white p-5">
                                    <div class="h-4 w-32 rounded-full bg-slate-200"></div>
                                    <div class="mt-4 h-5 w-2/3 rounded-full bg-slate-200"></div>
                                    <div class="mt-3 h-4 w-1/2 rounded-full bg-slate-100"></div>
                                </div>
                                <div class="animate-pulse rounded-[1.75rem] border border-slate-100 bg-white p-5">
                                    <div class="h-4 w-28 rounded-full bg-slate-200"></div>
                                    <div class="mt-4 h-5 w-3/4 rounded-full bg-slate-200"></div>
                                    <div class="mt-3 h-4 w-1/3 rounded-full bg-slate-100"></div>
                                </div>
                            </div>
                        </template>

                        <template x-for="request in filteredRequests" :key="request.id">
                            <article class="group rounded-[1.75rem] border border-slate-100 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-[0_22px_60px_rgba(15,23,42,0.10)]">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-bold uppercase tracking-[0.22em] text-red-700" x-text="request.blood_type"></span>
                                            <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.22em]" :class="request.urgency_level === 'CRITICAL' ? 'bg-rose-100 text-rose-700' : request.urgency_level === 'HIGH' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600'" x-text="request.urgency_level"></span>
                                        </div>
                                        <h2 class="mt-4 text-xl font-bold text-slate-950" x-text="request.hospital_name"></h2>
                                        <p class="mt-2 text-sm leading-6 text-slate-500">
                                            Patient <span class="font-semibold text-slate-700" x-text="request.patient_name"></span> ·
                                            <span x-text="request.hospital_name"></span> ·
                                            <span x-text="request.units_required + ' units'"></span>
                                        </p>
                                    </div>
                                    <div class="flex flex-col items-start gap-2 sm:items-end">
                                        <div class="text-sm font-semibold text-slate-400" x-text="formatTime(request.created_at)"></div>
                                        <x-ui.button variant="secondary" size="sm">Reveal contact</x-ui.button>
                                    </div>
                                </div>
                            </article>
                        </template>

                        <div x-show="!loading && filteredRequests.length === 0" class="rounded-[1.75rem] border border-dashed border-slate-200 bg-slate-50 p-8 text-center text-slate-500">
                            No requests match the current search and filter.
                        </div>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card class="overflow-hidden p-0" data-reveal>
                <div class="border-b border-slate-100 px-6 py-5">
                    <div class="text-xs font-bold uppercase tracking-[0.22em] text-slate-400">Live operations</div>
                    <div class="mt-2 text-2xl font-black text-slate-950">Coordination overview</div>
                </div>
                <div class="grid gap-5 p-6 lg:grid-cols-[1.1fr_0.9fr]">
                    <div class="rounded-3xl bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 p-5 text-white">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="text-sm font-bold uppercase tracking-[0.2em] text-white/50">Map preview</div>
                                <div class="mt-2 text-xl font-bold" x-text="city ? `${city} coverage` : 'Region coverage'"></div>
                            </div>
                            <span class="rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-bold uppercase tracking-[0.2em]">Live</span>
                        </div>

                        <div class="mt-6 grid gap-3 sm:grid-cols-3">
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                                <div class="text-sm text-white/60">Nearby donors</div>
                                <div class="mt-2 text-3xl font-black" x-text="stats.availableDonors"></div>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                                <div class="text-sm text-white/60">Cities covered</div>
                                <div class="mt-2 text-3xl font-black" x-text="stats.citiesCovered"></div>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                                <div class="text-sm text-white/60">Current city</div>
                                <div class="mt-2 text-xl font-black truncate" x-text="city || 'Detecting…'"></div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="rounded-3xl border border-slate-100 bg-slate-50 p-5">
                            <div class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Activity feed</div>
                            <div class="mt-4 space-y-4">
                                <div class="flex gap-3">
                                    <div class="mt-1 h-3 w-3 rounded-full bg-emerald-500"></div>
                                    <p class="text-sm leading-6 text-slate-600" x-text="city ? `${stats.availableDonors} donors are available in ${city}.` : 'Searching for donors near your location.'"></p>
                                </div>
                                <div class="flex gap-3">
                                    <div class="mt-1 h-3 w-3 rounded-full bg-red-500"></div>
                                    <p class="text-sm leading-6 text-slate-600" x-text="stats.criticalRequests ? `${stats.criticalRequests} critical requests need immediate attention.` : 'Critical request feed loading.'"></p>
                                </div>
                                <div class="flex gap-3">
                                    <div class="mt-1 h-3 w-3 rounded-full bg-slate-400"></div>
                                    <p class="text-sm leading-6 text-slate-600">Queued donor registrations remain available for OTP verification.</p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-3xl border border-dashed border-slate-200 bg-white p-5">
                            <div class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Skeleton state</div>
                            <div class="mt-4 space-y-3 animate-pulse">
                                <div class="h-4 w-3/4 rounded-full bg-slate-200"></div>
                                <div class="h-4 w-1/2 rounded-full bg-slate-100"></div>
                                <div class="h-10 rounded-2xl bg-slate-100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </section>

        <aside class="space-y-6 lg:sticky lg:top-24 lg:h-fit">
            <x-ui.card data-reveal class="translate-y-6 opacity-0 transition duration-700">
                <div class="rounded-[1.5rem] bg-gradient-to-br from-red-600 via-rose-600 to-slate-900 p-6 text-white">
                    <div class="text-xs font-bold uppercase tracking-[0.24em] text-white/70">Quick actions</div>
                    <div class="mt-3 text-2xl font-black tracking-tight">Create, register, and respond instantly.</div>
                    <div class="mt-6 space-y-3">
                        <x-ui.button href="{{ route('requests.create') }}" variant="secondary" class="w-full justify-center">Create Request</x-ui.button>
                        <x-ui.button href="{{ route('donor.registration') }}" variant="ghost" class="w-full justify-center text-white hover:bg-white/10">Register as Donor</x-ui.button>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card data-reveal class="translate-y-6 opacity-0 transition duration-700">
                <x-ui.section-heading eyebrow="Profile" title="Your response team" description="A compact summary panel for trust and identity." />
                <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                    <div class="rounded-3xl bg-slate-50 p-4">
                        <div class="text-sm text-slate-500">Available donors</div>
                        <div class="mt-1 text-3xl font-black text-slate-950">12</div>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-4">
                        <div class="text-sm text-slate-500">Cities tracked</div>
                        <div class="mt-1 text-3xl font-black text-slate-950">5</div>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card data-reveal class="translate-y-6 opacity-0 transition duration-700">
                <div class="text-xs font-bold uppercase tracking-[0.22em] text-slate-400">Notifications</div>
                <div class="mt-3 text-xl font-bold text-slate-950">Recent updates</div>
                <div class="mt-4 space-y-4">
                    <div class="rounded-3xl bg-slate-50 p-4">
                        <div class="text-sm font-semibold text-slate-900">Critical alert escalated</div>
                        <p class="mt-1 text-sm leading-6 text-slate-500">Live notifications keep responders informed with minimal delay.</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-4">
                        <div class="text-sm font-semibold text-slate-900">Donor onboarding in progress</div>
                        <p class="mt-1 text-sm leading-6 text-slate-500">OTP verification remains exactly as implemented in the backend.</p>
                    </div>
                </div>
            </x-ui.card>
        </aside>
    </div>
</div>

@push('scripts')
<script>
window.dashboardState = function () {
        return {
        query: '',
        urgency: 'all',
        loading: true,
            manualCity: '',
        requests: [],
        donors: [],
        city: null,
        locationStatus: 'Detecting your location…',
        stats: {
            activeRequests: 0,
            availableDonors: 0,
            criticalRequests: 0,
            highRequests: 0,
            normalRequests: 0,
            citiesCovered: 0,
        },
        init() {
            this.refreshLocation();
        },
        async refreshLocation() {
            this.loading = true;
            this.locationStatus = 'Requesting location permission…';

            try {
                const city = await this.detectCity();
                this.city = city;
                this.locationStatus = `Showing live data for ${city}.`;
                await this.loadData(city);
                window.BloodLink.flash(`Loaded live requests for ${city}.`, 'success');
            } catch (error) {
                console.error(error);
                this.city = null;
                this.locationStatus = 'Enable location access to see city-specific live data.';
                this.requests = [];
                this.donors = [];
                this.recalculateStats();
                window.BloodLink.flash('Location access is required to load live city data.', 'error');
            } finally {
                this.loading = false;
            }
        },
        async setCity(name) {
            if (!name || !name.trim()) {
                window.BloodLink.flash('Enter a valid city name.', 'error');
                return;
            }
            this.city = name.trim();
            this.locationStatus = `Showing live data for ${this.city}.`;
            this.loading = true;
            try {
                await this.loadData(this.city);
                window.BloodLink.flash(`Loaded live requests for ${this.city}.`, 'success');
            } catch (e) {
                console.error(e);
                window.BloodLink.flash('Failed to load data for that city.', 'error');
            } finally {
                this.loading = false;
            }
        },
        detectCity() {
            return new Promise((resolve, reject) => {
                if (!navigator.geolocation) {
                    reject(new Error('Geolocation not supported'));
                    return;
                }

                navigator.geolocation.getCurrentPosition(async (position) => {
                    try {
                        const { latitude, longitude } = position.coords;
                        const response = await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${latitude}&longitude=${longitude}&localityLanguage=en`);
                        if (!response.ok) {
                            throw new Error('Reverse geocode failed');
                        }

                        const data = await response.json();
                        const city = data.city || data.locality || data.principalSubdivision || data.localityInfo?.administrative?.[0]?.name;
                        if (!city) {
                            throw new Error('City not found');
                        }

                        resolve(city);
                    } catch (error) {
                        reject(error);
                    }
                }, reject, { enableHighAccuracy: true, timeout: 12000, maximumAge: 600000 });
            });
        },
        async loadData(city) {
            const [requestsResponse, donorsResponse] = await Promise.all([
                fetch(`/api/requests?city=${encodeURIComponent(city)}`),
                fetch('/api/requests/debug-donors'),
            ]);

            if (!requestsResponse.ok) {
                throw new Error('Failed to load requests');
            }

            if (!donorsResponse.ok) {
                throw new Error('Failed to load donors');
            }

            const requests = await requestsResponse.json();
            const donors = await donorsResponse.json();

            this.requests = Array.isArray(requests) ? requests : [];
            this.donors = Array.isArray(donors)
                ? donors.filter((donor) => {
                    const donorCity = (donor.city || '').trim().toLowerCase();
                    const currentCity = city.trim().toLowerCase();
                    return donor.is_donor && donor.is_available && donorCity === currentCity;
                })
                : [];

            this.recalculateStats();
        },
        recalculateStats() {
            this.stats.activeRequests = this.requests.length;
            this.stats.availableDonors = this.donors.length;
            this.stats.citiesCovered = this.city ? 1 : 0;
            this.stats.criticalRequests = this.requests.filter((request) => request.urgency_level === 'CRITICAL').length;
            this.stats.highRequests = this.requests.filter((request) => request.urgency_level === 'HIGH').length;
            this.stats.normalRequests = this.requests.filter((request) => request.urgency_level === 'NORMAL').length;
        },
        get filteredRequests() {
            return this.requests.filter((request) => {
                const searchMatch = [request.patient_name, request.hospital_name, request.blood_type, request.city]
                    .join(' ')
                    .toLowerCase()
                    .includes(this.query.toLowerCase());
                const urgencyMatch = this.urgency === 'all' || request.urgency_level.toLowerCase() === this.urgency;
                return searchMatch && urgencyMatch;
            });
        },
        formatTime(timestamp) {
            if (!timestamp) {
                return 'Just now';
            }

            const date = new Date(timestamp);
            const minutes = Math.max(1, Math.floor((Date.now() - date.getTime()) / 60000));
            if (minutes < 60) {
                return `${minutes} minute${minutes === 1 ? '' : 's'} ago`;
            }

            const hours = Math.floor(minutes / 60);
            return `${hours} hour${hours === 1 ? '' : 's'} ago`;
        },
    };
};
</script>
@endpush
@endsection
