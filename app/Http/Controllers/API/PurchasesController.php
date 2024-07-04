<?php

namespace App\Http\Controllers\API;

use App\Stock;
use App\Purchase;
use App\Purchase_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PurchasesController extends Controller
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
        $this->authorize('isPurchase');
        $this->authorize('Read');
        if ($search = \Request::get('search')) {
            return DB::table('purchases')
                ->join('suppliers','purchases.supplier_id','suppliers.id')
                ->leftjoin('users','purchases.user_id','users.id')
                ->select([
                    'purchases.*',
                    'suppliers.name as supplier',
                    'users.name as user'
                ])
                ->whereNull('purchases.deleted_at')
                ->where(function($query) use ($search){
                    $query->where('purchases.BillNo','LIKE',"$search%")
                            ->orWhere('suppliers.name','LIKE',"$search%");
                })
                ->latest()->paginate(20);
        }else
        {
            return DB::table('purchases')
                ->join('suppliers','purchases.supplier_id','suppliers.id')
                ->leftjoin('users','purchases.user_id','users.id')
                ->select([
                    'purchases.*',
                    'suppliers.name as supplier',
                    'users.name as user'
                ])
                ->whereNull('purchases.deleted_at')
                ->orderBy('id','DESC')
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
        $this->authorize('isPurchase');
        $this->authorize('Create');
        $this->validate($request,[
            'BillNo'=>'required',
            'total'=>'required',
            'paid'=>'required',
            'date'=>'required',
            'supplier'=>'required',
            'date'=>'required'
        ]);

         for($i=0;$i<count($request->medicineId);$i++){
            if(!$request->medicineId[$i] ){
                
                return response()->json(['error'=>'Please Select the medicines']);
               
            }
            else if(!$request->Batch_No[$i]){

                return response()->json(['error'=>'Please Enter Batch No ']);
               
            }
            else if(!$request->Expiry[$i]){
              
                return response()->json(['error'=>'Please Enter Expiry Date ']);
                   
             
            }
            else if(!$request->Quantity[$i]){
               
                return response()->json(['error'=>'Please Enter Quantity ']);
                   
               
            }
            else if(!$request->Rate[$i]){
               
                return response()->json(['error'=>'Please Enter Rate ']);
                
            }
            else if(!$request->Mrp[$i]){
                
                return response()->json(['error'=>'Please Enter Mrp ']);
                  
            }
            else if(!$request->Amount[$i]){
              
                return response()->json(['error'=>'Please Enter Amount ']);
                 
            }
        }


        if($request->image)
        {
           $name = time().'.'. explode('/',explode(':',substr($request->image,0,strpos($request->image, ';')))[1])[1];
           \Image::make($request->image)->save(public_path('images/purchases/').$name);
           $request->merge(['image'=>$name]);
        }
        $user = auth('api')->user();
        $approve = 0;
        $codeArray=Purchase::select('BillNo')->orderBy('id','desc')->limit(1)->get();

        $code_value='';
         //To check wether there is any location created or not 
         if(count($codeArray)<=0){
             $code_value="P01";
         }else{
             $code= $codeArray[0]->BillNo;
             $last_digit=substr($code,2,strlen($code)); 
             $code_value= 'P0'.(string)($last_digit+1);
         }


        $request->merge(['BillNo'=>$code_value]);
       $Purchases = Purchase::create([
           'BillNo'=>$request->BillNo,
           'supplier_id' => $request->supplier,
           'supplier_ref' => $request->supplierRef,
           'date' => $request->date,
           'description' => $request->description,
           'image' => $request->image,
           'user_id' => $user->id,
           'total' => $request->total,
           'remind' => $request->remind,
           'discount' => $request->discount,
           'paid' => $request->paid,
           'approve' => $approve
       ]);

       for($i=0;$i<count($request->medicineId);$i++){
            Purchase_detail::create(['purchase_id'=>$Purchases->id,'medicine_id'=>$request->medicineId[$i],'batch_no'=>$request->Batch_No[$i],'expiry_date'=>$request->Expiry[$i],'quantity'=>$request->Quantity[$i],'free_quantity'=>$request->Free_Quantity[$i],'rate'=>$request->Rate[$i],'MRP'=>$request->Mrp[$i],'amount'=>$request->Amount[$i],'approve'=>$approve]);
       }

        return response()->json(['success'=>'Data add Successfully','id'=>$Purchases->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('isPurchase');
        $purchase = DB::table('purchases')
        ->join('suppliers','purchases.supplier_id','suppliers.id')
        ->select([
            'purchases.*',
            'suppliers.name as supplier',
        ])
        ->where('purchases.id',$id)
        ->first();

        $purchase_detail = DB::table('purchase_details')
        ->join('medicines','purchase_details.medicine_id','medicines.id')
        ->leftjoin('groups','medicines.group_id','groups.id')
        ->select([
            'purchase_details.*',
            'medicines.name as medicines',
            'medicines.barcode as barcode',
            'groups.name as group'
        ])
        ->where('purchase_details.purchase_id',$id)
        ->get();

        return response()->json(['purchase'=>$purchase,'purchase_detail'=>$purchase_detail]);
        
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
        $this->authorize('isPurchase');
        $this->authorize('Update');
        $this->validate($request,[
            'BillNo'=>'required',
            'total'=>'required',
            'paid'=>'required',
            'date'=>'required',
            'supplier'=>'required'
        ]);

        for($i=0;$i<count($request->medicineId);$i++){
            if(!$request->medicineId[$i] ){
                
                return response()->json(['error'=>'Please Select the medicines']);
               
            }
            else if(!$request->Batch_No[$i]){

                return response()->json(['error'=>'Please Enter Batch No ']);
               
            }
            else if(!$request->Expiry[$i]){
              
                return response()->json(['error'=>'Please Enter Expiry Date ']);
                   
             
            }
            else if(!$request->Quantity[$i]){
               
                return response()->json(['error'=>'Please Enter Quantity ']);
                   
               
            }
            else if(!$request->Rate[$i]){
               
                return response()->json(['error'=>'Please Enter Rate ']);
                
            }
            else if(!$request->Mrp[$i]){
                
                return response()->json(['error'=>'Please Enter Mrp ']);
                  
            }
            else if(!$request->Amount[$i]){
              
                return response()->json(['error'=>'Please Enter Amount ']);
                 
            }
        }

        $purchase = Purchase::findOrFail($id);

        $currentPhoto = $purchase->image;
        if($request->image != $currentPhoto)
        {
            $name = time().'.'. explode('/',explode(':',substr($request->image,0,strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('images/purchases/').$name);

            $request->merge(['image'=>$name]);
            $userPhoto = $currentPhoto;
            if(file_exists($userPhoto))
            {
                @unlink($userPhoto);
            }
        }

        $user = auth('api')->user();
        $approve = 0;
        $purchase->BillNo=$request->BillNo;
        $purchase->supplier_id = $request->supplier;
        $purchase->supplier_ref = $request->supplierRef;
        $purchase->date = $request->date;
        $purchase->description = $request->description;
        $purchase->image = $request->image;
        $purchase->user_id = $user->id;
        $purchase->total = $request->total;
        $purchase->remind = $request->remind;
        $purchase->discount = $request->discount;
        $purchase->paid = $request->paid;
        $purchase->approve = $approve;
        $purchase->save();

        DB::table('purchase_details')->where('purchase_id',$id)->delete();

        for($i=0;$i<count($request->medicineId);$i++){
            Purchase_detail::create(['purchase_id'=>$purchase->id,'medicine_id'=>$request->medicineId[$i],'batch_no'=>$request->Batch_No[$i],'expiry_date'=>$request->Expiry[$i],'quantity'=>$request->Quantity[$i],'free_quantity'=>$request->Free_Quantity[$i],'rate'=>$request->Rate[$i],'MRP'=>$request->Mrp[$i],'amount'=>$request->Amount[$i],'approve'=>$approve]);
       }

        return response()->json(['success'=>'Data Update Successfully','id'=>$purchase->id]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isPurchase');
        $this->authorize('Delete');
        $Medicine = Purchase::findOrFail($id)->delete();
        Purchase_detail::where('purchase_id',$id)->delete();
        return 'success';
    }


    public function billNo()
    {
        $this->authorize('isPurchase');
        $codeArray=Purchase::select('BillNo')->orderBy('id','desc')->limit(1)->get();

        $code_value='';
         //To check wether there is any location created or not 
         if(count($codeArray)<=0){
             $code_value="P01";
         }else{
             $code= $codeArray[0]->BillNo;
             $last_digit=substr($code,2,strlen($code)); 
             $code_value= 'P0'.(string)($last_digit+1);
         }

         return $code_value;
    }

    public function edit($id)
    {
        $this->authorize('isPurchase');
        $this->authorize('Update');
        $purchase = DB::table('purchases')
        ->join('suppliers','purchases.supplier_id','suppliers.id')
        ->select([
            'purchases.*',
            'suppliers.name as supplier',
        ])
        ->where('purchases.id',$id)
        ->first();

        $purchase_detail = DB::table('purchase_details')
        ->join('medicines','purchase_details.medicine_id','medicines.id')
        ->select([
            'purchase_details.*',
            'medicines.name as medicines',
            'medicines.barcode as barcode'
        ])
        ->where('purchase_details.purchase_id',$id)
        ->get();

        return response()->json(['purchase'=>$purchase,'purchase_detail'=>$purchase_detail]);
    }

    public function approved($state,$id)
    {
        $this->authorize('isPurchase');
        $approved = Purchase::findOrFail($id);
        if($state==1)
        {
            $approved->approve = 1;
            DB::table('purchase_details')->where('purchase_id',$id)->update(['approve'=>1]);
            $approved->save();

            $data = DB::table('purchase_details')->where('purchase_id',$id)->get();

            foreach($data as $d)
            {
                Stock::create([
                    'purchase_detail_id' => $d->id,
                    'medicine_id'=> $d->medicine_id,
                    'batch_no'=>$d->batch_no,
                    'expiry_date'=>$d->expiry_date,
                    'quantity'=>$d->quantity+$d->free_quantity,
                    'MRP'=>$d->MRP,
                    'notify'=>0

                ]);
            }

        }else
        {
            $approved->approve = 2;

            $approved->save();
            DB::table('purchase_details')->where('purchase_id',$id)->update(['approve'=>2]);
        }

        return $approved->approve;
    }


}
