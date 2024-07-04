<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['purchase_detail_id','medicine_id','batch_no','expiry_date','quantity','free_quantity','MRP','notify'];
}
