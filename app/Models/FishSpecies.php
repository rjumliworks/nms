<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class FishSpecies extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name', 'local_name', 'english_name', 'tausug_name', 'scientific_name',
        'category_id', 'unit', 'is_active',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value ? ucwords(strtolower($value)) : $value;
    }

    public function category()
    {
        return $this->belongsTo(ListDropdown::class, 'category_id');
    }

    public function tubs()
    {
        return $this->hasMany(Tub::class, 'fish_id');
    }

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->logOnly([
            'name','local_name','english_name','tausug_name','scientific_name','category_id','unit','is_active'
        ])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}")
        ->useLogName('Fish Species')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
