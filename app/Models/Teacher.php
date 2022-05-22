<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = "teachers";
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'sufix',
        'sufix',
        'id_number',
        'sex',
        'birthdate',
        'birthplace',
        'user_id',
        'section_id',
        'level_of',
    ];

    public function account(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function advise(){
        return $this->hasMany(Advisory::class, 'teacher_id', 'id');
    }
    // public function user(){
    //     return $this->morphOne(Image::class, 'userable');
    // }
}
