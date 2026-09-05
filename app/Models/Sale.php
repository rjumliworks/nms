<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'total',
        'buyer_id',
        'truck_id',
        'trip_id',
        'is_sold',
        'is_paid',
    ];

    protected $appends = ['status'];

    public function getStatusAttribute()
    {
        if (!$this->buyer_id) {
            return 'Pending';
        }

        return $this->is_paid ? 'Paid' : 'Pending Payment';
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id');
    }

    public function truck()
    {
        return $this->belongsTo(ListName::class, 'truck_id');
    }

    public function lists()
    {
        return $this->hasMany(SaleList::class, 'sale_id');
    }
}
