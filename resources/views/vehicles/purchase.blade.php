<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'FleetForge') }} &mdash; Purchase Vehicles</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
        @php
            $formatCurrency = static fn (float $value): string => number_format($value, 2, ',', '.');
            $listingImage = asset('images/truck1.png');
        @endphp

        <nav class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/80 backdrop-blur">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-500/10 text-lg font-semibold text-indigo-600">
                        FF
                    </span>
                    <div>
                        <p class="text-sm font-medium uppercase tracking-[0.4em] text-slate-500">
                            FleetForge
                        </p>
                        <p class="text-base font-semibold text-slate-900">
                            Vehicle Marketplace
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a
                        href="{{ route('vehicles.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-transparent px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-slate-200 hover:bg-white/80 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/60"
                    >
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/60"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M18 9l3 3m0 0-3 3m3-3H9" />
                            </svg>
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <main class="mx-auto max-w-6xl px-6 py-12">
            <header class="rounded-3xl bg-gradient-to-br from-indigo-500/10 via-white to-slate-100 p-8 shadow-sm">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-xl">
                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-emerald-600">
                            Marketplace
                        </span>
                        <h1 class="mt-4 text-4xl font-semibold tracking-tight text-slate-900">
                            Expand your fleet with confidence
                        </h1>
                        <p class="mt-3 text-base text-slate-600">
                            Browse curated, finance-ready vehicles. Each listing includes verified details, purchase price,
                            and role suggestions so you can deploy them instantly.
                        </p>
                    </div>
                    <div class="rounded-2xl border border-emerald-200 bg-white/80 p-4 text-sm text-emerald-700 shadow-sm">
                        <p class="font-semibold uppercase tracking-[0.3em] text-emerald-500">
                            Available Balance
                        </p>
                        <p class="mt-2 text-3xl font-semibold text-slate-900">
                            DKK {{ $formatCurrency((float) $user->cash) }}
                        </p>
                        <p class="mt-2 text-xs text-slate-500">
                            Cash updates instantly after each transaction. Keep at least 10% in reserve for maintenance.
                        </p>
                    </div>
                </div>
            </header>

            @error('vehicle_id')
                <div class="mt-8 rounded-2xl border border-rose-200 bg-rose-50/80 px-6 py-4 text-sm text-rose-700 shadow-sm">
                    <p class="font-semibold">{{ $message }}</p>
                </div>
            @enderror

            <section class="mt-12">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-slate-900">
                        Available vehicles
                    </h2>
                    <p class="text-sm text-slate-500">
                        {{ $vehicles->total() }} vehicle{{ $vehicles->total() === 1 ? '' : 's' }} ready for purchase
                    </p>
                </div>

                <div class="mt-6 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse ($vehicles as $vehicle)
                        <article class="flex flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_20px_45px_-30px_rgba(15,23,42,0.35)] transition hover:-translate-y-1 hover:shadow-[0_28px_60px_-28px_rgba(37,99,235,0.35)]">
                            <div class="relative h-48 w-full overflow-hidden">
                                <img
                                    src="{{ $listingImage }}"
                                    alt="{{ $vehicle->make }} {{ $vehicle->model }}"
                                    class="h-full w-full object-cover transition duration-500 hover:scale-105"
                                />
                                <div class="absolute left-4 top-4 inline-flex items-center gap-2 rounded-full bg-slate-900/80 px-3 py-1 text-xs font-semibold uppercase tracking-[0.3em] text-slate-100">
                                    {{ $vehicle->make }}
                                </div>
                            </div>
                            <div class="flex flex-1 flex-col px-6 py-6">
                                <h3 class="text-lg font-semibold text-slate-900">
                                    {{ $vehicle->make }} {{ $vehicle->model }}
                                </h3>
                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $vehicle->year ?? 'Year TBD' }} &bull; VIN: <span class="font-mono text-xs">{{ $vehicle->vin }}</span>
                                </p>
                                <p class="mt-4 line-clamp-3 text-sm text-slate-600">
                                    Purpose-built for Scandinavian long hauls, boasting aerodynamic efficiency, adaptive
                                    cruise control, and predictive maintenance alerts to keep your fleet on schedule.
                                </p>
                                <div class="mt-6 flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">
                                            Purchase price
                                        </p>
                                        <p class="mt-1 text-2xl font-semibold text-slate-900">
                                            DKK {{ $formatCurrency((float) $vehicle->price) }}
                                        </p>
                                    </div>
                                    <form method="POST" action="{{ route('vehicles.marketplace.store') }}" class="flex flex-col items-end gap-2">
                                        @csrf
                                        <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-2 rounded-lg bg-indigo-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400/60"
                                        >
                                            Purchase
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center gap-4 rounded-3xl border border-slate-200 bg-white px-10 py-20 text-center shadow-sm">
                            <span class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-3xl text-slate-400">
                                🚛
                            </span>
                            <h3 class="text-lg font-semibold text-slate-900">
                                All vehicles are currently sold
                            </h3>
                            <p class="max-w-md text-sm text-slate-500">
                                Check back soon as our marketplace updates throughout the day with new long-haul and regional-ready units.
                            </p>
                            <a
                                href="{{ route('vehicles.index') }}"
                                class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400/60"
                            >
                                Return to dashboard
                            </a>
                        </div>
                    @endforelse
                </div>

                <div class="mt-10">
                    {{ $vehicles->links() }}
                </div>
            </section>
        </main>
    </body>
</html>
