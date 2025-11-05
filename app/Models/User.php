<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the teacher profile associated with the user.
     */
    public function teacher()
    {
        return $this->hasOne(Teachers::class);
    }

    /**
     * Get the parent profile associated with the user.
     */
    public function parent()
    {
        return $this->hasOne(Parents::class);
    }

    /**
     * Get the channels for the user.
     */
    public function channels()
    {
        return $this->hasMany(Channels::class);
    }

    /**
     * Get the user settings for the user.
     */
    public function usersettings()
    {
        return $this->hasMany(Usersettings::class);
    }

    /**
     * Get the school admin records for the user.
     */
    public function schoolsadmin()
    {
        return $this->hasMany(Schoolsadmin::class);
    }

    /**
     * Get the reminder templates created by the user (as admin).
     */
    public function remindersTemplates()
    {
        return $this->hasMany(RemindersTemplate::class, 'admin_id');
    }
}
