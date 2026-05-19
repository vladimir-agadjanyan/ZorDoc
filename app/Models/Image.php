<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'user_id',
        'original_name',
        'path',
        'size',
        'hash',
        'mime_type',
    ];
}
