<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'category_id',
        'employee_id',
        'trip_id',
        'is_paid',
        'is_cancelled',
    ];

    protected $appends = ['paid_amount', 'balance'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function category()
    {
        return $this->belongsTo(ListDropdown::class, 'category_id');
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }

    public function payments()
    {
        return $this->hasMany(LoanPayment::class)->orderBy('created_at', 'desc');
    }

    public function getPaidAmountAttribute()
    {
        if ($this->relationLoaded('payments')) {
            return (float) $this->payments->sum('amount');
        }

        return (float) $this->payments()->sum('amount');
    }

    public function getBalanceAttribute()
    {
        return max(0, (float) $this->amount - $this->paid_amount);
    }
}
