<?php

namespace App\Services\Modules\Trips\Name;

use App\Models\ListName;
use App\Http\Resources\DefaultResource;

class SaveClass
{
    public function store($request)
    {
        $data = ListName::create($request->only('name', 'type'));

        return [
            'data' => new DefaultResource($data),
            'message' => 'Name created successfully. Thanks',
            'info' => null,
            'status' => true,
        ];
    }

    public function update($request)
    {
        $data = ListName::findOrFail($request->id);
        $data->update($request->only('name', 'type', 'is_active'));

        return [
            'data' => new DefaultResource($data),
            'message' => 'Name updated successfully. Thanks',
            'info' => null,
            'status' => true,
        ];
    }
}
