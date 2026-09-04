<?php

namespace App\Services\Modules\Trips\Loan;

use App\Models\Loan;
use App\Http\Resources\DefaultResource;

class SaveClass
{
    public function store($request)
    {
        $data = Loan::create([
            'amount' => $request->amount,
            'category_id' => $request->category_id,
            'employee_id' => $request->employee_id,
            'is_paid' => 0,
            'is_cancelled' => 0,
        ]);

        return [
            'data' => new DefaultResource($data->load('employee', 'category')),
            'message' => 'Cash advance creation was successful!',
            'info' => "You've successfully recorded a new cash advance.",
        ];
    }

    public function update($request)
    {
        $data = Loan::findOrFail($request->id);
        $data->update($request->only('amount', 'category_id', 'employee_id'));

        return [
            'data' => new DefaultResource($data->load('employee', 'category')),
            'message' => 'Cash advance update was successful!',
            'info' => "You've successfully updated the selected cash advance.",
        ];
    }

    public function pay($request)
    {
        $data = Loan::findOrFail($request->id);
        $data->is_paid = 1;
        $data->save();

        return [
            'data' => new DefaultResource($data->load('employee', 'category')),
            'message' => 'Cash advance marked as paid!',
            'info' => "You've successfully marked the selected cash advance as paid.",
        ];
    }
}
