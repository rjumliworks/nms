<?php

namespace App\Services\Modules\Trips\Sale;

use App\Models\Sale;
use App\Models\Tub;
use App\Http\Resources\DefaultResource;

class SaveClass
{
    public function store($request)
    {
        $total = collect($request->lines)->sum('amount');

        $sale = Sale::create([
            'trip_id' => $request->trip_id,
            'buyer_id' => $request->buyer_id,
            'truck_id' => $request->truck_id,
            'total' => $total,
            'is_sold' => 1,
        ]);

        foreach ($request->lines as $line) {
            $sale->lists()->create([
                'quantity' => $line['quantity'],
                'amount' => $line['amount'],
                'tub_id' => $line['tub_id'],
            ]);
        }

        Tub::whereIn('id', collect($request->lines)->pluck('tub_id'))->update(['is_sold' => 1]);

        return [
            'data' => new DefaultResource($sale->load('trip', 'buyer', 'truck', 'lists.tub')),
            'message' => 'Sale creation was successful!',
            'info' => "You've successfully recorded a new sale.",
        ];
    }

    public function update($request)
    {
        $sale = Sale::findOrFail($request->id);
        $sale->update($request->only('buyer_id', 'truck_id', 'is_paid'));

        return [
            'data' => new DefaultResource($sale->load('trip', 'buyer', 'truck', 'lists.tub')),
            'message' => 'Sale updated successfully!',
            'info' => "You've successfully updated the selected sale.",
        ];
    }
}
