<?php

namespace App\Http\Controllers\API;

use App\Expense_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ExpensesCategoryController extends Controller
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
        $this->authorize('isExpenseCategory');
        $this->authorize('Read');
        if ($search = \Request::get('search')) {
            return DB::table('expense_categories')
                ->orderBy('id','DESC')
                ->join('users','expense_categories.user_id','users.id')
                ->select([
                    'expense_categories.*',
                    'users.name as username'
                ])
                ->where(function($query) use ($search){
                    $query->where('expense_categories.name','LIKE',"$search%");
                })
                ->whereNull('expense_categories.deleted_at')
                ->latest()->paginate(20);
        }else
        {
            return DB::table('expense_categories')
                ->orderBy('id','DESC')
                ->join('users','expense_categories.user_id','users.id')
                ->select([
                    'expense_categories.*',
                    'users.name as username'
                ])
                ->whereNull('expense_categories.deleted_at')
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
        $this->authorize('isExpenseCategory');
        $this->authorize('Create');
        $this->validate($request,[
            'name'=>'required'
        ]);
        $user = auth('api')->user()->id;
        return Expense_category::create([
            'name'=>$request->name,
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
        $this->authorize('isExpenseCategory');
        $this->authorize('Update');
        $category = Expense_category::findOrFail($id);
        $this->validate($request,[
            'name'=>'required',
        ]);
        $user = auth('api')->user()->id;
        $category->name = $request->name;
        $category->user_id = $user;
        $category->save();

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
        $this->authorize('isExpenseCategory');
        $this->authorize('Delete');
        Expense_category::findOrFail($id)->delete();
        return 'success';
    }
}
