<?php

namespace App\Services\Modules\Trips\Loan;

use App\Models\Loan;
use App\Http\Resources\DefaultResource;

class ViewClass
{
    public function list($request)
    {
        $data = Loan::with('employee', 'category', 'trip', 'payments')
        ->when($request->category_id, function ($query, $categoryId) {
            $query->where('category_id', $categoryId);
        })
        ->when($request->trip_id, function ($query, $tripId) {
            $query->where('trip_id', $tripId);
        })
        ->when($request->keyword, function ($query, $keyword) {
            $query->whereHas('employee', function ($sub) use ($keyword) {
                $sub->where('firstname', 'like', "%{$keyword}%")
                    ->orWhere('lastname', 'like', "%{$keyword}%");
            });
        })
        ->when($request->date, function ($query, $date) {
            $query->whereDate('created_at', $date);
        })
        ->when($request->trip_keyword, function ($query, $tripKeyword) {
            $query->whereHas('trip', function ($sub) use ($tripKeyword) {
                $sub->where('code', 'like', "%{$tripKeyword}%");
            });
        })
        ->when($request->month, function ($query, $month) {
            $query->whereMonth('created_at', $month);
        })
        ->orderByRaw('CASE WHEN is_cancelled = 1 THEN 2 WHEN is_paid = 0 THEN 0 ELSE 1 END')
        ->orderBy('created_at', 'desc')
        ->paginate($request->counts);

        return DefaultResource::collection($data);
    }

    public function stats($request = null)
    {
        $month = $request?->month;

        $scopeToMonth = function ($query) use ($month) {
            $query->when($month, function ($query, $month) {
                $query->whereMonth('created_at', $month);
            }, function ($query) {
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
            });
        };

        return [
            'total' => (float) Loan::where('is_cancelled', 0)->sum('amount'),
            'paid' => (float) Loan::where('is_cancelled', 0)->where('is_paid', 1)
                ->tap($scopeToMonth)
                ->sum('amount'),
            'notPaid' => (float) Loan::where('is_cancelled', 0)->where('is_paid', 0)
                ->tap($scopeToMonth)
                ->sum('amount'),
        ];
    }

    public function forPrint($request)
    {
        return Loan::with('employee', 'category', 'trip')
        ->when($request->month, function ($query, $month) {
            $query->whereMonth('created_at', $month);
        })
        ->when($request->year, function ($query, $year) {
            $query->whereYear('created_at', $year);
        })
        ->when($request->trip_keyword, function ($query, $tripKeyword) {
            $query->whereHas('trip', function ($sub) use ($tripKeyword) {
                $sub->where('code', 'like', "%{$tripKeyword}%");
            });
        })
        ->orderBy('created_at')
        ->get();
    }

    public function analytics($request)
    {
        $year = $request->year ?: now()->year;

        $byMonth = Loan::whereYear('created_at', $year)
            ->where('is_cancelled', 0)
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $months = range(1, 12);

        return [
            'year' => $year,
            'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'cashAdvances' => collect($months)->map(fn ($m) => (float) ($byMonth[$m] ?? 0))->values(),
        ];
    }
}
