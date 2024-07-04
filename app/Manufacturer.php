<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manufacturer extends Model
{
    use SoftDeletes;
    protected $fillable = ['name','code','user_id'];
    protected $dates = ['deleted_at'];
}
