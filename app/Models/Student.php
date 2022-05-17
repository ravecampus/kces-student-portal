<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $fillable = [
        'lrn',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'sex',
        'nationality',
        'religion',
        'civil_status',
        'address',
        'birthdate',
        'birthplace',
        'age',
    ];

    public function account(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
