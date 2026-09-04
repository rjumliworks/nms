<?php

namespace App\Http\Requests\Modules\Trips;

use Illuminate\Foundation\Http\FormRequest;

class TripRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'id' => 'required|integer|exists:trips,id',
                'boat_id' => 'sometimes|required|integer|exists:list_names,id',
                'date' => 'sometimes|required|date',
                'note' => 'nullable|string',
                'has_departed' => 'sometimes|boolean',
                'is_completed' => 'sometimes|boolean',
            ];
        }

        return [
            'boat_id' => 'required|integer|exists:list_names,id',
            'date' => 'required|date',
            'note' => 'nullable|string',
        ];
    }
}
