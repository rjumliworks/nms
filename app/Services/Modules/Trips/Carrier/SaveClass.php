<?php

namespace App\Services\Modules\Trips\Carrier;

use App\Models\Carrier;
use App\Http\Resources\DefaultResource;

class SaveClass
{
    public function store($request)
    {
        $count = Carrier::where('trip_id', $request->trip_id)->count();
        $order = $this->ordinal($count + 1);

        $carrier = Carrier::create([
            'order' => $order,
            'total' => collect($request->tubs)->sum('amount'),
            'carrier_id' => $request->carrier_id,
            'trip_id' => $request->trip_id,
        ]);

        foreach ($request->tubs as $tub) {
            $carrier->tubs()->create([
                'quantity' => $tub['quantity'],
                'amount' => $tub['amount'],
                'fish_id' => $tub['fish_id'] ?? null,
                'is_sold' => 0,
            ]);
        }

        return [
            'data' => new DefaultResource($carrier->load('carrier', 'tubs.fish')),
            'message' => 'Carrier creation was successful!',
            'info' => "You've successfully added a new carrier with its catch.",
        ];
    }

    protected function ordinal($number)
    {
        if (($number % 100) >= 11 && ($number % 100) <= 13) {
            return $number . 'th';
        }

        $suffixes = [1 => 'st', 2 => 'nd', 3 => 'rd'];

        return $number . ($suffixes[$number % 10] ?? 'th');
    }
}
