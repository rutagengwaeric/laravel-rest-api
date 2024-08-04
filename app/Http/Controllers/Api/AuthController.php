<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => "required|email|max:255",
            'password' => "required|string|min:8|max:255",
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => "Incorrect credentials"
            ], 401);
        }

        $token = $user->createToken($user->name . 'Auth-Token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token-type' => 'Bearer',
            'token' => $token
        ], 200);
    } // End Method

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => "required|string|max:255",
            'email' => "required|email|unique:users,email|max:255",
            'password' => "required|string|min:8|max:255",
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            $token = $user->createToken($user->name . 'Auth-Token')->plainTextToken;
            return response()->json([
                'message' => 'User Registered Successfully',
                'token-type' => 'Bearer',
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'message' => 'Something Went Wrong while registering',
            ], 401);
        }
    } // End Method

    public function logout(Request $request){

        $user = User::where('id', $request->user()->id)->first();
        if($user){
            $user->tokens()->delete();
            return response()->json([
                'message' => 'User Logout Successfully',
            ], 200);

        }else{
            return response()->json([
                'message' => 'User Not Found',
            ], 404);
        }
    }

    public function  profile(Request $request){

        if($request->user()){

            return response()->json([
                'message' => 'Fetched Data',
                'data' => $request->user(),
            ], 200);


        }else{
            return response()->json([
                'message' => 'Not Authenticated',
            ], 401);
        }

    }
}

