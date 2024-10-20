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

    protected static $faker;


    public static function boot()
    {
        self::$faker = \Faker\Factory::create();
        //after create record
        parent::boot();
        static::created(function ($user) {
            if ($user->role === 'father' || $user->role === 'mother') {
                // Create corresponding parent record
                //use faker malaysia
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
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

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
