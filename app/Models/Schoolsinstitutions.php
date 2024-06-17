<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Schoolsinstitutions extends Model
{
    use HasFactory;

    use SoftDeletes;

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
        try {
            //1 save school first
            DB::beginTransaction();
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
            $schoolAdmin->school_admin_id = $dataFromRequest['school_admin_id'];
            $schoolAdmin->saveOrFail();
            DB::commit();
            return $school;
        } catch (Exception $e) {
            //throw $th;
            DB::rollBack();

            Log::error('Error ' . $e->getMessage() . ' in ' . $e->getFile()  . ' at line ' . $e->getLine());
        }
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
        try {
            //code...
            DB::beginTransaction();

            //delete childs
            $this->childs()->delete();

            $school = Schoolsinstitutions::find($id);
            $school->delete();

            DB::commit();
        } catch (Exception $e) {
            //throw $th;
        }
    }

    public function teachers()
    {
        return $this->hasMany(Teachers::class, 'teacher_id');
    }

    public function childs()
    {
        return $this->hasMany(PivotClassChild::class, 'child_id');
    }
}
