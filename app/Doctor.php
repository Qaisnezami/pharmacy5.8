<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use SoftDeletes;
    protected $fillable = ['name','code','email','gender','user_id','phone','specialist'];
    protected $dates = ['deleted_at'];

}
