<?php

namespace App\Http\Controllers\API;

use App\Payment;
use App\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
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
    public function index(Request $request)
    {
        $this->authorize('isSupplier');
        $this->authorize('Read');
        return DB::table('payments')
                ->orderBy('id','DESC')
                ->join('users','payments.user_id','users.id')
                ->join('suppliers','payments.supplier_id','suppliers.id')
                ->select([
                    'payments.*',
                    'users.name as username',
                    'suppliers.name as supplier',
                ])
                ->where('payments.supplier_id',$request->id)
                ->latest()->paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('isSupplier');
        $this->authorize('Create');
        $this->validate($request,[
            'cash'=>'required|numeric',
        ]);
        $user = auth('api')->user()->id;
        $purchase = Purchase::where('supplier_id',$request->supplier_id)->sum('remind');
        $payment = Payment::where('supplier_id',$request->supplier_id)->sum('cash');
        $total = ($purchase - $payment) - $request->cash;
        if($total < 0)
        {
            return response()->json(['error'=>'You Enter Cash More Then Remaind Money']);
        }
        Payment::create([
            'cash'=>$request->cash,
            'description'=>$request->description,
            'user_id'=>$user,
            'supplier_id'=>$request->supplier_id,
        ]);

        return response()->json(['success'=>'Payment add Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('isSupplier');
        $purchase = Purchase::where('supplier_id',$id)->sum('remind');
        $payment = Payment::where('supplier_id',$id)->sum('cash');
        return ($purchase)-($payment);
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
        $this->authorize('isSupplier');
        $this->authorize('Update');
        $this->validate($request,[
            'cash'=>'required',
        ]);
        $Payment = Payment::findOrFail($id);

        $purchase = Purchase::where('supplier_id',$request->supplier_id)->sum('remind');
        $payment2 = Payment::where('supplier_id',$request->supplier_id)->sum('cash');
        $total = ($purchase - $payment2 - $request->cash) + $Payment->cash;
        if($total < 0)
        {
            return response()->json(['error'=>'You Enter Cash More Then Remaind Money']);
        }
    
        $user = auth('api')->user()->id;
        $Payment->cash = $request->cash;
        $Payment->description = $request->description;
        $Payment->supplier_id = $request->supplier_id;
        $Payment->user_id = $user;
        $Payment->save();

        return response()->json(['success'=>'Payment Update Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isSupplier');
        $this->authorize('Delete');
        Payment::findOrFail($id)->delete();
        return 'success';
    }
}
