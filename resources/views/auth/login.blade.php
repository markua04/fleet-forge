<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'FleetForge') }} &mdash; Sign in</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100">
        <div class="relative flex min-h-screen">
            <div class="relative hidden w-0 flex-1 overflow-hidden lg:block">
                <div class="absolute inset-0">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/80 via-fuchsia-500/70 to-slate-900"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/40 to-transparent"></div>
                </div>
                <div class="relative flex h-full flex-col justify-between p-12">
                    <div>
                        <span class="inline-flex items-center rounded-full bg-slate-900/50 px-4 py-1 text-sm font-medium text-slate-100/80">
                            FleetForge Command Center
                        </span>
                        <h1 class="mt-6 text-4xl font-semibold tracking-tight text-white">
                            Stay ahead of every mile.
                        </h1>
                        <p class="mt-4 max-w-md text-base text-slate-100/80">
                            Monitor vehicle utilization, spending, and assignments in real-time. FleetForge keeps your
                            operations running at peak efficiency.
                        </p>
                    </div>
                    <dl class="grid grid-cols-2 gap-6 text-slate-100/80">
                        <div>
                            <dt class="text-sm uppercase tracking-widest text-slate-200/70">Active Fleet</dt>
                            <dd class="mt-1 text-3xl font-semibold">120+</dd>
                        </div>
                        <div>
                            <dt class="text-sm uppercase tracking-widest text-slate-200/70">Downtime Reduced</dt>
                            <dd class="mt-1 text-3xl font-semibold">38%</dd>
                        </div>
                        <div>
                            <dt class="text-sm uppercase tracking-widest text-slate-200/70">Maintenance Alerts</dt>
                            <dd class="mt-1 text-3xl font-semibold">Instant</dd>
                        </div>
                        <div>
                            <dt class="text-sm uppercase tracking-widest text-slate-200/70">Support</dt>
                            <dd class="mt-1 text-3xl font-semibold">24/7</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="flex w-full flex-col justify-center px-6 py-12 sm:px-12 lg:w-[32rem] lg:px-16">
                <div class="mx-auto w-full max-w-md">
                    <div class="flex items-center gap-2 text-slate-300">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-500/20 text-2xl font-semibold text-indigo-300">
                            FF
                        </span>
                        <p class="text-sm font-medium uppercase tracking-[0.35em] text-slate-400">
                            FleetForge Platform
                        </p>
                    </div>
                    <h2 class="mt-8 text-3xl font-semibold tracking-tight text-white">
                        Welcome back, dispatcher
                    </h2>
                    <p class="mt-2 text-sm text-slate-400">
                        Sign in to access your vehicles, assignments, and real-time purchase activity.
                    </p>

                    <form method="POST" action="{{ route('login.store') }}" class="mt-10 space-y-6">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-300">
                                Email address
                            </label>
                            <div class="mt-2">
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    autofocus
                                    required
                                    class="block w-full rounded-xl border border-slate-700/50 bg-slate-900/60 px-4 py-3 text-base text-white shadow-[0_16px_32px_rgba(15,23,42,0.35)] ring-1 ring-transparent transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400/60"
                                    placeholder="you@fleetforge.io"
                                />
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-300">
                                Password
                            </label>
                            <div class="mt-2">
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    required
                                    class="block w-full rounded-xl border border-slate-700/50 bg-slate-900/60 px-4 py-3 text-base text-white shadow-[0_16px_32px_rgba(15,23,42,0.35)] ring-1 ring-transparent transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400/60"
                                    placeholder="Enter your password"
                                />
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between text-sm text-slate-400">
                            <label class="inline-flex items-center gap-2">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    value="1"
                                    @checked(old('remember'))
                                    class="h-4 w-4 rounded border-slate-600 bg-slate-900 text-indigo-500 focus:ring-indigo-400/60"
                                />
                                <span>Keep me signed in</span>
                            </label>
                            <a href="#" class="font-medium text-indigo-300 transition hover:text-indigo-200">
                                Forgot password?
                            </a>
                        </div>

                        <button
                            type="submit"
                            class="group relative flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500 px-6 py-3 text-base font-semibold text-white shadow-[0_20px_40px_rgba(79,70,229,0.35)] transition hover:scale-[1.01] hover:shadow-[0_24px_48px_rgba(88,28,135,0.35)] focus:outline-none focus-visible:ring-4 focus-visible:ring-indigo-400/60"
                        >
                            Access Fleet Dashboard
                            <svg class="ml-2 h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3" />
                            </svg>
                        </button>

                        <p class="text-center text-sm text-slate-500">
                            Need access? <a href="#" class="font-medium text-indigo-300 transition hover:text-indigo-200">Request an account</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
