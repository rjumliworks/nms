<?php

namespace App\Services\Modules\Trips\Trip;

use App\Models\Trip;
use App\Http\Resources\DefaultResource;

class ViewClass
{
    public function list($request)
    {
        $data = Trip::with('boat', 'carriers.carrier', 'carriers.tubs.fish', 'expenses', 'sales')
        ->when($request->keyword, function ($query, $keyword) {
            $query->where('code', 'like', "%{$keyword}%");
        })
        ->orderBy('date', 'desc')
        ->orderBy('id', 'desc')
        ->paginate($request->counts);

        return DefaultResource::collection($data);
    }

    public function show($id)
    {
        $data = Trip::with('boat', 'carriers.carrier', 'carriers.tubs.fish')->findOrFail($id);

        return new DefaultResource($data);
    }
}
