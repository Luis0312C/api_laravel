<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ResetMail;
use App\Models\Empleado;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ApiController extends Controller
{

    public function resetPassword(Request $request)
    {
        $request->validate([
            'username' => 'required|email|string',
        ]);

        $user = User::where("username", $request->username)->first();

        if (!empty($user)) {
            $newPassword = substr(md5(uniqid(rand(), true)), 0, 8);
            $hashedPassword = md5($newPassword);

            $user->password = $hashedPassword;
            $user->save();

            Mail::to($user->username)->send(new ResetMail($newPassword));

            return response()->json([
                "status" => true,
                "message" => "Password reset successful. Please check your email for the new password.",
                "data" => []
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "User not found",
                "data" => []
            ]);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|email|string',
            'password' => 'required'
        ]);

        $user = User::where("username", $request->username)->first();

        if (!empty($user)) {
            if (md5($request->password) === $user->password) {
                $token = $user->createToken('mytoken')->accessToken;

                return response()->json([
                    "status" => true,
                    "message" => "Login successful",
                    "token" => $token,
                    "data" => []
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Password didn't match",
                    "data" => []
                ]);
            }
        } else {
            return response()->json([
                "status" => false,
                "message" => "Invalid Email Value",
                "data" => []
            ]);
        }
    }


    // GET [Auth: Token]
    public function profile()
    {
        $userData = auth()->user();

        return response()->json([
            "status" => true,
            "message" => "Profile information",
            "data" => [
                'id_user' => $userData->id_user,
                'username' => $userData->username,
                'register_at' => $userData->register_at,
                'last_login' => $userData->last_login,
                'id_rol' => $userData->id_rol,
                'about' => $userData->about,
                'mi_agenda' => $userData->mi_agenda,
                'created_at' => $userData->created_at,
                'id_empleado' => $userData->id_empleado,

            ],
        ]);
    }

    // GET [Auth: Token]
    public function logout()
    {

        $token = auth()->user()->token();

        $token->revoke();

        return response()->json([
            "status" => true,
            "message" => "User Logged out successfully"
        ]);
    }
}
