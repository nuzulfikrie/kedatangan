<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Validator;

class PivotClassTeacher extends Model
{
    use HasFactory;

    protected $table = 'pivot_class_teacher';

    protected $foreignKey = 'id';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Ensure teacher_id and class_id combination is unique
            $exists = PivotClassTeacher::where('teacher_id', $model->teacher_id)
                ->where('class_id', $model->class_id)
                ->exists();
            if ($exists) {
                throw new \Exception('The combination of teacher and class must be unique.');
            }
        });
    }

    public function teacher()
    {
        $this->belongsTo(Teachers::class, 'teacher_id', 'id');
    }

    public function class()
    {
        $this->belongsTo(Classes::class, 'class_id', 'id');
    }

    public static function createRecord(array $data)
    {

        //record must exists in classes table,
        //record must exists in teachers table
        // Validate data
        $validator = \Illuminate\Support\Facades\Validator::make($data, [
            'teacher_id' => 'required|exists:teachers,id',
            'class_id' => 'required|exists:classes,id',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // Ensure teacher_id and class_id combination is unique
        $exists = self::where('teacher_id', $data['teacher_id'])
            ->where('class_id', $data['class_id'])
            ->exists();
        if ($exists) {
            throw new \Exception('The combination of teacher and class must be unique.');
        }

        return self::create($data);
    }

    public function updateRecord(int $id, array $data)
    {
        // Find the record
        $record = self::findOrFail($id);

        // Validate data
        $validator = \Illuminate\Support\Facades\Validator::make($data, [
            'teacher_id' => 'required|exists:teachers,id',
            'class_id' => 'required|exists:classes,id',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // Ensure teacher_id and class_id combination is unique
        $exists = self::where('teacher_id', $data['teacher_id'])
            ->where('class_id', $data['class_id'])
            ->where('id', '<>', $id)
            ->exists();
        if ($exists) {
            throw new \Exception('The combination of teacher and class must be unique.');
        }

        // Update the record
        $record->update($data);

        return $record;
    }

    public function deleteRecord(int $id)
    {
        // Find the record
        $record = self::findOrFail($id);

        // Delete the record
        $record->delete();

        return true;
    }
}
