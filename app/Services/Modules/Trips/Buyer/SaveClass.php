<?php

namespace App\Services\Modules\Trips\Buyer;

use App\Models\Buyer;
use App\Http\Resources\DefaultResource;

class SaveClass
{
    public function store($request)
    {
        $data = Buyer::create($request->only('firstname', 'middlename', 'lastname'));

        return [
            'data' => new DefaultResource($data),
            'message' => 'Buyer created successfully. Thanks',
            'info' => null,
            'status' => true,
        ];
    }

    public function update($request)
    {
        $data = Buyer::findOrFail($request->id);
        $data->update($request->only('firstname', 'middlename', 'lastname', 'is_active'));

        return [
            'data' => new DefaultResource($data),
            'message' => 'Buyer updated successfully. Thanks',
            'info' => null,
            'status' => true,
        ];
    }
}
