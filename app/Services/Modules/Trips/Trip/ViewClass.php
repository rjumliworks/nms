<?php

namespace App\Services\Modules\Trips\Trip;

use App\Models\Trip;
use App\Http\Resources\DefaultResource;

class ViewClass
{
    public function list($request)
    {
        $data = Trip::with('boat', 'carriers.carrier', 'carriers.tubs.fish', 'expenses', 'sales')
        ->when($request->keyword, function ($query, $keyword) {
            $query->where('code', 'like', "%{$keyword}%");
        })
        ->when($request->status, function ($query, $status) {
            match ($status) {
                'preparing' => $query->where('has_departed', 0)->where('is_completed', 0),
                'ongoing' => $query->where('has_departed', 1)->where('is_completed', 0),
                'completed' => $query->where('is_completed', 1),
                default => null,
            };
        })
        ->when($request->date, function ($query, $date) {
            $query->whereDate('date', $date);
        })
        ->when($request->month, function ($query, $month) {
            $query->whereMonth('date', $month);
        })
        ->orderByRaw('CASE WHEN is_completed = 1 THEN 2 WHEN has_departed = 1 THEN 1 ELSE 0 END')
        ->orderBy('date', 'desc')
        ->orderBy('id', 'desc')
        ->paginate($request->counts);

        return DefaultResource::collection($data);
    }

    public function show($id)
    {
        $data = Trip::with(
            'boat',
            'carriers.carrier',
            'carriers.tubs.fish',
            'expenses.employee',
            'expenses.category',
            'sales.buyer',
            'sales.truck',
            'sales.lists.tub',
            'loans.employee',
            'loans.category',
            'loans.payments'
        )->findOrFail($id);

        return new DefaultResource($data);
    }

    public function stats($request = null)
    {
        $month = $request?->month;

        $scopeToMonth = function ($query) use ($month) {
            $query->when($month, function ($query, $month) {
                $query->whereMonth('date', $month);
            }, function ($query) {
                $query->whereMonth('date', now()->month)
                    ->whereYear('date', now()->year);
            });
        };

        return [
            'total' => Trip::count(),
            'ongoing' => Trip::where('has_departed', 1)->where('is_completed', 0)
                ->tap($scopeToMonth)
                ->count(),
            'completed' => Trip::where('is_completed', 1)
                ->tap($scopeToMonth)
                ->count(),
        ];
    }

    public function forPrint($request)
    {
        return Trip::with('boat', 'carriers')
        ->when($request->month, function ($query, $month) {
            $query->whereMonth('date', $month);
        })
        ->when($request->year, function ($query, $year) {
            $query->whereYear('date', $year);
        })
        ->orderBy('date')
        ->get();
    }

    public function analytics($request)
    {
        $year = $request->year ?: now()->year;

        $totalByMonth = Trip::whereYear('date', $year)
            ->selectRaw('MONTH(date) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $ongoingByMonth = Trip::whereYear('date', $year)
            ->where('has_departed', 1)->where('is_completed', 0)
            ->selectRaw('MONTH(date) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $completedByMonth = Trip::whereYear('date', $year)
            ->where('is_completed', 1)
            ->selectRaw('MONTH(date) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $months = range(1, 12);

        return [
            'year' => $year,
            'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'total' => collect($months)->map(fn ($m) => (int) ($totalByMonth[$m] ?? 0))->values(),
            'ongoing' => collect($months)->map(fn ($m) => (int) ($ongoingByMonth[$m] ?? 0))->values(),
            'completed' => collect($months)->map(fn ($m) => (int) ($completedByMonth[$m] ?? 0))->values(),
        ];
    }
}
