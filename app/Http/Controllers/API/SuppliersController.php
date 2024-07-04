<?php

namespace App\Http\Controllers\API;

use App\Vendor;
use App\Company;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SuppliersController extends Controller
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
        $this->authorize('isSupplier');
        $this->authorize('Read');
        if ($search = \Request::get('search')) {
            return DB::table('suppliers')
                ->join('users','suppliers.user_id','users.id')
                ->leftjoin('companies','suppliers.company_id','companies.id')
                ->select([
                    'suppliers.*',
                    'users.name as username',
                    'companies.id as company',
                    'companies.name as company_name'
                ])
                ->where(function($query) use ($search){
                    $query->where('suppliers.code','LIKE',"$search%")
                            ->orWhere('suppliers.name','LIKE',"$search%");
                })
                ->whereNull('suppliers.deleted_at')
                ->latest()->paginate(20);
        }else{
            return DB::table('suppliers')
                ->join('users','suppliers.user_id','users.id')
                ->leftjoin('companies','suppliers.company_id','companies.id')
                ->select([
                    'suppliers.*',
                    'users.name as username',
                    'companies.id as company',
                    'companies.name as company_name'
                ])
                ->orderBy('id','DESC')
                ->whereNull('suppliers.deleted_at')
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
        $this->authorize('isSupplier');
        $this->authorize('Create');
        $this->validate($request,[
            'name'=>'required',
            'phone'=>'required',
        ]);

        $codeArray=Supplier::select('code')->orderBy('id','desc')->limit(1)->get();
        $code_value='';
         //To check wether there is any location created or not 
         if(count($codeArray)<=0){
             $code_value="V01";
         }else{
             $code= $codeArray[0]->code;
             $last_digit=substr($code,2,strlen($code)); 
             $code_value= 'V0'.(string)($last_digit+1);
         }

         $name = null;
         if($request->contract)
         {
            $name = time().'.'. explode('/',explode(':',substr($request->contract,0,strpos($request->contract, ';')))[1])[1];
            \Image::make($request->contract)->save(public_path('images/suppliers/').$name);
            $request->merge(['contract'=>$name]);
         }
         $user = auth('api')->user()->id;
        return Supplier::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company_id' => $request->company,
            'address'=>$request->address,
            'user_id' => $user,
            'code' => $code_value,
            'contract'=>$request->contract
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
        $this->authorize('isPurchase');
        return response()->json(['data'=>Supplier::whereNull('deleted_at')->select(['name','id as code'])->get()]);
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
        $suppliers = Supplier::findOrFail($id);
        $this->validate($request,[
            'name'=>'required',
            'phone'=>'required',
        ]);


        $currentPhoto = $suppliers->contract;
        if($request->contract != $currentPhoto)
        {
            $name = time().'.'. explode('/',explode(':',substr($request->contract,0,strpos($request->contract, ';')))[1])[1];
            \Image::make($request->contract)->save(public_path('images/suppliers/').$name);

            $request->merge(['contract'=>$name]);
            $userPhoto = public_path('images/suppliers/').$currentPhoto;
            if(file_exists($userPhoto))
            {
                @unlink($userPhoto);
            }
        }
        $user = auth('api')->user()->id;
        $suppliers->name = $request->name;
        $suppliers->email = $request->email;
        $suppliers->phone = $request->phone;
        $suppliers->company_id=$request->company;
        $suppliers->user_id = $user;
        $suppliers->address=$request->address;
        $suppliers->contract=$request->contract;
        $suppliers->save();

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
        $this->authorize('isSupplier');
        $this->authorize('Delete');
        $company = Supplier::findOrFail($id)->delete();
        return 'success';
    }
    public function company()
    {
        $this->authorize('isSupplier');
        return Company::whereNull('companies.deleted_at')->get();
    }
}
