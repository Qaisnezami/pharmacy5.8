<?php

namespace App\Http\Controllers\API;

use App\Income;
use App\Expense;
use App\Expense_category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExpensesController extends Controller
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
        //
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
            'cash'=>'required|numeric',
            'category'=>'required|numeric'
        ]);
        $user = auth('api')->user()->id;
        return Expense::create([
            'cash'=>$request->cash,
            'description'=>$request->description,
            'user_id'=>$user,
            'expense_category_id'=>$request->category,
            'income_id'=>$request->income_id,
            'date'=>now()
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
        $income = Income::find($id);
        $Expense = Expense::where('income_id',$id)->sum('cash');
        return ($income->cash)-($Expense);
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
        $income = Expense::findOrFail($id);
        $this->validate($request,[
            'cash'=>'required',
            'category'=>'required|numeric'
        ]);
        $user = auth('api')->user()->id;
        $income->cash = $request->cash;
        $income->description = $request->description;
        $income->expense_category_id = $request->category;
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
        Expense::findOrFail($id)->delete();
        return 'success';
    }

    public function category()
    {
        $this->authorize('isExpense');
        return response()->json(['data'=>Expense_category::whereNull('deleted_at')->select(['name','id as code'])->get()]);
    }
}
