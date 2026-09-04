<?php

namespace App\Services\Modules\Trips\Expense;

use App\Models\Expense;
use App\Http\Resources\DefaultResource;

class SaveClass
{
    public function store($request)
    {
        $data = Expense::create([
            'amount' => $request->amount,
            'note' => $request->note,
            'category_id' => $request->category_id,
            'name_id' => $request->name_id,
            'trip_id' => $request->trip_id,
        ]);

        return [
            'data' => new DefaultResource($data->load('name', 'category', 'trip')),
            'message' => 'Expense creation was successful!',
            'info' => "You've successfully recorded a new expense.",
        ];
    }

    public function update($request)
    {
        $data = Expense::findOrFail($request->id);
        $data->update($request->only('amount', 'note', 'category_id', 'name_id', 'trip_id'));

        return [
            'data' => new DefaultResource($data->load('name', 'category', 'trip')),
            'message' => 'Expense update was successful!',
            'info' => "You've successfully updated the selected expense.",
        ];
    }
}
