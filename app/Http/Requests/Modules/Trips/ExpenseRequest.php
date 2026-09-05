<?php

namespace App\Http\Requests\Modules\Trips;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'id' => 'required|integer|exists:expenses,id',
                'amount' => 'sometimes|required|numeric|min:0',
                'category_id' => 'sometimes|required|integer|exists:list_dropdowns,id',
                'employee_id' => 'nullable|integer|exists:employees,id',
                'trip_id' => 'sometimes|required|integer|exists:trips,id',
                'note' => 'nullable|string',
            ];
        }

        return [
            'amount' => 'required|numeric|min:0',
            'category_id' => 'required|integer|exists:list_dropdowns,id',
            'employee_id' => 'nullable|integer|exists:employees,id',
            'trip_id' => 'required|integer|exists:trips,id',
            'note' => 'nullable|string',
        ];
    }
}
