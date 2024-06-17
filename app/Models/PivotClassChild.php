<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PivotClassChild extends Model
{
    use HasFactory;

    protected $table = 'pivot_class_child';

    public function class()
    {
        $this->belongsTo(Classes::class, 'class_id', 'id');
    }



    public function child()
    {
        $this->belongsTo(Childs::class, 'child_id', 'id');
    }
}
