<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    use SoftDeletes;
    protected $fillable = ['order_id','medicine_id','quantity','approve'];
    protected $dates = ['deleted_at'];
}
