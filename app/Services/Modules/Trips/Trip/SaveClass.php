<?php

namespace App\Services\Modules\Trips\Trip;

use App\Models\Trip;
use App\Http\Resources\DefaultResource;

class SaveClass
{
    public function store($request)
    {
        $active = Trip::where('has_departed', 0)->where('is_completed', 0)->count();
        if ($active > 0) {
            throw new \Exception('There is already an active trip. Please complete or depart it before creating a new one.');
        }

        Trip::where('is_completed', 0)->update(['is_completed' => 1]);

        $sequence = Trip::whereDate('created_at', now()->toDateString())->count() + 1;
        $code = 'TRP' . now()->format('Ymd') . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        $data = Trip::create([
            'code' => $code,
            'date' => $request->date,
            'note' => $request->note,
            'boat_id' => $request->boat_id,
            'has_departed' => 0,
            'is_completed' => 0,
        ]);

        return [
            'data' => new DefaultResource($data->load('boat')),
            'message' => 'Trip creation was successful!',
            'info' => "You've successfully created a new trip.",
        ];
    }

    public function update($request)
    {
        $data = Trip::findOrFail($request->id);
        $data->update($request->only('boat_id', 'date', 'note', 'has_departed', 'is_completed'));

        return [
            'data' => new DefaultResource($data->load('boat')),
            'message' => 'Trip update was successful!',
            'info' => "You've successfully updated the selected trip.",
        ];
    }
}
