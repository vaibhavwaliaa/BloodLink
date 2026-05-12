@extends('layouts.app')

@section('title', 'Donor Registration')

@section('content')
<div class="grid gap-8 lg:grid-cols-[0.95fr_1.05fr] lg:items-start">
    <x-ui.card class="overflow-hidden p-0 lg:sticky lg:top-24" data-reveal>
        <div class="bg-gradient-to-br from-red-600 via-rose-600 to-slate-900 px-7 py-7 text-white">
            <div class="text-xs font-bold uppercase tracking-[0.26em] text-white/70">Donor verification</div>
            <h1 class="mt-3 text-3xl font-black tracking-tight">Complete your donor profile</h1>
            <p class="mt-3 text-sm leading-7 text-white/85">Verify your phone number to receive emergency alerts and future donation requests.</p>
        </div>

        <div class="grid gap-4 p-6">
            <div class="rounded-3xl bg-slate-50 p-4">
                <div class="text-sm font-semibold text-slate-900">Step 1</div>
                <div class="mt-1 text-sm text-slate-500">Enter your blood group, city, and mobile number.</div>
            </div>
            <div class="rounded-3xl bg-slate-50 p-4">
                <div class="text-sm font-semibold text-slate-900">Step 2</div>
                <div class="mt-1 text-sm text-slate-500">Verify OTP and activate your donor profile.</div>
            </div>
            <div class="rounded-3xl bg-white p-4 ring-1 ring-slate-100">
                <div class="text-sm font-semibold text-slate-900">Security note</div>
                <p class="mt-1 text-sm leading-6 text-slate-500">The backend authentication and OTP flow remain unchanged.</p>
            </div>
        </div>
    </x-ui.card>

    <x-ui.card data-reveal>
        <div class="flex flex-wrap items-center justify-between gap-3 text-sm font-semibold text-slate-500">
            <a href="{{ route('home') }}" class="transition hover:text-red-700">← Home</a>
            <a href="{{ route('dashboard') }}" class="transition hover:text-red-700">Dashboard</a>
        </div>

        <div class="mt-6">
            <div class="mb-5 flex items-center gap-3">
                <div class="h-2 flex-1 overflow-hidden rounded-full bg-slate-100">
                    <div id="step-indicator" class="h-full w-1/2 rounded-full bg-gradient-to-r from-red-600 to-rose-600 transition-all duration-300"></div>
                </div>
                <span class="text-xs font-bold uppercase tracking-[0.22em] text-slate-400">Step 1 of 2</span>
            </div>

            <form id="donor-form" class="space-y-5">
                <div id="step-1" class="space-y-5">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700" for="bloodType">Blood Type</label>
                        <select id="bloodType" name="bloodType" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-medium text-slate-900 outline-none transition focus:border-red-300 focus:bg-white focus:ring-4 focus:ring-red-100">
                            <option>A+</option><option>A-</option><option>B+</option><option>B-</option><option>AB+</option><option>AB-</option><option>O+</option><option>O-</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700" for="city">City</label>
                        <input id="city" name="city" type="text" placeholder="e.g. Chandigarh" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-medium text-slate-900 outline-none transition focus:border-red-300 focus:bg-white focus:ring-4 focus:ring-red-100">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700" for="phoneNumber">Phone Number</label>
                        <input id="phoneNumber" name="phoneNumber" type="tel" placeholder="+919876543210" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-medium text-slate-900 outline-none transition focus:border-red-300 focus:bg-white focus:ring-4 focus:ring-red-100">
                    </div>
                    <button type="button" id="send-otp" class="w-full rounded-xl bg-gradient-to-r from-red-600 to-rose-600 px-5 py-3.5 text-sm font-semibold text-white shadow-lg shadow-red-600/20 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-red-600/25">Send OTP</button>
                </div>

                <div id="step-2" class="hidden space-y-5">
                    <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">Code sent to <span id="sent-phone" class="font-semibold"></span></div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700" for="otp">Enter 6-digit OTP</label>
                        <input id="otp" name="otp" type="text" maxlength="6" placeholder="••••••" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-center tracking-[0.4em] text-sm font-medium text-slate-900 outline-none transition focus:border-red-300 focus:bg-white focus:ring-4 focus:ring-red-100">
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <button type="button" id="verify-otp" class="rounded-xl bg-gradient-to-r from-red-600 to-rose-600 px-5 py-3.5 text-sm font-semibold text-white shadow-lg shadow-red-600/20 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-red-600/25">Verify & Register</button>
                        <button type="button" id="change-number" class="rounded-xl border border-slate-200 bg-white px-5 py-3.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Change number</button>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-5">
                    <p id="donor-status" class="text-sm font-medium text-slate-500">Ready to verify.</p>
                </div>
            </form>
        </div>
    </x-ui.card>
