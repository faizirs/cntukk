<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function formLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.index');
            } elseif ($user->role === 'siswa') {
                return redirect()->route('siswa.index');
            }

            Auth::logout();
            return back()->withErrors(['login' => 'Role tidak dikenali.']);
        }

        return back()->withErrors(['login' => 'Username atau Password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}