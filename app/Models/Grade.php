<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $table = 'grade';
    protected $fillable =[
        'subject_id',
        'first',
        'second',
        'third',
        'fourth',
        'average',
        'remarks',
        'grade_status_id'
    ];
    public function setSubjectAttribute()
    {
        return Subject::where('subject_id', $this->subject_id)->first();
    }

}
