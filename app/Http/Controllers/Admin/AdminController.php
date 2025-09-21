<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Administrador::paginate(10);
        return view('admin.administradores.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.administradores.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:administradores,email',
            'password' => 'required|min:8|confirmed'
        ]);

        Administrador::create([
            'nome' => $data['nome'],
            'email' => $data['email'],
            'senha_hash' => Hash::make($data['password']),
        ]);

        return redirect()->route('admin.administradores.index')->with('ok', 'Administrador criado com sucesso!');
    }

    public function destroy($id)
    {
        $administrador = $admin = Administrador::findOrFail($id);
        
        if (Auth::guard('web')->id() == $administrador->id_admin) {
            return back()->with('ok', 'Você não pode excluir sua própria conta.');
        }

        $administrador->delete();
        return back()->with('ok', 'Administrador removido com sucesso!');
    }
}
