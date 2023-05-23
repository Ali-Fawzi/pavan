<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Str;




class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'role_id' => 'required|exists:roles,id' // Assuming 'roles' is the table name for roles
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = new User();
            $user->uuid = Str::uuid();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            // Set other fields as needed

            $user->save();

            // Assign the appropriate role to the user
            $role = Role::findOrFail($request->input('role_id'));
            $user->role()->associate($role);
            $user->save();
           // Generate token for the registered user
           $token = Auth::login($user);

           return response()->json([
               'token' => $token,
               'user' => $user,
               'message' => 'User created successfully'
           ], 201);

       } catch (\Exception $e) {
        // Log the error for debugging purposes
        Log::error($e->getMessage());

        // Return error response
        return response()->json(['error' => 'An error occurred during registration'], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $validator->validated();

        if (!auth('api')->claims(['role' => 'bar'])->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = auth('api')->claims(['role' => auth('api')->user()->role->name])->attempt($credentials);

        return $this->createNewToken($token);
    }


    //get admins and doctors accounts
    public function getAdminAccounts()
    {
        // Retrieve only admin accounts
        $adminRole = Role::where('name', 'admin')->first();

        $adminAccounts = User::whereHas('roles', function ($query) use ($adminRole) {
            $query->where('role_id', $adminRole->id);
        })->get();

        return response()->json(['admin_accounts' => $adminAccounts]);
    }

    public function getDoctorAccounts()
    {
        // Retrieve only doctor accounts
        $doctorRole = Role::where('name', 'doctor')->first();

        $doctorAccounts = User::whereHas('roles', function ($query) use ($doctorRole) {
            $query->where('role_id', $doctorRole->id);
        })->get();

        return response()->json(['doctor_accounts' => $doctorAccounts]);
    }


    private function createNewToken($token)
    {
        $user = auth('api')->user();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    public function logout(): JsonResponse
    {
        // Invalidate the current token
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'User successfully logged out']);
    }


//     /**
//      * Refresh a token.
//      *
//      * @return \Illuminate\Http\JsonResponse
//      */
    public function refresh() {
        $token = JWTAuth::refresh();

        return $this->createNewToken(auth()->$token);
    }
}
