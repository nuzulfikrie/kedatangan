<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Childs extends Model
{
    use HasFactory;

    protected $table = 'childs';
    protected $primaryKey = 'id';

    public function childParents()
    {
        return $this->hasMany(ChildParents::class);
        //how to access this - $child->childParents->parent_id
    }

    public function school()
    {
        return $this->belongsTo(SchoolsInstitutions::class, 'school_id', 'id');
        //how to access this - $child->school->name
    }
}
