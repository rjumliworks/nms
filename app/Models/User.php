<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Crypt;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, LogsActivity;

    protected $fillable = [
        'username',
        'email',
        'kradworkz',
        'password',
        'code',
        'is_active',
        'is_locked',
        'must_change',
        'password_changed_at',
    ];

    protected $hidden = [
        'kradworkz',
        'password',
        'remember_token',
        'code',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function profile()
    {
        return $this->hasOne('App\Models\UserProfile', 'user_id');
    }

    public function myroles()
    {
        return $this->hasMany('App\Models\UserRole', 'user_id')->orderBy('is_active','DESC');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Models\ListRole', 'user_roles', 'user_id', 'role_id');
    }

    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function setEmailAttribute($value)
    {
        $email = strtolower($value);
        $this->attributes['email'] = Crypt::encryptString($email);
        $this->attributes['kradworkz'] = hash('sha256', $email);
    }

    public function getEmailAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function getLastLoginAtAttribute($value)
    {
        return $value ? date('M d, Y g:i a', strtotime($value)) : null;
    }

    public function getPasswordChangedAtAttribute($value)
    {
        return $value ? date('F d, Y g:i a', strtotime($value)) : null;
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('F d, Y g:i a', strtotime($value));
    }

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
        ->logOnly(['username','kradworkz','is_active','must_change'])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} the user information")
        ->useLogName('User')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
    }

    public function activities()
    {
        $this->loadMissing('profile');

        $profileId = $this->profile?->id;

        return Activity::with('causer:id', 'causer.profile:user_id,firstname,lastname,middlename')
        ->where(function ($query) use ($profileId) {
            $query->where('subject_type', User::class)->where('subject_id', $this->id);

            if ($profileId) {
                $query->orWhere(function ($q) use ($profileId) {
                    $q->where('subject_type', UserProfile::class)->where('subject_id', $profileId);
                });
            }
        })
        ->orderBy('created_at', 'desc')->orderBy('id', 'desc');
    }
}
