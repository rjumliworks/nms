<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    use HasFactory;

    protected $fillable = [
        'order',
        'total',
        'carrier_id',
        'trip_id',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }

    public function carrier()
    {
        return $this->belongsTo(ListName::class, 'carrier_id');
    }

    public function tubs()
    {
        return $this->hasMany(Tub::class, 'carrier_id');
    }
}
