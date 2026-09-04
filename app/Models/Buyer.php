<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Buyer extends Model
{
    use LogsActivity;

    protected $fillable = [
        'firstname', 'middlename', 'lastname', 'is_active',
    ];

    protected $appends = ['name'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function getNameAttribute()
    {
        $middleInitial = $this->middlename ? ' ' . strtoupper(mb_substr($this->middlename, 0, 1)) . '.' : '';
        return trim("{$this->lastname}, {$this->firstname}{$middleInitial}");
    }

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->logOnly([
            'firstname','middlename','lastname','is_active'
        ])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}")
        ->useLogName('Buyer')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
