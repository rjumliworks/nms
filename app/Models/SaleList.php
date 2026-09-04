<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleList extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'amount',
        'sale_id',
        'tub_id',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function tub()
    {
        return $this->belongsTo(Tub::class, 'tub_id');
    }
}
