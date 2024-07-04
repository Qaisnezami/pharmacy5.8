<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Stock;
use Illuminate\Http\Request;
use App\Notifications\ExpiryDate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class NotificationsController extends Controller
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
        $date = date("Y-m-d", strtotime("+3 months"));
        $data = DB::table('stocks')
        ->join('medicines','stocks.medicine_id','medicines.id')
        ->select([
            'stocks.*',
            'medicines.name',
            'medicines.barcode',
        ])
        ->where('stocks.quantity','>',0)
        ->where('stocks.expiry_date','<=',$date)->get();
        $message = 'is about to expiry';
        foreach($data as $d)
        {
            if($d->notify == 0)
            {
                $users = User::all();
                Notification::send($users, new ExpiryDate($d,$message));
                $stock = Stock::find($d->id);
                $stock->notify = 1;
                $stock->save();
            }
        }

        return response()->json(['unreadNotifications'=>Auth::user()->unreadNotifications,'unreadNotificationsCount'=>Auth::user()->unreadNotifications->count(),'readNotifications'=>Auth::user()->readNotifications->sortByDesc('created_at')->take(5)]);
        
    }


    public function read()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['readNotifications'=>Auth::user()->readNotifications->sortByDesc('created_at')->take(5)]);
    }

    
}
