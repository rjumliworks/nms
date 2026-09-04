<?php

namespace App\Models;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Storage;

class UserProfile extends Model
{
    use LogsActivity;

    protected $guarded = [];
    protected $fillable = [
        'lastname',
        'firstname',
        'middlename',
        'mobile',
        'mobile_hash',
        'avatar',
        'user_id',
    ];
    protected $appends = ['name','fullname'];
    protected $encryptable = [
        'firstname',
        'middlename',
        'mobile'
    ];
    protected $hidden = [
        'mobile_hash'
    ];

    public function user() { return $this->belongsTo(User::class); }

    public function getFullnameAttribute()
    {
        $middleInitial = $this->middlename ? strtoupper(mb_substr($this->middlename, 0, 1)) . '.' : '';
        return trim("{$this->firstname} {$middleInitial} {$this->lastname}");
    }

    public function getNameAttribute()
    {
        $middleInitial = $this->middlename ? strtoupper(mb_substr($this->middlename, 0, 1)) . '.' : '';
        $parts = [trim($this->lastname) . ',', trim($this->firstname), $middleInitial];
        return implode(' ', array_filter($parts));
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, ['firstname', 'middlename', 'lastname','mobile']) && !is_null($value)) {
            $value = ucwords(strtolower($value));
        }

        if (in_array($key, $this->encryptable) && !is_null($value) && $value !== '') {
            $value = Crypt::encryptString($value);
        }

        return parent::setAttribute($key, $value);
    }

    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        if (in_array($key, $this->encryptable) && !is_null($value)) {
            try {
                return ucwords(Crypt::decryptString($value));
            } catch (\Throwable $e) {
                return $value;
            }
        }
        return $value;
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            if (! empty($model->mobile)) {
                $plainMobile = $model->mobile;
                $normalized = preg_replace('/\D+/', '', $plainMobile);
                $model->mobile_hash = hash('sha256', $normalized);
            }
        });
    }

    public function getAvatarAttribute($value)
    {
        if ($value === 'noavatar.jpg') {
            return asset('images/avatars/' . $value);
        }

        return Storage::disk('public')->url($value);
    }

    protected static $recordEvents = ['updated'];
    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->logOnly([
            'firstname',
            'lastname',
            'middlename',
            'mobile',
            'mobile_hash',
            'avatar'
        ])
        ->setDescriptionForEvent(fn(string $eventName) => "$eventName the profile information")
        ->useLogName('User Profile')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }
}
