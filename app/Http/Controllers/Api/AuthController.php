<?php

namespace App\Http\Controllers\Api;

use Session;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{

    public function register(Request $request){
        $request->validate([
            'email' => 'required|string|email|unique:users,email',
            'username' => 'required|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'role' => 3,
            'password' => bcrypt($request->password)
        ]);

       return response()->json($user,200);
    }

    public function login(LoginRequest $request){
    
        $credentials = $request->getCredentials();

        if(!Auth::attempt($credentials)):
            // $request->session()->regenerate();
            $errors = ['errors'=>['main' => ['Wrong Credential!']]];
            return response()->json($errors,401);
        endif;

        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        $a = Auth::user();
        return response()->json($a,200);
    }

    public function logout()
    {
        try {
            Session::flush();
            $success = true;
            $message = 'Successfully logged out';
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        // response
        $response = [
            'success' => $success,
            'message' => $message,
        ];
        return response()->json($response);
    }

    public function adminprofile(Request $request, $id){
        $request->validate([
                
            'email' => 'required|string|email',
            'username' => 'required|string',
        ]);

        $user = User::find($id);
        $user->username = $request->username;
        $user->email = $request->email;
        $user->save();
        return response()->json($user, 200);
    }

    public function adminpassword(Request $request){

        $request->validate([
            'username' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $pass = User::find($request->id);
        $pass->password = bcrypt($request->password);
        $pass->save();

        return response()->json($pass, 200);
    }

}
