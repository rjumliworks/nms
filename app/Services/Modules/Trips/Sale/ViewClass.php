<?php

namespace App\Services\Modules\Trips\Sale;

use App\Models\Sale;
use App\Http\Resources\DefaultResource;

class ViewClass
{
    public function list($request)
    {
        $data = Sale::with('trip', 'buyer', 'truck', 'lists.tub')
        ->when($request->trip_id, function ($query, $tripId) {
            $query->where('trip_id', $tripId);
        })
        ->orderBy('created_at', 'desc')
        ->paginate($request->counts);

        return DefaultResource::collection($data);
    }
}
