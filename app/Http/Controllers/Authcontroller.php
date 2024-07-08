<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;


class Authcontroller extends Controller
{
    public function register(Request $request){
        $validator = validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',

        ]);
        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response , 400);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;
        $response= [
            'success' => true,
            'data' => $success,
            'message'=> 'user register successfully'
        ];
        return response()->json($response , 200);


    }
    

    public function login(Request $request){
        if(Auth::attempt(['email'=> $request->email, 'password'=> $request->password])){
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;

            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'user login successfully'
            ];
            return response()->json($response , 200);
        }
        else{
            $response =[
                  'success' => false,
                  'message' => 'unauthorized',
            ];
        }
    }
}
