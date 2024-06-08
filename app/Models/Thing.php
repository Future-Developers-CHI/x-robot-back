<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thing extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'robot_id',
        'image_path',
        'gps'
    ];
}
