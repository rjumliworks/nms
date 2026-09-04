<?php

namespace App\Services\Modules\Trips\Employee;

use App\Models\Employee;
use App\Http\Resources\DefaultResource;

class SaveClass
{
    public function store($request)
    {
        $data = Employee::create($request->only('firstname', 'middlename', 'lastname'));

        return [
            'data' => new DefaultResource($data),
            'message' => 'Employee created successfully. Thanks',
            'info' => null,
            'status' => true,
        ];
    }

    public function update($request)
    {
        $data = Employee::findOrFail($request->id);
        $data->update($request->only('firstname', 'middlename', 'lastname', 'is_active'));

        return [
            'data' => new DefaultResource($data),
            'message' => 'Employee updated successfully. Thanks',
            'info' => null,
            'status' => true,
        ];
    }
}
