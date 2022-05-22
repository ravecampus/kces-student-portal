<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectLevel extends Model
{
    use HasFactory;

    protected $table = 'subject_per_level';
    protected $fillable = ['subject_id', 'level_of'];


}
