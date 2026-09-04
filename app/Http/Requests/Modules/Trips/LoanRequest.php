<?php

namespace App\Http\Requests\Modules\Trips;

use Illuminate\Foundation\Http\FormRequest;

class LoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->option === 'pay') {
            return [
                'id' => 'required|integer|exists:loans,id',
            ];
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'id' => 'required|integer|exists:loans,id',
                'amount' => 'sometimes|required|numeric|min:0',
                'category_id' => 'sometimes|required|integer|exists:list_dropdowns,id',
                'employee_id' => 'nullable|integer|exists:employees,id',
            ];
        }

        return [
            'amount' => 'required|numeric|min:0',
            'category_id' => 'required|integer|exists:list_dropdowns,id',
            'employee_id' => 'nullable|integer|exists:employees,id',
        ];
    }
}
