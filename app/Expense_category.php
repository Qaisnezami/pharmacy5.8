<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense_category extends Model
{
    use SoftDeletes;
    protected $fillable = ['name','user_id'];
    protected $dates = ['deleted_at'];
}
