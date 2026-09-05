<?php

namespace App\Http\Requests\Modules\Trips;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FishSpeciesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:150',
                Rule::unique('fish_species', 'name')->ignore($this->id),
            ],
            'local_name' => 'nullable|string|max:150',
            'english_name' => 'nullable|string|max:150',
            'tausug_name' => 'nullable|string|max:150',
            'scientific_name' => 'nullable|string|max:150',
            'category_id' => 'nullable|integer|exists:list_dropdowns,id',
            'unit' => 'nullable|string|max:20',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
