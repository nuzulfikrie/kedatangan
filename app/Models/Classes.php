<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    protected $table = 'classes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'school_id',
        'child_id',
        'class_name',
        'created_at',
        'updated_at'
    ];

    public function school()
    {
        $this->belongsTo(Schoolsinstitutions::class, 'school_id', 'id');
    }

    public function student()
    {
        $this->hasMany(Childs::class, 'child_id', 'id');
    }

    public function admins()
    {
        $this->hasMany(Schoolsadmin::class, 'school_id', 'id');
    }

    public function teachers()
    {
        // school teacher, use school id
        $this->hasManyThrough(Teachers::class, Schoolsinstitutions::class, 'school_id', 'id', 'school_id', 'id');
    }
}
