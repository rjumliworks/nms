<?php

namespace App\Services\Modules\Trips\Employee;

use App\Models\Employee;
use App\Http\Resources\DefaultResource;

class ViewClass
{
    public function list($request)
    {
        $data = Employee::when($request->keyword, function ($query, $keyword) {
            $query->where(function ($sub) use ($keyword) {
                $sub->where('firstname', 'like', "%{$keyword}%")
                    ->orWhere('lastname', 'like', "%{$keyword}%");
            });
        })
        ->orderBy('lastname')
        ->orderBy('firstname')
        ->paginate($request->counts);

        return DefaultResource::collection($data);
    }
}
