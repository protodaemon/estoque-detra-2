<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'user' => 'required|string',
            'senha' => 'required|string'
        ]);

        $usuario = Usuario::where('user', $request->user)->first();

        if (!$usuario || !Hash::check($request->senha, $usuario->senha)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        $token = bin2hex(random_bytes(40));
        $expira = now()->addHours(2); // expira em 2 horas

        $usuario->api_token = $token;
        $usuario->api_token_expira_em = $expira;
        $usuario->save();

        return response()->json([
            'token' => $token,
            'usuario' => $usuario
        ]);
    }

    public function logout(Request $request)
    {
        $usuario = Usuario::where('api_token', $request->bearerToken())->first();

        if ($usuario) {
            $usuario->api_token = null;
            $usuario->api_token_expira_em = null;
            $usuario->save();
        }

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

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
        'senha' => $request->senha, //criptografada feita pelo mutator na model
        'nome' => $request->nome,
        'email_rec' => $request->email_rec
    ]);

    return response()->json([
        'message' => 'Usuário registrado com sucesso!',
        'usuario' => $usuario
    ], 201);
}

}
