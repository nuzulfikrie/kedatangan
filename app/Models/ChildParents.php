<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildParents extends Model
{
    use HasFactory;

    protected $table = 'child_parents';
    protected $primaryKey = 'id';

    public function child()
    {
        return $this->belongsTo(Childs::class);
        //how to access this - $child->childParents->parent_id
    }

    public function parent()
    {
        return $this->belongsTo(Parents::class);
        //how to access this - $parent->childParents->child_id
    }
}
