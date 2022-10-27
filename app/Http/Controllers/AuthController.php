<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){

        $field = $request->validate([
            'name'=> 'required|string',
            'email' => 'required|string|unique:users,email' ,
            'password' => 'required|string|confirmed'
        ]);
        $user = User::create([
            'name'=>$field['name'],
            'email'=>$field['email'],
            'password'=> bcrypt($field['password']),

        ]);

        $token = $user->createToken('myapptoken')->plainTextToken ;

        $response = [
            'user'=> $user ,
            'token'=> $token 
        ];
        return response($response,201);
    }

    public function login(Request $request){

        // $field = $request->validate([
        //     'email' => 'required|string' ,
        //     'password' => 'required|string'
        // ]);
        $validate = Validator::make($request->all(),[
            'email' => 'required|string' ,
            'password' => 'required|string'
        ]);
       if($validate->fails()){
            return response()->json([
                'validation_errors' => $validate->getMessageBag(),
            ]);
       }

        //Check Email 
        $user = User::Where('email',$request->email)->first();
        //Check Password 
        if(!$user || !Hash::check($request->password ,$user->password)){
            return response()->json([
                'status'=> 401 ,
                'message' => 'Invalid Email or Password ...!! '
    
            ]);
        }
        // $token = $user->createToken('myapptoken')->plainTextToken ;
        $token = $user->createToken($user->email.'_Token')->plainTextToken ;
        // $response = [
        //     'user'=> $user ,
        //     'token'=> $token 
        // ];
        return response()->json([
            'status'=> 200 ,
            'Name' => $user->name , 
            'role' => $user->role ,
            'token' => $token ,
            'message' => 'Logged in Successfully ... '

        ]);
    }


    public function logout(Request $request){
        Auth::user()->tokens->each(function($token, $key) {
            $token->delete();
        });
        return response()->json(['message'=>'Successfully logged out']);

    }
}
