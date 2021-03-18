<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentMark extends Model
{
    use HasFactory;

    protected $fillable = ['student_id','term_id','maths','science','history','total'];

    public function student(){
        return $this->belongsTo('App\Models\Student');
    }
    public function term(){
        return $this->belongsTo('App\Models\Term');
    }
}
