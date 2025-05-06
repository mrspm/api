<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function login()
    {
        return response()->json([
            'message' => 'Unauthenticated.',
        ], 401);

        //return view('users.login-form');
    }


    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = 0;
        if(!empty($request->remember)) $remember = 1;

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ], $remember)) {
            return redirect()->route('admin.index');
        }

        return redirect()->back()->with('error', 'Неправильный email или пароль');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function addAdmin()
    {
        $model = User::query()->where('email', 'info@iondigital.de')->first();
        if(empty($model)) {
            $model = new User;
            $model->name = 'admin';
            $model->email = 'info@iondigital.de';
            $model->password = Hash::make('');
            $model->save();

            die;
        }
    }
}
