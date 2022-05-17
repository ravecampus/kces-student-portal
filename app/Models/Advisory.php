<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advisory extends Model
{
    use HasFactory;

    protected $table = 'advisory';
    protected $fillable = [
        'section_id', 
        'school_year_id', 
        'teacher_id'
    ];


    public function teacher()
    {
        return $this->belongsTo('App\Teacher', 'id', 'teacher_id');
    }
}
