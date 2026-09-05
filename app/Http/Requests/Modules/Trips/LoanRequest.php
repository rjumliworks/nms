<?php

namespace App\Http\Requests\Modules\Trips;

use App\Models\Loan;
use Illuminate\Foundation\Http\FormRequest;

class LoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->option === 'payment' && $this->id && $this->amount) {
                $loan = Loan::find($this->id);
                if ($loan && (float) $this->amount > $loan->balance) {
                    $validator->errors()->add(
                        'amount',
                        'Payment amount cannot exceed the remaining balance of ' . number_format($loan->balance, 2) . '.'
                    );
                }
            }
        });
    }

    public function rules(): array
    {
        if ($this->option === 'pay') {
            return [
                'id' => 'required|integer|exists:loans,id',
            ];
        }

        if ($this->option === 'payment') {
            return [
                'id' => 'required|integer|exists:loans,id',
                'amount' => 'required|numeric|min:0.01',
                'note' => 'nullable|string|max:255',
            ];
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'id' => 'required|integer|exists:loans,id',
                'amount' => 'sometimes|required|numeric|min:0',
                'category_id' => 'sometimes|required|integer|exists:list_dropdowns,id',
                'employee_id' => 'nullable|integer|exists:employees,id',
                'trip_id' => 'nullable|integer|exists:trips,id',
            ];
        }

        return [
            'amount' => 'required|numeric|min:0',
            'category_id' => 'required|integer|exists:list_dropdowns,id',
            'employee_id' => 'nullable|integer|exists:employees,id',
            'trip_id' => 'nullable|integer|exists:trips,id',
        ];
    }
}
