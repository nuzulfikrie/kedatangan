<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schoolsinstitutions extends Model
{
    use HasFactory;

    protected $table = 'schools_institutions';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'school_email',
        'school_website',
    ];

    /**
     * Get the teachers for the school.
     */
    public function teachers()
    {
        return $this->hasMany(Teachers::class, 'school_id');
    }

    /**
     * Get the children for the school.
     */
    public function childs()
    {
        return $this->hasMany(Childs::class, 'school_id');
    }

    /**
     * Get the classes for the school.
     */
    public function classes()
    {
        return $this->hasMany(Classes::class, 'school_id');
    }

    /**
     * Get the reminder templates for the school.
     */
    public function remindersTemplates()
    {
        return $this->hasMany(RemindersTemplate::class, 'school_id');
    }

    /**
     * Get the school admins for the school.
     */
    public function schoolsadmin()
    {
        return $this->hasMany(Schoolsadmin::class, 'school_id');
    }

    /**
     * Create a new school record.
     *
     * @param array $dataFromRequest
     * @return bool
     */
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

    /**
     * Update a school record.
     *
     * @param array $dataFromRequest
     * @return bool
     */
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

    /**
     * Delete a school record.
     *
     * @param int $id
     * @return bool|null
     */
    public function deleteRecords(int $id)
    {
        $school = Schoolsinstitutions::find($id);
        return $school->delete();
    }
}
