<?php

namespace App\Services\Modules\Trips\FishSpecies;

use App\Models\FishSpecies;
use App\Http\Resources\DefaultResource;

class SaveClass
{
    public function store($request)
    {
        $data = FishSpecies::create($request->only(
            'name', 'local_name', 'english_name', 'tausug_name', 'scientific_name', 'category_id', 'unit'
        ));

        return [
            'data' => new DefaultResource($data->load('category')),
            'message' => 'Fish species created successfully. Thanks',
            'info' => null,
            'status' => true,
        ];
    }

    public function update($request)
    {
        $data = FishSpecies::findOrFail($request->id);
        $data->update($request->only(
            'name', 'local_name', 'english_name', 'tausug_name', 'scientific_name', 'category_id', 'unit', 'is_active'
        ));

        return [
            'data' => new DefaultResource($data->load('category')),
            'message' => 'Fish species updated successfully. Thanks',
            'info' => null,
            'status' => true,
        ];
    }
}
