<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase_detail extends Model
{
    use SoftDeletes;
    protected $fillable = ['purchase_id','medicine_id','batch_no','expiry_date','quantity','free_quantity','rate','MRP','amount','approve'];
    protected $dates = ['deleted_at'];
}
