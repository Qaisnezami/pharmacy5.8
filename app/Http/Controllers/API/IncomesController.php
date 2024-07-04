<?php

namespace App\Http\Controllers\API;

use App\Income;
use App\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class IncomesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isExpense');
        $this->authorize('Read');
        if ($search = \Request::get('search')) {
            return DB::table('incomes')
                ->join('users','incomes.user_id','users.id')
                ->select([
                    'incomes.*',
                    'users.name as username'
                ])
                ->whereNull('incomes.deleted_at')
                ->where(function($query) use ($search){
                    $query->where('incomes.cash','LIKE',"%$search%");
                    $query->orWhere('incomes.description','LIKE',"%$search%");
                })
                ->paginate(20);
        }else
        {
            return DB::table('incomes')
                ->join('users','incomes.user_id','users.id')
                ->select([
                    'incomes.*',
                    'users.name as username'
                ])
                ->whereNull('incomes.deleted_at')
                ->latest()->paginate(20);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('isExpense');
        $this->authorize('Create');
        $this->validate($request,[
            'cash'=>'required|numeric'
        ]);
        $user = auth('api')->user()->id;
        return Income::create([
            'cash'=>$request->cash,
            'description'=>$request->description,
            'user_id'=>$user
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('isExpense');
        $this->authorize('Read');
        if ($search = \Request::get('search')) {
            return DB::table('expenses')
                ->join('users','expenses.user_id','users.id')
                ->join('expense_categories','expenses.expense_category_id','expense_categories.id')
                ->select([
                    'expenses.*',
                    'users.name as username',
                    'expense_categories.name as category_name',
                    'expense_categories.id as category',
                ])
                ->where(function($query) use ($search){
                    $query->where('expenses.cash','LIKE',"%$search%")
                            ->orWhere('expenses.description','LIKE',"%$search%")
                            ->orWhere('expense_categories.name','LIKE',"%$search%");
                })
                ->whereNull('expenses.deleted_at')
                ->where('expenses.income_id',$id)
                ->latest()->paginate(20);
        }else
        {
            return DB::table('expenses')
                ->join('users','expenses.user_id','users.id')
                ->join('expense_categories','expenses.expense_category_id','expense_categories.id')
                ->select([
                    'expenses.*',
                    'users.name as username',
                    'expense_categories.name as category_name',
                    'expense_categories.id as category',
                ])
                ->whereNull('expenses.deleted_at')
                ->where('expenses.income_id',$id)
                ->latest()->paginate(20);
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('isExpense');
        $this->authorize('Update');
        $this->authorize('isIncome');
        $income = Income::findOrFail($id);
        $this->validate($request,[
            'cash'=>'required',
        ]);
        $user = auth('api')->user()->id;
        $income->cash = $request->cash;
        $income->description = $request->description;
        $income->user_id = $user;
        $income->save();

        return 'success';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isExpense');
        $this->authorize('Delete');
        Income::findOrFail($id)->delete();
        Expense::where('income_id',$id)->delete();
        return 'success';
    }
}
