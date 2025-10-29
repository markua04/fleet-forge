<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'FleetForge') }} &mdash; My Vehicles</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
        @php
            $vehicleCount = $vehicles->count();
            $totalValue = $vehicles->sum(fn ($vehicle) => (float) $vehicle->price);
            $latestAssignment = $vehicles
                ->filter(fn ($vehicle) => $vehicle->pivot?->assigned_at)
                ->sortByDesc(fn ($vehicle) => $vehicle->pivot?->assigned_at)
                ->first();
            $formatCurrency = static fn (float $value): string => number_format($value, 2, ',', '.');
        @endphp

        <nav class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/80 backdrop-blur">
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
                            Vehicle Control Center
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden text-right sm:block">
                        <p class="text-sm font-medium text-slate-500">
                            Logged in as
                        </p>
                        <p class="text-sm font-semibold text-slate-900">
                            {{ $user->name }}
                        </p>
                    </div>
                    <a
                        href="{{ route('vehicles.marketplace') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-transparent px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:border-indigo-100 hover:bg-indigo-50/80 hover:text-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400/60"
                    >
                        Purchase vehicles
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/60"
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
            @if (session('status'))
                <div class="mb-8 rounded-2xl border border-emerald-200 bg-emerald-50/80 px-6 py-4 text-sm text-emerald-700 shadow-sm">
                    <p class="font-semibold">{{ session('status') }}</p>
                </div>
            @endif
            <header class="rounded-3xl bg-gradient-to-br from-indigo-500/10 via-white to-slate-100 p-8 shadow-sm">
                <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                    <div class="md:max-w-sm">
                        <span class="inline-flex items-center gap-2 rounded-full bg-indigo-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-indigo-600">
                            Fleet Overview
                        </span>
                        <h1 class="mt-4 text-3xl font-semibold tracking-tight text-slate-900">
                            Your owned vehicles at a glance
                        </h1>
                        <p class="mt-3 text-base text-slate-600">
                            Monitor each vehicle’s assignment, purchase value, and operational status.
                            Tap into deeper analytics to keep FleetForge running smoothly.
                        </p>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-2 lg:gap-6 lg:min-w-[22rem]">
                        <div class="rounded-2xl border border-slate-200 bg-white/90 p-4 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">
                                Remaining Balance
                            </p>
                            <p class="mt-2 text-2xl font-semibold text-slate-900 leading-tight break-words text-balance">
                                DKK {{ $formatCurrency((float) $user->cash) }}
                            </p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white/90 p-4 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">
                                Fleet Value
                            </p>
                            <p class="mt-2 text-2xl font-semibold text-slate-900 leading-tight break-words text-balance">
                                DKK {{ $formatCurrency($totalValue) }}
                            </p>
                        </div>
                    </div>
                </div>
            </header>

            <section class="mt-12 rounded-3xl border border-slate-200 bg-white/90 shadow-sm">
                <div class="flex flex-col gap-2 border-b border-slate-200 px-8 py-6 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">
                            Owned vehicles
                            <span class="text-sm font-medium text-slate-500">({{ $vehicleCount }})</span>
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Detailed view of every truck currently assigned to your team.
                        </p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    @if ($vehicles->isNotEmpty())
                        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                            <thead class="bg-slate-50/80 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                <tr>
                                    <th scope="col" class="px-8 py-4">Vehicle</th>
                                    <th scope="col" class="px-6 py-4">VIN</th>
                                    <th scope="col" class="px-6 py-4">Purchase Price</th>
                                    <th scope="col" class="px-6 py-4">Role</th>
                                    <th scope="col" class="px-6 py-4">Assigned</th>
                                    <th scope="col" class="px-6 py-4 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200/70 bg-white">
                                @foreach ($vehicles as $vehicle)
                                    @php
                                        $assignedAt = $vehicle->pivot?->assigned_at
                                            ? \Illuminate\Support\Carbon::parse($vehicle->pivot->assigned_at)
                                            : null;
                                    @endphp
                                    <tr class="transition hover:bg-slate-50/70">
                                        <td class="px-8 py-4 align-top">
                                            <div class="font-semibold text-slate-900">
                                                {{ $vehicle->make }} {{ $vehicle->model }}
                                            </div>
                                            <div class="mt-1 text-xs uppercase tracking-widest text-slate-500">
                                                {{ $vehicle->year ?? 'Year N/A' }} &bull; {{ strtoupper($vehicle->type ?? 'Vehicle') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 align-top font-mono text-xs text-slate-500">
                                            {{ $vehicle->vin }}
                                        </td>
                                        <td class="px-6 py-4 align-top font-semibold text-slate-900 break-words">
                                            DKK {{ $formatCurrency((float) $vehicle->price) }}
                                        </td>
                                        <td class="px-6 py-4 align-top">
                                            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-slate-600">
                                                {{ $vehicle->pivot?->role ?? 'Owner' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 align-top text-sm text-slate-600">
                                            {{ $assignedAt?->format('M j, Y g:i A') ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 align-top text-right">
                                            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.3em] text-emerald-600">
                                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                                Active
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="flex flex-col items-center justify-center gap-4 px-8 py-20 text-center">
                            <span class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-3xl text-slate-400">
                                🚚
                            </span>
                            <h3 class="text-lg font-semibold text-slate-900">
                                No vehicles assigned yet
                            </h3>
                            <p class="max-w-md text-sm text-slate-500">
                                Once you purchase a vehicle, it will appear here instantly with its role,
                                assignment timestamp, and purchase price.
                            </p>
                        </div>
                    @endif
                </div>
            </section>
        </main>
    </body>
</html>
