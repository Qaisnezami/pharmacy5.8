<?php

namespace App\Http\Controllers\API;

use App\Group;
use App\Doctor;
use App\Medicine;
use App\Manufacturer;
use App\Classification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MedicinesController extends Controller
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
        $this->authorize('isMedicines');
        $this->authorize('Read');
        if ($search = \Request::get('search')) {
            $data = Medicine::where(function($query) use ($search){
                $query->where('medicines.barcode','LIKE',"$search%")
                        ->orWhere('medicines.name','LIKE',"$search%");
            })
            ->leftjoin('classifications','medicines.classification_id','classifications.id')
                ->leftjoin('manufacturers','medicines.manufacturer_id','manufacturers.id')
                ->leftjoin('doctors','medicines.doctor_id','doctors.id')
                ->leftjoin('groups','medicines.group_id','groups.id')
                ->leftjoin('users','medicines.user_id','users.id')
                ->select([
                    'medicines.*',
                    'medicines.barcode as code',
                    'users.name as username',
                    'classifications.id as classification',
                    'classifications.name as classificationName',
                    'manufacturers.id as manufacturer',
                    'manufacturers.name as manufacturerName',
                    'doctors.id as doctor',
                    'doctors.name as doctorName',
                    'groups.id as group',
                    'groups.name as groupName',
                ])
            ->whereNull('medicines.deleted_at')
            ->paginate(20);
        }else{
            $data = DB::table('medicines')
                ->leftjoin('classifications','medicines.classification_id','classifications.id')
                ->leftjoin('manufacturers','medicines.manufacturer_id','manufacturers.id')
                ->leftjoin('doctors','medicines.doctor_id','doctors.id')
                ->leftjoin('groups','medicines.group_id','groups.id')
                ->leftjoin('users','medicines.user_id','users.id')
                ->select([
                    'medicines.*',
                    'medicines.barcode as code',
                    'users.name as username',
                    'classifications.id as classification',
                    'classifications.name as classificationName',
                    'manufacturers.id as manufacturer',
                    'manufacturers.name as manufacturerName',
                    'doctors.id as doctor',
                    'doctors.name as doctorName',
                    'groups.id as group',
                    'groups.name as groupName',
                ])
                ->orderBy('id','DESC')
                ->whereNull('medicines.deleted_at')
                ->latest()->paginate(20);
            $data->no = 1;
        }
        

        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->authorize('isMedicines');
        $this->authorize('Create');
        $this->validate($request,[
            'barcode'=>'required|unique:medicines',
            'name'=>'required',
        ]);

         $name = null;
         if($request->image)
         {
            $name = time().'.'. explode('/',explode(':',substr($request->image,0,strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('images/medicines/').$name);
            $request->merge(['image'=>$name]);
         }
         if($request->isactive) $active = 1;
         else $active = 0;
         
         $user = auth('api')->user();
         if($request->approve)
         {
            $approve = 1;
         }else
         {
            $approve = 0;
         }
         
         
         return Medicine::create([
            'barcode' => $request->barcode,
            'name' => $request->name,
            'group_id'=>$request->group,
            'classification_id'=>$request->classification,
            'manufacturer_id'=>$request->manufacturer,
            'doctor_id'=>$request->doctor,
            'description'=>$request->description,
            'user_id'=>$user->id,
            'approve'=>$approve,
            'isactive'=>$active,
            'image'=> "/images/medicines/".$request->image
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
        $med  =  \Request::get('medicineView');
        $medicines = Medicine::leftjoin('groups','medicines.group_id','groups.id')
        ->select([
            'medicines.*',
            'groups.name as groups'
        ])
        ->where(function($query) use ($med){
            if(!$med)
            {
                $query->where('isactive','!=',0)
                ->where('approve','!=',0)
                ->where('approve','!=',2);
            } 
        })
        
        ->where('medicines.name','LIKE',"$id%")->take(20)->get();
        return $medicines;
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
        $this->authorize('isMedicines');
        $this->authorize('Update');
        $medicines = Medicine::findOrFail($id);
        $this->validate($request,[
            'barcode'=>'required|unique:medicines,barcode,'.$medicines->id,
            'name'=>'required',
        ]);
        $currentPhoto = $medicines->image;
        if($request->image != $currentPhoto)
        {
            $name = time().'.'. explode('/',explode(':',substr($request->image,0,strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('images/medicines/').$name);

            $request->merge(['image'=>'images/medicines/'.$name]);
            $userPhoto = $currentPhoto;
            if(file_exists($userPhoto))
            {
                @unlink($userPhoto);
            }
        }
        $user = auth('api')->user()->id;

        if($medicines->approve == 2  )
        {
            $approve = 2;
        }
        if($request->approve)
        {
           $approve = 1;
        }else
        {
           $approve = 0;
        }
        $medicines->barcode = $request->barcode;
        $medicines->name = $request->name;
        $medicines->group_id= $request->group;
        $medicines->classification_id= $request->classification;
        $medicines->manufacturer_id= $request->manufacturer;
        $medicines->doctor_id=$request->doctor;
        $medicines->description=$request->description;
        $medicines->user_id=$user;
        $medicines->approve=$approve;
        $medicines->image=$request->image;
        $medicines->save();
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
        $this->authorize('isMedicines');
        $this->authorize('Delete');
        $Medicine = Medicine::findOrFail($id)->delete();
        return 'success';
    }






    public function classification()
    {
        $this->authorize('isMedicines');
        return response()->json(['data'=>Classification::whereNull('deleted_at')->select(['name','id as code'])->get()]); 
    }

    public function manufacturer()
    {
        $this->authorize('isMedicines');
        return response()->json(['data'=>Manufacturer::whereNull('deleted_at')->select(['name','id as code'])->get()]); 
    }

    public function group()
    {
        $this->authorize('isMedicines');
        return response()->json(['data'=>Group::whereNull('deleted_at')->select(['name','id as code'])->get()]); 
    }

    public function doctor()
    {
        $this->authorize('isMedicines');
        return response()->json(['data'=>Doctor::whereNull('deleted_at')->select(['name','id as code'])->get()]); 
    }




    public function activation($id)
    {
        $this->authorize('Activation');
        $activation = Medicine::findOrFail($id);

        if($activation->isactive == 0)
        {
            $activation->isactive = 1;
            $activation->save();
        }else
        {
            $activation->isactive = 0;
            $activation->save();
        }
        return $activation->isactive;
    }



    public function approved($state,$id)
    {
        $this->authorize('Approval');
        $approved = Medicine::findOrFail($id);
        if($state==1)
        {
            $approved->approve = 1;
            $approved->save();
        }else
        {
            $approved->approve = 2;
            $approved->save();
        }

        return $approved->approve;
    }



    public function barcode(Request $request)
    {   
       
        $this->authorize('isBarcode');
        if(!$request->all())
        {

            $data = Medicine::whereNull('deleted_at')->select(['barcode','name'])->get();
            return response()->json($data);
        }else
        {
            $data = Medicine::whereNull('deleted_at')->whereIn('id',$request->all())->select(['barcode','name'])->get();
            return response()->json($data);
        }
    }

    public function SearchBarcode()
    {
        $this->authorize('isBarcode');
        return response()->json(['data'=>Medicine::whereNull('deleted_at')->select(['name','id'])->get()]); 
    }

    public function Findbarcode($id)
    {   
        return response()->json(Medicine::leftjoin('groups','medicines.group_id','groups.id')->where('barcode',$id)->select(['medicines.name','medicines.id','groups.name as group'])->first()); 
    }

    

}
