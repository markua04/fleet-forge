<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'role' => ['nullable', 'string', 'max:32'],
        ];
    }

    public function role(): string
    {
        return $this->input('role', 'owner');
    }
}
