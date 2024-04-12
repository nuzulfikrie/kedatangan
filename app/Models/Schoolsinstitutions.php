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

    public function schoolsAdmin()
    {
        return $this->hasMany('schools_admin', 'school_id', 'id');
    }


    public function getAllAdmins()
    {
        return $this->schoolsAdmin;
    }

    public function getAllRecords()
    {
        return Schoolsinstitutions::all();
    }

    public function getRecordById(int $id)
    {
        return Schoolsinstitutions::find($id);
    }


    public function createRecords(array $dataFromRequest)
    {
        //1 save school first
        $school = new Schoolsinstitutions();
        $school->name = $dataFromRequest['name'];
        $school->address = $dataFromRequest['address'];
        $school->phone_number = $dataFromRequest['phone_number'];
        $school->school_email = $dataFromRequest['school_email'];
        $school->school_website = $dataFromRequest['school_website'];


        $school->saveOrFail();

        //create school admin id
        $schoolAdmin = new Schoolsadmin();
        $schoolAdmin->school_id = $school->id;
        $schoolAdmin->user_id = $dataFromRequest['school_admin_id'];
        $schoolAdmin->saveOrFail();

        return $school;
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
