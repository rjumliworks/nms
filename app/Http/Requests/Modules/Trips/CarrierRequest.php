<?php

namespace App\Http\Requests\Modules\Trips;

use Illuminate\Foundation\Http\FormRequest;

class CarrierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'carrier_id' => 'required|integer|exists:list_names,id',
            'trip_id' => 'required|integer|exists:trips,id',
            'tubs' => 'required|array|min:1',
            'tubs.*.fish_id' => 'nullable|integer|exists:fish_species,id',
            'tubs.*.quantity' => 'required|string',
            'tubs.*.amount' => 'required|numeric|min:0',
        ];
    }
}
