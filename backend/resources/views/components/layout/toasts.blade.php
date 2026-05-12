<div x-data="BloodLink.toasts()" x-init="init()" class="fixed bottom-4 right-4 z-50 flex w-[min(100%-2rem,22rem)] flex-col gap-3">
    <template x-for="toast in items" :key="toast.id">
        <div x-show="toast.visible" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-3 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-3 scale-95"
            class="rounded-2xl border px-4 py-3 shadow-2xl backdrop-blur-xl" :class="classes(toast.type)">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white/70 text-sm font-bold" x-text="toast.type === 'success' ? '✓' : toast.type === 'error' ? '!' : 'i'"></div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold" x-text="toast.message"></p>
                </div>
                <button type="button" @click="dismiss(toast.id)" class="text-base font-bold opacity-70 transition hover:opacity-100">×</button>
            </div>
        </div>
    </template>
</div>