<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\FileHandlerTrait;

class File extends Model
{
    use HasFactory, FileHandlerTrait;

    protected $fillable = [
        'storage_type',
        'file_name',
        'file_extension',
        'file_path',
        'uploader_id',
        'remark',
    ];

    /**
     * Relationship with the User model (uploader).
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }
}
