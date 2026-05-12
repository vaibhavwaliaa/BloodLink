@extends('layouts.app')

@section('title', 'Request Blood')

@section('content')
<div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
    <x-ui.card class="overflow-hidden p-0 lg:sticky lg:top-24" data-reveal>
        <div class="bg-gradient-to-br from-red-600 via-rose-600 to-slate-900 px-7 py-7 text-white">
            <div class="text-xs font-bold uppercase tracking-[0.26em] text-white/70">Emergency blood request</div>
            <h1 class="mt-3 text-3xl font-black tracking-tight">Broadcast a request in seconds</h1>
            <p class="mt-3 text-sm leading-7 text-white/85">A focused form with a premium visual shell, without changing the underlying request workflow.</p>
        </div>

        <div class="grid gap-4 p-6">
            <div class="rounded-3xl bg-slate-50 p-4">
                <div class="text-sm font-semibold text-slate-900">What happens next</div>
                <p class="mt-1 text-sm leading-6 text-slate-500">The request is sent to the existing API endpoint, which keeps the same notification behavior in place.</p>
            </div>
            <div class="rounded-3xl bg-white p-4 ring-1 ring-slate-100">
                <div class="text-sm font-semibold text-slate-900">Suggested workflow</div>
                <ul class="mt-3 space-y-2 text-sm text-slate-500">
                    <li>• Enter patient and hospital details.</li>
                    <li>• Set urgency and quantity.</li>
                    <li>• Submit to notify nearby donors.</li>
                </ul>
            </div>
        </div>
    </x-ui.card>

    <x-ui.card data-reveal>
        <x-ui.section-heading eyebrow="Request form" title="Build the emergency request" description="A responsive form with better spacing, clearer hierarchy, and premium input states." />

        <form id="request-form" class="mt-6 grid gap-5">
            <div class="grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="patientName">Patient Name</label>
                    <input id="patientName" name="patientName" type="text" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-red-300 focus:bg-white focus:ring-4 focus:ring-red-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="bloodType">Blood Type Needed</label>
                    <select id="bloodType" name="bloodType" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-medium text-slate-900 outline-none transition focus:border-red-300 focus:bg-white focus:ring-4 focus:ring-red-100">
                        <option>A+</option><option>A-</option><option>B+</option><option>B-</option><option>AB+</option><option>AB-</option><option>O+</option><option>O-</option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="unitsRequired">Units Required</label>
                    <input id="unitsRequired" name="unitsRequired" type="number" min="1" max="10" value="1" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-medium text-slate-900 outline-none transition focus:border-red-300 focus:bg-white focus:ring-4 focus:ring-red-100">
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="hospitalName">Hospital Name</label>
                    <input id="hospitalName" name="hospitalName" type="text" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-medium text-slate-900 outline-none transition focus:border-red-300 focus:bg-white focus:ring-4 focus:ring-red-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="city">City</label>
                    <input id="city" name="city" type="text" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-medium text-slate-900 outline-none transition focus:border-red-300 focus:bg-white focus:ring-4 focus:ring-red-100">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="urgencyLevel">Urgency Level</label>
                    <select id="urgencyLevel" name="urgencyLevel" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-medium text-slate-900 outline-none transition focus:border-red-300 focus:bg-white focus:ring-4 focus:ring-red-100">
                        <option value="NORMAL">Normal</option>
                        <option value="HIGH">High</option>
                        <option value="CRITICAL">Critical</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="contactNumber">Contact Number</label>
                    <input id="contactNumber" name="contactNumber" type="tel" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm font-medium text-slate-900 outline-none transition focus:border-red-300 focus:bg-white focus:ring-4 focus:ring-red-100">
                </div>
            </div>

            <div class="flex flex-col gap-4 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                <p id="request-status" class="text-sm font-medium text-slate-500">Ready to submit.</p>
                <div class="flex flex-wrap gap-3">
                    <button type="button" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50" onclick="history.back()">Cancel</button>
                    <button type="submit" class="rounded-xl bg-gradient-to-r from-red-600 to-rose-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-red-600/20 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-red-600/25">Broadcast Request</button>
                </div>
            </div>
        </form>
    </x-ui.card>
</div>

@push('scripts')
<script>
document.getElementById('request-form').addEventListener('submit', async function (event) {
    event.preventDefault();
    const status = document.getElementById('request-status');
    const submitButton = event.target.querySelector('button[type="submit"]');
    const payload = {
        patientName: document.getElementById('patientName').value,
        bloodType: document.getElementById('bloodType').value,
        hospitalName: document.getElementById('hospitalName').value,
        unitsRequired: document.getElementById('unitsRequired').value,
        urgencyLevel: document.getElementById('urgencyLevel').value,
        contactNumber: document.getElementById('contactNumber').value,
        city: document.getElementById('city').value,
    };

    status.textContent = 'Sending request...';
    submitButton.disabled = true;
    submitButton.textContent = 'Sending...';
    try {
        const response = await fetch('/api/requests', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(payload)
        });

        if (response.ok) {
            status.textContent = 'Request submitted successfully.';
            window.BloodLink.flash('Blood request submitted successfully.', 'success');
            event.target.reset();
        } else {
            const errorText = await response.text();
            status.textContent = 'Error submitting request.';
            window.BloodLink.flash('Request submission failed.', 'error');
            console.error(errorText);
        }
    } catch (error) {
        status.textContent = 'Network error.';
        window.BloodLink.flash('Network error while submitting the request.', 'error');
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = 'Broadcast Request';
    }
});
</script>
@endpush

@endsection
