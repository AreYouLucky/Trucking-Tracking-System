<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {

        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return Auth::user();
            // return redirect()->intended('dashboard');
        }
        return response()->json([
            'errors' => [
                'logs' => 'Invalid Credentials!',
            ]
        ], 422);
    }

    public function logout(Request $req){
        Auth::logout();
        $req->session()->invalidate();

        $req->session()->regenerateToken();

        return redirect('/');
    }
}
