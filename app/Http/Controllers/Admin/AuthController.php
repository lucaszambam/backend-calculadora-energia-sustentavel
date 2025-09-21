<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required','min:8'],
        ]);

        $admin = Administrador::where('email', $data['email'])->first();

        if (!$admin || !Hash::check($data['password'], $admin->senha_hash)) {
            return back()->withErrors(['email' => 'Credenciais inválidas.'])->withInput();
        }

        Auth::guard('web')->login($admin, false);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        return redirect()->route('admin.login.form');
    }
}
