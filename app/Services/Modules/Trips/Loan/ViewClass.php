<?php

namespace App\Services\Modules\Trips\Loan;

use App\Models\Loan;
use App\Http\Resources\DefaultResource;

class ViewClass
{
    public function list($request)
    {
        $data = Loan::with('employee', 'category')
        ->when($request->category_id, function ($query, $categoryId) {
            $query->where('category_id', $categoryId);
        })
        ->when($request->keyword, function ($query, $keyword) {
            $query->whereHas('employee', function ($sub) use ($keyword) {
                $sub->where('firstname', 'like', "%{$keyword}%")
                    ->orWhere('lastname', 'like', "%{$keyword}%");
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate($request->counts);

        return DefaultResource::collection($data);
    }

    public function breakdown()
    {
        $categories = Loan::where('is_cancelled', 0)
        ->selectRaw('category_id, is_paid, COUNT(*) as count, SUM(amount) as total')
        ->groupBy('category_id', 'is_paid')
        ->with('category')
        ->get();

        return [
            'paid' => [
                'count' => Loan::where('is_cancelled', 0)->where('is_paid', 1)->count(),
                'total' => Loan::where('is_cancelled', 0)->where('is_paid', 1)->sum('amount'),
            ],
            'unpaid' => [
                'count' => Loan::where('is_cancelled', 0)->where('is_paid', 0)->count(),
                'total' => Loan::where('is_cancelled', 0)->where('is_paid', 0)->sum('amount'),
            ],
            'categories' => $categories,
        ];
    }
}
