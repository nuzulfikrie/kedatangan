<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminders extends Model
{
    use HasFactory;

    public function childs()
    {
        return $this->belongsTo(Childs::class, 'child_id', 'id');
    }

    public static function createRecord(array $data)
    {
        return self::create($data);
    }

    public static function updateRecord(int $id, array $data)
    {
        $record = self::find($id);
        $record->update($data);
        return $record;
    }

    public static function deleteRecord(int $id)
    {
        $record = self::find($id);
        $record->delete();
    }
}
