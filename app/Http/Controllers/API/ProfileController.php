<?php

namespace App\Http\Controllers\api;

use App\Role;
use App\User;
use App\Profile;
use phpseclib\Crypt\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ProfileController extends Controller
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
        $this->authorize('isProfile');
        return auth('api')->user();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->authorize('isProfile');
        $user = auth('api')->user();

        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|email|unique:users,email,'.$user->id,
            'password'=>'sometimes|required',
        ]);

        $currentPhoto = $user->photo;
        if($request->photo != $currentPhoto)
        {
            $name = time().'.'. explode('/',explode(':',substr($request->photo,0,strpos($request->photo, ';')))[1])[1];
            \Image::make($request->photo)->resize(300, 300)->save(public_path('images/users/').$name);

            $request->merge(['photo'=>$name]);
            $userPhoto = public_path('images/users/').$currentPhoto;
            if(file_exists($userPhoto))
            {
                @unlink($userPhoto);
            }
        }


        if(!empty($request->password)){
            $request->merge(['password'=>bcrypt($request->password)]);
        }else{
            $request->merge(['password' => $user->password]);
        }
        $user->update(
            ['id'=>$request->id,'name'=>$request->name,'email'=>$request->email,'photo'=>$request->photo,'password'=>$request->password]
        );

        return ['message'=>$request->all()];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        //
    }
}
