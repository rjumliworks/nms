<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListDropdown extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name', 'classification', 'type', 'color', 'others', 'is_active',
    ];
}
