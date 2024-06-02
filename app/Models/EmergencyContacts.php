<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyContacts extends Model
{
    use HasFactory;

    protected $table = 'emergency_contacts';
    protected $primaryKey = 'id';

    protected $attributes = [];

    protected $fillable = [
        'child_id',
        'parent_id',
        'name',
        'phone_number',
        'relationship',
        'picture_path',
        'address',
        'created_at',
        'updated_at'
    ];



    public function childs()
    {
        return $this->belongsTo(Childs::class, 'child_id', 'id');
    }

    public function parents()
    {
        return $this->belongsTo(Parents::class, 'parent_id', 'id');
    }

    public static function createRecord(array $data)
    {

        return self::create($data);
    }

    public static function updateRecord(int $id, array $data)
    {

        $record = self::find($id);
        return self::update($data);
    }

    public static function deleteRecord(int $id)
    {
        $record = self::find($id);
        return $record->delete();
    }

    //utility function
    public static function wipe()
    {
        self::truncate();
        return true;
    }
}
