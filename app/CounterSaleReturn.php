<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CounterSaleReturn extends Model
{
    protected $fillable = ['counter_sale_details_id','medicine_id','stock_id','expiry_date','quantity','MRP','amount','user_id','date'];

    public function getCreatedAtAttribute($date)
{
    return Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');
}

public function getUpdatedAtAttribute($date)
{
    return Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');
}
}
