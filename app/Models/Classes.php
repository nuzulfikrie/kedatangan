<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    protected $table = 'classes';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'school_id',
        'child_id',
        'class_name',
    ];

    /**
     * Get the school institution that the class belongs to.
     */
    public function schoolsinstitution()
    {
        return $this->belongsTo(Schoolsinstitutions::class, 'school_id');
    }

    /**
     * Get the child that belongs to the class.
     */
    public function child()
    {
        return $this->belongsTo(Childs::class, 'child_id');
    }
}
