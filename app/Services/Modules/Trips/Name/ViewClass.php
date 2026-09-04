<?php

namespace App\Services\Modules\Trips\Name;

use App\Models\ListName;
use App\Http\Resources\DefaultResource;

class ViewClass
{
    const TYPES = ['Boat', 'Carrier', 'Truck', 'Buyer', 'Person', 'Fish'];

    public function list($request)
    {
        $data = ListName::when($request->type, function ($query, $type) {
            $query->where('type', $type);
        })
        ->when($request->keyword, function ($query, $keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        })
        ->orderBy('name')
        ->paginate($request->counts);

        return DefaultResource::collection($data);
    }
}
