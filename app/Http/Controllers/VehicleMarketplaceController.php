<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\VehicleRepository;
use App\Services\VehiclePurchaseService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VehicleMarketplaceController extends Controller
{
    public function __construct(
        private readonly VehicleRepository $vehicles,
        private readonly VehiclePurchaseService $purchaseService,
    ) {
    }

    public function index(Request $request): View
    {
        $user = $request->user();

        $availableVehicles = $this->vehicles->paginateAvailable();

        return view('vehicles.purchase', [
            'user' => $user,
            'vehicles' => $availableVehicles,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
        ]);

        $user = $request->user();

        try {
            $this->purchaseService->purchase(
                $user,
                (int) $data['vehicle_id']
            );
        } catch (\DomainException $exception) {
            return back()
                ->withInput()
                ->withErrors([
                    'vehicle_id' => $exception->getMessage(),
                ]);
        }

        return redirect()
            ->route('vehicles.index')
            ->with('status', 'Vehicle purchased successfully.');
    }
}
