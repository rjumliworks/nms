<?php

namespace App\Services\Modules\Trips\FishSpecies;

use App\Models\FishSpecies;
use App\Http\Resources\DefaultResource;

class ViewClass
{
    public function list($request)
    {
        $data = FishSpecies::with('category')
        ->when($request->category_id, function ($query, $categoryId) {
            $query->where('category_id', $categoryId);
        })
        ->when($request->keyword, function ($query, $keyword) {
            $query->where(function ($sub) use ($keyword) {
                $sub->where('name', 'like', "%{$keyword}%")
                    ->orWhere('local_name', 'like', "%{$keyword}%")
                    ->orWhere('english_name', 'like', "%{$keyword}%")
                    ->orWhere('tausug_name', 'like', "%{$keyword}%")
                    ->orWhere('scientific_name', 'like', "%{$keyword}%");
            });
        })
        ->orderBy('name')
        ->paginate($request->counts);

        return DefaultResource::collection($data);
    }
}
