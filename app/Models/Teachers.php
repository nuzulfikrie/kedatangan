<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Teachers extends Model
{
    use HasFactory;

    protected $table = 'teachers';
    protected $primaryKey = 'id';

    public function schools()
    {
        return $this->belongsTo(Schoolsinstitutions::class, 'school_id', 'id');
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function teacherClass()
    {
        //pivot teacher class
        $this->hasMany(PivotClassTeacher::class, 'teacher_id', 'id');
    }

    public static function createTeacher(array $data)
    {
        try {
            DB::beginTransaction();

            $dataTeacher = [
                'teacher_name' => $data['name'],
                'teacher_specialization' => $data['specialization'],
                'user_id' => $data['user_id'],
                'school_id' => $data['school_id'],
                'picture_path' => $data['picture_path'],
            ];

            $teacher = self::create($dataTeacher);
            if (!$teacher) {
                throw new Exception(__('Failed create record'));
            }

            $dataPivot = [
                'teacher_id' => $teacher->id,
                'class_id' => $data['class_id'],
            ];

            $pct = PivotClassTeacher::createRecord($dataPivot);

            if (!$pct) {
                throw new Exception(__('Failed create record'));
            }

            DB::commit();

            return [
                'message' => 'Success Create Teacher',
                'teacher' => $teacher,
                'code' => 200,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Fail create teacher , exception in ' . $e->getFile() . ' at ' . $e->getLine() . ' message ' . $e->getMessage());

            return [
                'message' => 'Failed create teacher ' . $e->getMessage(),
                'code' => 500,
                'teacher' => null,
            ];
        }
    }

    public static function updateTeacher(int $teacherId, array $data)
    {
        try {
            $teacher = self::findOrFail($teacherId);
            $teacher =  $teacher::update($data);

            if ($teacher) {
                return [
                    'message' => 'Success update teacher',
                    'teacher' => $teacher,
                    'code' =>  200,
                ];
            }
        } catch (Exception $e) {
            //throw $th;

            Log::error(
                'Failed update teacher  in ' . $e->getFile() . ' At' . $e->getLine() . ' due to ' . $e->getMessage()
            );

            return [
                'message' => 'Failed update teacher',
                'code' => 500,
                'teacher' => null,
            ];
        }
    }

    public static function deleteTeacher(int $teacherId)
    {
        try {
            DB::beginTransaction();

            $pct = PivotClassTeacher::where('teacher_id', $teacherId)->get();
            foreach ($pct as $ct) {
                $ct->delete();
            }

            $teacher = self::findOrFail($teacherId);

            $teacher->delete();
            DB::commit();
        } catch (Exception $e) {
            //throw $th;

            DB::rollBack();

            return [
                'message' => 'Failed update teacher',
                'code' => 500,
                'teacher' => null,
            ];
        }
    }
}
