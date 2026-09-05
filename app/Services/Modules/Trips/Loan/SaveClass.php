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
            'trip_id' => $request->trip_id,
            'is_paid' => 0,
            'is_cancelled' => 0,
        ]);

        return [
            'data' => new DefaultResource($data->load('employee', 'category', 'trip', 'payments')),
            'message' => 'Cash advance creation was successful!',
            'info' => "You've successfully recorded a new cash advance.",
        ];
    }

    public function update($request)
    {
        $data = Loan::findOrFail($request->id);
        $data->update($request->only('amount', 'category_id', 'employee_id', 'trip_id'));

        return [
            'data' => new DefaultResource($data->load('employee', 'category', 'trip', 'payments')),
            'message' => 'Cash advance update was successful!',
            'info' => "You've successfully updated the selected cash advance.",
        ];
    }

    public function pay($request)
    {
        $loan = Loan::findOrFail($request->id);
        $balance = $loan->balance;

        if ($balance > 0) {
            $loan->payments()->create([
                'amount' => $balance,
                'note' => 'Marked as fully paid.',
            ]);
        }

        $loan->is_paid = 1;
        $loan->save();

        return [
            'data' => new DefaultResource($loan->load('employee', 'category', 'trip', 'payments')),
            'message' => 'Cash advance marked as paid!',
            'info' => "You've successfully marked the selected cash advance as paid.",
        ];
    }

    public function recordPayment($request)
    {
        $loan = Loan::findOrFail($request->id);
        // LoanRequest already rejects amounts over the remaining balance;
        // clamp defensively here too in case the balance shifted since validation.
        $amount = min((float) $request->amount, $loan->balance);

        $loan->payments()->create([
            'amount' => $amount,
            'note' => $request->note,
        ]);

        $loan->refresh();
        if ($loan->balance <= 0) {
            $loan->is_paid = 1;
            $loan->save();
        }

        return [
            'data' => new DefaultResource($loan->load('employee', 'category', 'trip', 'payments')),
            'message' => 'Payment recorded successfully!',
            'info' => "You've successfully recorded a payment for this cash advance.",
        ];
    }
}
