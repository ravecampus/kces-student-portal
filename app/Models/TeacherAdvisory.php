<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherAdvisory extends Model
{
    use HasFactory;
    protected $table = 'teacher_advisory';
    protected $fillable = ['advisory_id', 'student_id'];
}
