<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;
    protected $fillable = ['income_id','cash','expense_category_id','description','user_id','date'];
    protected $dates = ['deleted_at'];
}