</div>

@push('scripts')
<script>
const step1 = document.getElementById('step-1');
const step2 = document.getElementById('step-2');
const statusEl = document.getElementById('donor-status');
const sentPhone = document.getElementById('sent-phone');
const stepIndicator = document.getElementById('step-indicator');

document.getElementById('send-otp').addEventListener('click', async () => {
    const phoneNumber = document.getElementById('phoneNumber').value;
    const body = { phoneNumber };
    statusEl.textContent = 'Sending OTP...';
    const sendButton = document.getElementById('send-otp');
    sendButton.disabled = true;
    sendButton.textContent = 'Sending...';
    try {
        const token = localStorage.getItem('bloodlink_token');
        const response = await fetch('/api/auth/send-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                ...(token ? { 'Authorization': `Bearer ${token}` } : {})
            },
            body: JSON.stringify(body)
        });

        if (response.ok) {
            sentPhone.textContent = phoneNumber;
            step1.classList.add('hidden');
            step2.classList.remove('hidden');
            stepIndicator.classList.remove('w-1/2');
            stepIndicator.classList.add('w-full');
            statusEl.textContent = 'OTP sent successfully.';
            window.BloodLink.flash('OTP sent to your phone.', 'success');
        } else {
            statusEl.textContent = 'Unable to send OTP.';
            window.BloodLink.flash('Unable to send OTP right now.', 'error');
        }
    } catch (error) {
        statusEl.textContent = 'Network error.';
        window.BloodLink.flash('Network error while sending OTP.', 'error');
    } finally {
        sendButton.disabled = false;
        sendButton.textContent = 'Send OTP';
    }
});

document.getElementById('change-number').addEventListener('click', () => {
    step2.classList.add('hidden');
    step1.classList.remove('hidden');
    stepIndicator.classList.remove('w-full');
    stepIndicator.classList.add('w-1/2');
});

document.getElementById('verify-otp').addEventListener('click', async () => {
    const body = {
        otp: document.getElementById('otp').value,
        bloodType: document.getElementById('bloodType').value,
        city: document.getElementById('city').value
    };
    statusEl.textContent = 'Verifying OTP...';
    const verifyButton = document.getElementById('verify-otp');
    verifyButton.disabled = true;
    verifyButton.textContent = 'Verifying...';
    try {
        const token = localStorage.getItem('bloodlink_token');
        const response = await fetch('/api/auth/verify-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                ...(token ? { 'Authorization': `Bearer ${token}` } : {})
            },
            body: JSON.stringify(body)
        });

        if (response.ok) {
            statusEl.textContent = 'Donor registered successfully.';
            window.BloodLink.flash('Donor profile activated successfully.', 'success');
            setTimeout(() => window.location.href = "{{ route('dashboard') }}", 800);
        } else {
            const text = await response.text();
            statusEl.textContent = 'Error: ' + text;
            window.BloodLink.flash('OTP verification failed.', 'error');
        }
    } catch (error) {
        statusEl.textContent = 'Network error.';
        window.BloodLink.flash('Network error while verifying OTP.', 'error');
    } finally {
        verifyButton.disabled = false;
        verifyButton.textContent = 'Verify & Register';
    }
});
</script>
@endpush

@endsection
