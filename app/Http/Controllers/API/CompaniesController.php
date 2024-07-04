<?php

namespace App\Http\Controllers\API;

use Auth;
use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CompaniesController extends Controller
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
        $this->authorize('isCompany');
        $this->authorize('Read');
        return DB::table('companies')
                ->join('users','companies.user_id','users.id')
                ->select([
                    'companies.*',
                    'users.name as username'
                ])
                ->orderBy('id','DESC')
                ->whereNull('companies.deleted_at')
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
        $this->authorize('isCompany');
        $this->authorize('Create');
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|email',
            'phone'=>'required',
            'address'=>'required',
        ]);

        $codeArray=Company::select('code')->orderBy('id','desc')->limit(1)->get();
        $code_value='';
         //To check wether there is any location created or not 
         if(count($codeArray)<=0){
             $code_value="C01";
         }else{
             $code= $codeArray[0]->code;
             $last_digit=substr($code,2,strlen($code)); 
             $code_value= 'C0'.(string)($last_digit+1);
         }
         $name = null;
         if($request->contract)
         {
            $name = time().'.'. explode('/',explode(':',substr($request->contract,0,strpos($request->contract, ';')))[1])[1];
            \Image::make($request->contract)->save(public_path('images/company/').$name);
            $request->merge(['contract'=>$name]);
         }
         $user = auth('api')->user()->id;
        return Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
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
        //
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
        $this->authorize('isCompany');
        $this->authorize('Update');
        $company = Company::findOrFail($id);
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|email',
            'phone'=>'required',
            'address'=>'required',
        ]);
        $currentPhoto = $company->contract;
        if($request->contract != $currentPhoto)
        {
            $name = time().'.'. explode('/',explode(':',substr($request->contract,0,strpos($request->contract, ';')))[1])[1];
            \Image::make($request->contract)->save(public_path('images/company/').$name);

            $request->merge(['contract'=>$name]);
            $userPhoto = public_path('images/company/').$currentPhoto;
            if(file_exists($userPhoto))
            {
                @unlink($userPhoto);
            }
        }
        $user = auth('api')->user()->id;
        $company->name = $request->name;
        $company->email = $request->email;
        $company->phone = $request->phone;
        $company->address = $request->address;
        $company->contract=$request->contract;
        $company->user_id = $user;
        $company->save();

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
        $this->authorize('isCompany');
        $this->authorize('Delete');
        $company = Company::findOrFail($id)->delete();
        return 'success';
    }
}
