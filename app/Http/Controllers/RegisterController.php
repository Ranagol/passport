<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //here we are validating the data sent from the user for registration
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',//this is the repeated password
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);//please crypt the password Laravel
        $user = User::create($input);//here we are creating our user in our users table in db
        $success['token'] =  $user->createToken('MyApp')->accessToken;//here we are creating the token for our user
        $success['name'] =  $user->name;
        return $this->sendResponse($success, 'User register successfully.');
        /*
        Example for a successfull registration response:
            "success": true,
            "data": {
                "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNTM1YjI5NTgwYWRlY2IzMWM4OTg0MjJiNjFkNjQzOGIzMDE4ODA2ODMwMGMzYTVlZGU4MTBiZGJlMjg2OTk3ZTI5YmE2MTliNjRlOWFjNjEiLCJpYXQiOjE1ODUxMzExNTksIm5iZiI6MTU4NTEzMTE1OSwiZXhwIjoxNjE2NjY3MTU5LCJzdWIiOiIyIiwic2NvcGVzIjpbXX0.VVrNRhoscNODNjShrJaRK76F6EUqtI08ajb6FbkLeKXIjsqeArNR4jcHQTi7Y58hXhE429L-VNuahdqTI0R5NdGPrQy54oDwHcdu8jsG99NZYuoN5-m4Ga85NiQWsin7XExLnn_QzvVUe05EIno6dTcMsR2yd8a2m4ZhRW0snx1Bc-oUxkVUkwMlNqLEZtjX6AuS8ZHSpWsFc8h3R2uUJbVFZI69ub2g1iDeNeXTniwntaxVSsrp_bEVRh07ij4GWgAq13S2Mdhj9yFwa2in0eKADRRhwNsExgPbioWNGUBIzd4V3tbH1UvpvTA5_Brcc4VSAnJV1oJKOtfp4Tl956YeblPA14xOozgWGBIBaHpYqIQxbrmROJQQx529BAs1sB0-8A1p29FUeiN6CVa6NkUh9HnMLv-LmIf9ehowkLNTS1o8zKh4lkEfPbqsX2gK3beTHGFiMzLLlVtkglEc-Jy9HwCTuVcYmhUDrB8GEi2SW1ThPKeo1wYHShEUzVuQ1JV0LyD0Q0BnltgWyCbajYbmsSgyeC4S27rPl9VEgerOESSJu77_-aZ3wPYFsw0bpzb4U4P1puxg3N_9msjD18Ft1VHUHl95C69TQGlvJZVfY4Ufd8-ti97mHZzUa6C6PFXgAAehrNxiK7xbFzUsPW_QEZC6wc86ZUiEoesBTV4",
                "name": "Losi"
            },
            "message": "User register successfully."


        */
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){//do the authorization. Take the users email and password from the request, and authorize him. If the authorization is successfull..
            $user = Auth::user();//...take the user, and...
            $success['token'] =  $user->createToken('MyApp')-> accessToken;//...create a token for him
            $success['name'] =  $user->name;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }
}
