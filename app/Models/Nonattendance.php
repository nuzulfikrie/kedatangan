<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nonattendance extends Model
{
    use HasFactory;

    protected $table = 'nonattendance';

    protected $fillable = [
        'child_id',
        'date',
        'reason'
    ];

    protected $timestamp = true;

    public function child()
    {
        return $this->belongsTo(Childs::class, 'child_id', 'id');
    }

    public static function createRecord(array $data)
    {
        return self::createOrFail($data);
    }

    public static function updateRecord(int $id, array $data)
    {
        $record = self::findOrFail($id);
        $record->update($data);
        return $record;
    }

    public static function deleteRecord(int $id)
    {
        $record = self::findOrFail($id);
        $record->delete();

        return true;
    }

    public static function getRecord(int $id)
    {
        return self::findOrFail($id);
    }

    public static function wipe()
    {
        self::truncate();
        return true;
    }

    public static function getAllRecords()
    {
        return self::all();
    }

    public static function getAllRecordsByChild(int $child_id)
    {
        return self::where('child_id', $child_id)->get();
    }
}
