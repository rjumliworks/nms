<?php

namespace App\Services\Dashboard;

use App\Models\Trip;
use App\Models\Expense;
use App\Models\Loan;
use App\Models\Sale;

class ViewClass
{
    public function summary()
    {
        return [
            'activeTrip' => Trip::with('boat', 'carriers.tubs')
                ->where('is_completed', 0)
                ->orderBy('date', 'desc')
                ->first(),
            'stats' => [
                'trips' => Trip::count(),
                'expenses' => (float) Expense::sum('amount'),
                'sales' => (float) Sale::sum('total'),
                'cashAdvanceUnpaid' => (float) Loan::where('is_paid', 0)->where('is_cancelled', 0)->sum('amount'),
            ],
            'recentExpenses' => Expense::with('name', 'category', 'trip')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
            'recentSales' => Sale::with('buyer', 'trip')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
            'cashAdvanceBreakdown' => [
                'paid' => Loan::where('is_cancelled', 0)->where('is_paid', 1)->count(),
                'unpaid' => Loan::where('is_cancelled', 0)->where('is_paid', 0)->count(),
            ],
        ];
    }
}
