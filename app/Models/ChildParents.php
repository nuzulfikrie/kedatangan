<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildParents extends Model
{
    use HasFactory;

    protected $table = 'child_parents';
    protected $primaryKey = 'id';

    protected $fillable = [
        'child_id',
        'parent_id',
        'active',
        'created_at',
        'updated_at'
    ];

    //cast 


    public function child()
    {
        return $this->hasOne(Childs::class, 'id', 'child_id');
    }

    public function schools()
    {
        return $this->hasManyThrough(
            SchoolsInstitutions::class,
            Childs::class,
            'id',
            'id',
            'child_id',
            'school_id'

        );
    }

    public function parent()
    {
        return $this->belongsTo(Parents::class);
        //how to access this - $parent->childParents->child_id
    }
}
