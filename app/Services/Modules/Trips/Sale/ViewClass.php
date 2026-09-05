<?php

namespace App\Services\Modules\Trips\Sale;

use App\Models\Sale;
use App\Http\Resources\DefaultResource;

class ViewClass
{
    public function list($request)
    {
        $data = Sale::with('trip', 'buyer', 'truck', 'lists.tub')
        ->when($request->keyword, function ($query, $keyword) {
            $query->whereHas('buyer', function ($sub) use ($keyword) {
                $sub->where('firstname', 'like', "%{$keyword}%")
                    ->orWhere('lastname', 'like', "%{$keyword}%");
            });
        })
        ->when($request->trip_id, function ($query, $tripId) {
            $query->where('trip_id', $tripId);
        })
        ->when($request->status, function ($query, $status) {
            match ($status) {
                'pending' => $query->whereNull('buyer_id'),
                'pending_payment' => $query->whereNotNull('buyer_id')->where('is_paid', 0),
                'paid' => $query->whereNotNull('buyer_id')->where('is_paid', 1),
                default => null,
            };
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
        ->orderByRaw('CASE WHEN buyer_id IS NULL THEN 0 WHEN is_paid = 0 THEN 1 ELSE 2 END')
        ->orderBy('created_at', 'desc')
        ->paginate($request->counts);

        return DefaultResource::collection($data);
    }

    public function stats($request = null)
    {
        $month = $request?->month;

        return [
            'total' => (float) Sale::sum('total'),
            'monthly' => (float) Sale::when($month, function ($query, $month) {
                    $query->whereMonth('created_at', $month);
                }, function ($query) {
                    $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                })
                ->sum('total'),
            'pending' => Sale::whereNull('buyer_id')
                ->when($month, function ($query, $month) {
                    $query->whereMonth('created_at', $month);
                })
                ->count(),
        ];
    }

    public function forPrint($request)
    {
        return Sale::with('trip', 'buyer', 'truck', 'lists.tub')
        ->when($request->type === 'pending', function ($query) {
            $query->whereNull('buyer_id');
        })
        ->when($request->type === 'sales', function ($query) {
            $query->whereNotNull('buyer_id');
        })
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

        $confirmedByMonth = Sale::whereYear('created_at', $year)
            ->whereNotNull('buyer_id')
            ->selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $pendingByMonth = Sale::whereYear('created_at', $year)
            ->whereNull('buyer_id')
            ->selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $months = range(1, 12);
        $confirmed = collect($months)->map(fn ($m) => (float) ($confirmedByMonth[$m] ?? 0))->values();
        $pending = collect($months)->map(fn ($m) => (float) ($pendingByMonth[$m] ?? 0))->values();
        $total = $confirmed->map(fn ($value, $i) => $value + $pending[$i])->values();

        return [
            'year' => $year,
            'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'total' => $total,
            'confirmed' => $confirmed,
            'pending' => $pending,
        ];
    }
}
