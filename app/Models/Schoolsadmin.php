<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schoolsadmin extends Model
{
    use HasFactory;

    protected $table = 'schools_admin';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'school_id',
    ];

    /**
     * Get the user that is a school admin.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the school institution that the admin manages.
     */
    public function schoolsinstitution()
    {
        return $this->belongsTo(Schoolsinstitutions::class, 'school_id');
    }
}
