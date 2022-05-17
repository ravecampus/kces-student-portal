<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeStatus extends Model
{
    use HasFactory;

    protected $table = 'grade_status';
    protected $fillable = ['teacher_advisory_id', 'status'];

    public function grade(){
        return $this->hasMany(Grade::class, 'grade_status_id', 'id');
    }
}
