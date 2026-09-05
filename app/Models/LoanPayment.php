<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LoanPayment extends Model
{
    use LogsActivity;

    protected $fillable = [
        'loan_id', 'amount', 'note',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->logOnly([
            'loan_id','amount','note'
        ])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}")
        ->useLogName('Cash Advance Payment')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
