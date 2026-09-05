<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'date',
        'note',
        'has_departed',
        'is_completed',
        'boat_id',
    ];

    public function boat()
    {
        return $this->belongsTo(ListName::class, 'boat_id');
    }

    public function carriers()
    {
        return $this->hasMany(Carrier::class, 'trip_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'trip_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'trip_id');
    }

    public function loans()
    {
        return $this->hasMany(Loan::class, 'trip_id');
    }
}
