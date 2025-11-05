<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teachers extends Model
{
    use HasFactory;

    protected $table = 'teachers';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'teacher_name',
        'teacher_specialization',
        'user_id',
        'school_id',
        'picture_path',
    ];

    /**
     * Get the user that owns the teacher profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the school institution that the teacher belongs to.
     */
    public function schoolsinstitution()
    {
        return $this->belongsTo(Schoolsinstitutions::class, 'school_id');
    }
}
