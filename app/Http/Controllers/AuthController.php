<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request){
        return view('front.account.login');
    }
    public function authenticate(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if($validator->passes()){

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                if($user->role == 1){
                    if(session()->has('url.intended')){
                       //dd(session()->get('url.intended')) ;
                        return redirect(session()->get('url.intended'));
                    }
                    return redirect()->route('user.profile');
                }else{
                    return redirect()->route('user.login')->with('error','you are not valid to login');
                }
                
            }else{
                return redirect()->back()->withInput($request->only('email'))->with('error','email or password is wrong');
            }

        }else{
            return redirect()->back()->withErrors($validator)->withInput($request->only('email'));
        }
    }
    public function registation(Request $request){
        return view('front.account.registation');
    }

    public function registationUser(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'password' => 'required|confirmed',
        ]);

        if($validator->passes()){

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();

            session()->flash('success','you are registered successfully');

            return response()->json([
                'status' => true,
                'messege' => 'user add successfuly'
            ]);

        }else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function profile(){
        return view('front.account.profile');
    }
    public function logout(){
        Auth::logout();
        return redirect()->route('user.login');
    }
}
