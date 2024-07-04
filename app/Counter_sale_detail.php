<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Counter_sale_detail extends Model
{

    protected $fillable = ['counter_sale_id','medicine_id','stock_id','expiry_date','quantity','MRP','amount','user_id','status'];

}
