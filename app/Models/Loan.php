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
        'name_id',
        'is_paid',
        'is_cancelled',
    ];

    public function name()
    {
        return $this->belongsTo(ListName::class, 'name_id');
    }

    public function category()
    {
        return $this->belongsTo(ListDropdown::class, 'category_id');
    }
}
