<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hash',
        'storage_file_path',
        'public_file_path',
        'owner_type',
        'owner_id',
    ];
}
