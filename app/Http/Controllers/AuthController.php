<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Session::get('authenticated')) {
            return redirect()->route('employees.index');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $correctPassword = env('APP_ACCESS_PASSWORD');

        if (! $correctPassword) {
            return back()->withErrors(['password' => 'Sistem belum dikonfigurasi dengan password akses.']);
        }

        if ($request->password === $correctPassword) {
            Session::put('authenticated', true);

            return redirect()->route('employees.index');
        }

        return back()->withErrors(['password' => 'Password yang Anda masukkan salah.']);
    }

    public function logout()
    {
        Session::forget('authenticated');

        return redirect()->route('login');
    }
}
