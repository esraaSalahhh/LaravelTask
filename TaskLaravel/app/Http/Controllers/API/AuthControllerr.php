<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use DB;
use App\Session;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AuthControllerr extends Controller
{
	private $apiToken;

	public function __construct()
  {
    $this->apiToken = uniqid(base64_encode(Str::random(30)));
  }

    public function Register(Request $request){
    	$validator = Validator::make($request->all(),[
    		'name'=>'required',
    		'email'=>['required','unique:adminn','email'],
    		'password'=>['required','min:8'],
    		'phone'=>'required',
    		'city'=>'required',
    		'gender'=>'required',
    		'age'=>'required',
    		]);
    	 if ($validator->fails()) {
    	return response()->json(['error'=>$validator->errors()], 400);      
    } else {
    	$admin=DB::table('adminn')->where('email',$request->email)->first();
    	 if($admin){
        return response()->json([
          'message' => 'User already exist',
          'code' => 400,
        ]);
      }
      else{
      	$pw=Hash::make($request->password);

      	DB::table('adminn')->insert([
      		'name'=>$request->name,
      		'email'=>$request->email,
      		'password'=>$pw,
      		'phone'=>$request->phone,
      		'city'=>$request->city,
      		'gender'=>$request->gender,
      		'age'=>$request->age,
      		'created_at'=>Carbon::now(),
      		'updated_at'=>Carbon::now(),
]);

      	return response()->json([
              'name'   => $request->name,
              'email' =>  $request->email,
              'token' => $this->apiToken,
              'code' => 200,
            ],200);  

    	}
	}}
	public function Login(Request $request){
		$validator=Validator::make($request->all(),[
			'email'=>'required|email',
			'password'=>'required'
			]);
		 if ($validator->fails()) {
    	return response()->json(['error'=>$validator->errors()], 400);      
    } else {
    	$admin=DB::table('adminn')->where('email',$request->email)->first();
    	if($admin){
    		if( password_verify($request->password, $admin->password) ) {
    			$session = new Session;
          $session->Admin_id = $admin->id;
          $session->token = $this->apiToken;
          $session->save();

    		return response()->json([
              'client_id'=> $admin->id,
              'token' => $this->apiToken,
              'message' => 'Login successfully',
              'code' => 200,
            ],200);
    		}
    		else{
    			return response()->json([
            'message' => 'Invalid Password',
            'code' => 400,
          ]);
    		}

    	}
    	else {
        return response()->json([
          'message' => 'email not found',
          'code' => 400,
        ]);
      }

    }
	}

	public function Logout($token)
  {
    $Admin=DB::table('session')->where('token',$token)->delete();
    if($Admin) {
        return response()->json([
          'message' => 'User Logged Out',
          'code' => 200,
        ]);      
    } else {
      return response()->json([
        'message' => 'User not found',
        'code' => 400
      ]);
    }
  }

  public function updateProfile(Request $request,$id){
  	$pw=Hash::make($request->password);
  	   	if(DB::table('adminn')->where('id', $id)->exists()) {
    		$admin=DB::table('adminn')->Find($id);
    		DB::table('adminn')->where('id', $id)->update([
    			'name' => is_null($request->name) ? $admin->name : $request->name,
    			'password'=>is_null($pw) ? $admin->password :$pw,
    			'phone'=>is_null($request->phone) ? $admin->phone : $request->phone,
    			'city'=>is_null($request->city) ? $admin->city : $request->city,
    			'gender'=>is_null($request->gender) ? $admin->gender : $request->gender,
    			'age'=>is_null($request->age) ? $admin->age : $request->age,
    			]);
    		return response()->json([
            "message" => "product updated successfully"
        ], 200);


    		  } else {
        return response()->json([
          "message" => "Product not found"
        ], 404);
      }
  }


}
