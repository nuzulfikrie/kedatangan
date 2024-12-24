<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected static $faker;

    public static function boot()
    {
        parent::boot();
        self::$faker = \Faker\Factory::create();

        static::creating(function ($user) {
            $user->status = $user->status ?? 'active';
        });

        static::created(function ($user) {
            if ($user->role === 'father' || $user->role === 'mother') {
                Parents::create([
                    'parent_name' => $user->name,
                    'phone_number' => null,
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'picture_path' => self::$faker->imageUrl(640, 480, 'people'),
                    'race' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_admin',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the is_admin attribute.
     */
    protected function isAdmin(): Attribute
    {
        return Attribute::make(
            get: fn() => (bool) $this->attributes['is_admin'],
        );
    }

    /**
     * Get the is_active attribute.
     */
    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: fn() => ($this->attributes['status'] ?? 'inactive') === 'active',
        );
    }

    public function parent()
    {
        $this->hasOne(
            Parents::class,
            'user_id',
            'id'
        );
    }

    public function teacher()
    {
        $this->hasOne(
            Teachers::class,
            'user_id',
            'id'
        );
    }

    public function schools()
    {
        if ($this->role == 'school_admin') {

            return $this->hasMany(
                Schoolsadmin::class,
                'school_admin_id',
                'id'
            );
        }
    }

    public  function schoolsAdmin()
    {
        $this->hasMany(
            Schoolsadmin::class,
            'school_admin_id',
            'id'
        );
    }

    public function students()
    {
        if ($this->role == 'school_admin') {
            return $this->hasManyThrough(
                PivotClassChild::class,
                Schoolsadmin::class,
                'school_admin_id',
                'school_id',
                'id',
                'school_id'
            );
        }
    }
}
