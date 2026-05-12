<!doctype html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#b91c1c">
    <title>@yield('title', 'Blood Donation')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased [font-family:Inter,ui-sans-serif,system-ui,-apple-system,'Segoe_UI',sans-serif]">
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-24 left-1/2 h-80 w-80 -translate-x-1/2 rounded-full bg-red-100/60 blur-3xl"></div>
        <div class="absolute right-0 top-32 h-[32rem] w-[32rem] rounded-full bg-rose-50 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 h-72 w-72 rounded-full bg-slate-100 blur-3xl"></div>
    </div>

    <x-layout.navbar />

    <main class="relative z-10 flex-1">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
            @yield('content')
        </div>
    </main>

    <x-layout.footer />
    <x-layout.toasts />

    <script>
        window.BloodLink = {
            parseToken(token) {
                try {
                    const payload = token.split('.')[1];
                    const base64 = payload.replace(/-/g, '+').replace(/_/g, '/');
                    const padded = base64.padEnd(Math.ceil(base64.length / 4) * 4, '=');
                    return JSON.parse(atob(padded));
                } catch (error) {
                    return null;
                }
            },
            flash(message, type = 'success') {
                localStorage.setItem('bloodlink_toast', JSON.stringify({ message, type }));
                window.dispatchEvent(new CustomEvent('bloodlink:toast'));
            },
            nav() {
                return {
                    mobileOpen: false,
                    userMenu: false,
                    user: null,
                    init() {
                        this.syncUser();
                        window.addEventListener('storage', (event) => {
                            if (event.key === 'bloodlink_token') {
                                this.syncUser();
                            }
                        });
                    },
                    syncUser() {
                        const token = localStorage.getItem('bloodlink_token');
                        this.user = token ? window.BloodLink.parseToken(token) : null;
                    },
                    login() {
                        window.location.href = '/api/auth/google';
                    },
                    registerDonor() {
                        const token = localStorage.getItem('bloodlink_token');
                        if (token) {
                            window.location.href = '{{ route('donor.registration') }}';
                            return;
                        }

                        localStorage.setItem('auth_intent', 'donor');
                        window.location.href = '/api/auth/google';
                    },
                    logout() {
                        localStorage.removeItem('bloodlink_token');
                        localStorage.removeItem('auth_intent');
                        this.user = null;
                        window.location.href = '{{ route('home') }}';
                    },
                };
            },
            toasts() {
                return {
                    items: [],
                    init() {
                        this.consumeStoredToast();
                        window.addEventListener('bloodlink:toast', () => this.consumeStoredToast());
                        window.addEventListener('storage', (event) => {
                            if (event.key === 'bloodlink_toast') {
                                this.consumeStoredToast();
                            }
                        });
                    },
                    consumeStoredToast() {
                        const raw = localStorage.getItem('bloodlink_toast');
                        if (!raw) {
                            return;
                        }

                        localStorage.removeItem('bloodlink_toast');

                        try {
                            const toast = JSON.parse(raw);
                            this.push(toast.message, toast.type);
                        } catch (error) {
                            this.push(raw, 'info');
                        }
                    },
                    push(message, type = 'success') {
                        const id = Date.now() + Math.random();
                        this.items.push({ id, message, type, visible: false });
                        requestAnimationFrame(() => {
                            const toast = this.items.find((item) => item.id === id);
                            if (toast) {
                                toast.visible = true;
                            }
                        });

                        setTimeout(() => this.dismiss(id), 3200);
                    },
                    dismiss(id) {
                        const toast = this.items.find((item) => item.id === id);
                        if (toast) {
                            toast.visible = false;
                        }

                        setTimeout(() => {
                            this.items = this.items.filter((item) => item.id !== id);
                        }, 250);
                    },
                    classes(type) {
                        const map = {
                            success: 'border-emerald-200 bg-emerald-50 text-emerald-900',
                            error: 'border-rose-200 bg-rose-50 text-rose-900',
                            info: 'border-sky-200 bg-sky-50 text-sky-900',
                        };

                        return map[type] ?? map.info;
                    },
                };
            },
        };

        document.addEventListener('DOMContentLoaded', () => {
            const revealItems = document.querySelectorAll('[data-reveal]');
            if (!('IntersectionObserver' in window) || revealItems.length === 0) {
                return;
            }

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.remove('translate-y-6', 'opacity-0');
                        entry.target.classList.add('translate-y-0', 'opacity-100');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12 });

            revealItems.forEach((element) => observer.observe(element));
        });
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>
