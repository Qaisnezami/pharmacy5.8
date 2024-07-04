<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;
    protected $fillable = ['name','code','email','user_id','phone','company_id','address','contract'];
    protected $dates = ['deleted_at'];
}
