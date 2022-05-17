<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SYear extends Model
{
    use HasFactory;
    protected $table = 'school_years';
    protected $fillable = [
        'sy_name'
    ];
}
