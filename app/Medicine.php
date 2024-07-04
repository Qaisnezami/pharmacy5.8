<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine extends Model
{
    use SoftDeletes;
    protected $fillable = ['name','barcode','user_id','group_id','classification_id','image','manufacturer_id','doctor_id','description','isactive','approve'];
    protected $dates = ['deleted_at'];
}
