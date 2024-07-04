<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;
    protected $fillable = ['BillNo','supplier_id','supplier_ref','date','description','image','user_id','total','remind','discount','paid','approve'];
    protected $dates = ['deleted_at'];
}
