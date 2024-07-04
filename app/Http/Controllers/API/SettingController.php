<?php

namespace App\Http\Controllers\API;

use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
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
        $this->authorize('isSetting');
        $this->authorize('Read');
        return Setting::first();
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->authorize('isSetting');
        $this->authorize('Update');
        $setting = Setting::first();

        $currentPhoto = $setting->logo;
        if($request->logo != $currentPhoto)
        {
            $name = time().'.'. explode('/',explode(':',substr($request->logo,0,strpos($request->logo, ';')))[1])[1];
            \Image::make($request->logo)->resize(300, 300)->save(public_path('images/logo/').$name);

            $request->merge(['logo'=>$name]);
            $userPhoto = public_path('images/logo/').$currentPhoto;
            if(file_exists($userPhoto))
            {
                @unlink($userPhoto);
            }
        }

        $currentPhoto = $setting->background;
        if($request->background != $currentPhoto)
        {
            $name = time().'.'. explode('/',explode(':',substr($request->background,0,strpos($request->background, ';')))[1])[1];
            \Image::make($request->background)->save(public_path('images/logo/').$name);

            $request->merge(['background'=>$name]);
            $userPhoto = public_path('images/logo/').$currentPhoto;
            if(file_exists($userPhoto))
            {
                @unlink($userPhoto);
            }
        }

        $setting->name = $request->name;
        $setting->logo = $request->logo;
        $setting->background = $request->background;
        $setting->address = $request->address;
        $setting->phone = $request->phone;
        $setting->full_name = $request->full_name;
        $setting->save();
        return "success";
    }

   

    public function backup()
    {
        $this->authorize('isSetting');
         Artisan::call('backup:run');
         $path = url('storage/app/Laravel/'.date('Y-m-d-H-i-s').'.zip');
        return response()->json($path);
    }
}
