<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapeh extends Model
{
    use HasFactory;
    protected $table = 'mapeh';
    protected $fillable = [
        'mapeh_id',
        'grade_status_id',
        'first',
        'second',
        'third',
        'fourth',
        'average',
    ];
}
