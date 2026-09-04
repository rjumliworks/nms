<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tub extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'amount',
        'is_sold',
        'fish_id',
        'carrier_id',
    ];

    public function carrier()
    {
        return $this->belongsTo(Carrier::class, 'carrier_id');
    }

    public function fish()
    {
        return $this->belongsTo(ListName::class, 'fish_id');
    }

    public function saleLists()
    {
        return $this->hasMany(SaleList::class, 'tub_id');
    }
}
