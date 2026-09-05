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
            'tripBreakdown' => [
                'preparing' => Trip::where('has_departed', 0)->where('is_completed', 0)->count(),
                'ongoing' => Trip::where('has_departed', 1)->where('is_completed', 0)->count(),
                'completed' => Trip::where('is_completed', 1)->count(),
            ],
            'salesBreakdown' => [
                'total' => Sale::count(),
                'pending' => Sale::whereNull('buyer_id')->count(),
                'pendingPayment' => Sale::whereNotNull('buyer_id')->where('is_paid', 0)->count(),
                'paid' => Sale::whereNotNull('buyer_id')->where('is_paid', 1)->count(),
            ],
            'cashAdvanceBreakdown' => [
                'total' => Loan::where('is_cancelled', 0)->count(),
                'paid' => Loan::where('is_cancelled', 0)->where('is_paid', 1)->count(),
                'unpaid' => Loan::where('is_cancelled', 0)->where('is_paid', 0)->count(),
            ],
            'recentExpenses' => Expense::with('employee', 'category', 'trip')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
            'recentSales' => Sale::with('buyer', 'trip')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
        ];
    }
}
