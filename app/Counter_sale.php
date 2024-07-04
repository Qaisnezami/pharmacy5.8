<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Counter_sale extends Model
{
    use SoftDeletes;
    protected $fillable = ['BillNo','doctor_id','patient_name','date','description','image','user_id','total','remaind','discount','paid'];
    protected $dates = ['deleted_at'];
}
