<?php

namespace App\Http\Requests\Modules\Trips;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'trip_id' => 'required|integer|exists:trips,id',
            'buyer_id' => 'required|integer|exists:buyers,id',
            'truck_id' => 'nullable|integer|exists:list_names,id',
            'lines' => 'required|array|min:1',
            'lines.*.tub_id' => 'required|integer|exists:tubs,id',
            'lines.*.quantity' => 'required|string',
            'lines.*.amount' => 'required|numeric|min:0',
        ];
    }
}
