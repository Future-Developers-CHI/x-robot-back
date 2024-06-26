<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Robot extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'gps',
        'active',
        'status',
        'password'
    ];
}
