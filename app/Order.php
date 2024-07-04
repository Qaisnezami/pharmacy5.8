<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $fillable = ['BillNo','supplier_id','date','description','user_id','is_stock','approve'];
    protected $dates = ['deleted_at'];
}
