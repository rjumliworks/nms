<?php

namespace App\Http\Requests\Modules\Trips;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('list_names', 'name')
                    ->where(fn ($query) => $query->where('type', $this->type))
                    ->ignore($this->id),
            ],
            'type' => 'required|string|in:Boat,Carrier,Truck,Buyer,Person,Fish',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
