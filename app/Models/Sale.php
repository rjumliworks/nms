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
    ];

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
