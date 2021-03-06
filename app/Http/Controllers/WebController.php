<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
class WebController extends Controller
{
    public function index(){
        return view('web.register');
    }
    public function post(Request $request){
        // return $request;
        $request->validate([
            'sponsor_id' => 'required',
            'sponsor_name' => 'required',
            'name' => 'required',
            'mobile_no' => 'required',
            'email' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
        ]);

        //create random sponserid
        $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz{}[]$!/+';
        $rendom_pin  = substr(str_shuffle($data), 0, 10);
        $mid = User::max('id');
        // return $mid+1;
        if($mid < 10){
        $member_id = 'GF1' . "0000" . $mid+1;
        }
        elseif($mid < 100){
            $member_id = 'GF1' . "000" . $mid+1;
        } 
        else{
            $member_id = 'GF1' . "00" . $mid+1;
        }
        $User = new User();
        $User->track_id = $rendom_pin;
        $User->member_id = $member_id;
        $User->sponsor_id = $request->sponsor_id;
        $User->sponsor_name = $request->sponsor_name;
        $User->name = $request->name;
        $User->mobile_no = $request->mobile_no;
        $User->status = 'Inactive';
        $User->top_up = "no";
        $User -> joining_date_from=date('d-m-Y H:i:s');
        $User->email = $request->email;
        $User->password = $request->password;
        $User->save();
        return redirect('User');
    }
    public function edit($id)
    {
        return view('User/edit_user')->with('data',User::find($id));
    }
    public function update(Request $req)
    {
        // return $req;
        $id = $req -> post('pid');
        $sponser_id = $req -> post('sponser_id');
        $member_id = $req -> post('member_id');
        $new_member_id = $req -> post('new_member_id');
        $data = User::find($id);
        // return $member_id;
        
        if($data -> top_up == 'yes' && $data -> member_id == $member_id){
             $result =  DB::table('users') 
             ->where('id', $id)
             ->limit(1) 
             ->update(['member_id'=>$new_member_id,'activation_date_from'=>date('d-m-Y H:i:s')]);
             if($result){
                 return view('User/edit_user')->with('message','User Activated successfully!');
             }else{
                 return view('User/edit_user')->with('error','User Not Activated!');
             }
        }else{
            return "No";
        }
       
    }
}
