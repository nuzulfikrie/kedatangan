<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schoolsinstitutions extends Model
{
    use HasFactory;


    protected $table = 'schools_institutions';

    protected $primaryKey = 'id';

    protected $attributes = [];
    public function createRecords(array $dataFromRequest)
    {
        $school = new Schoolsinstitutions();
        $school->name = $dataFromRequest['name'];
        $school->address = $dataFromRequest['address'];
        $school->phone_number = $dataFromRequest['phone_number'];
        $school->school_email = $dataFromRequest['school_email'];
        $school->school_website = $dataFromRequest['school_website'];
        return $school->saveOrFail();
    }

    public function updateRecords(array $dataFromRequest)
    {
        $school = Schoolsinstitutions::find($dataFromRequest['id']);
        $school->name = $dataFromRequest['name'];
        $school->address = $dataFromRequest['address'];
        $school->phone_number = $dataFromRequest['phone_number'];
        $school->school_email = $dataFromRequest['school_email'];
        $school->school_website = $dataFromRequest['school_website'];
        return $school->saveOrFail();
    }

    public function deleteRecords(int $id)
    {
        $school = Schoolsinstitutions::find($id);
        return $school->delete();
    }

    public function teachers()
    {
        return $this->hasMany(Teachers::class, 'teacher_id');
    }

    public function childs()
    {
        return $this->hasMany(Childs::class, 'child_id');
    }
}
