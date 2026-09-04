<?php

namespace App\Services\Modules\Trips\Expense;

use App\Models\Expense;
use App\Http\Resources\DefaultResource;

class ViewClass
{
    public function list($request)
    {
        $data = Expense::with('name', 'category', 'trip')
        ->when($request->category_id, function ($query, $categoryId) {
            $query->where('category_id', $categoryId);
        })
        ->when($request->keyword, function ($query, $keyword) {
            $query->whereHas('name', function ($sub) use ($keyword) {
                $sub->where('name', 'like', "%{$keyword}%");
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate($request->counts);

        return DefaultResource::collection($data);
    }
}
