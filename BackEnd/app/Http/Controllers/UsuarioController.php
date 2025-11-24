<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'user' => 'required|string|unique:usuario,user',
            'senha' => 'required|string|min:6',
            'nome' => 'required|string|max:255',
            'email_rec' => 'nullable|email|max:255'
        ]);

        $usuario = Usuario::create([
            'user' => $request->user,
            'senha' => $request->senha,
            'nome' => $request->nome,
            'email_rec' => $request->email_rec
        ]);

        return response()->json(['message' => 'Usuário registrado com sucesso!'], 201);
    }

    public function login(Request $request)
    {
        $credentials = [
            'user' => $request->user,
            'password' => $request->senha,
        ];

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        return response()->json([
            'token' => $token,
            'usuario' => auth()->user(),
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    public function refresh()
    {
        return response()->json([
            'token' => auth()->refresh()
        ]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
