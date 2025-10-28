<?php 

use Illuminate\Http\Request;
use App\Models\Usuario;

class token{
    public function checkToken(Request $request)
{
    $header = $request->header('Authorization');
    if (!$header || !str_starts_with($header, 'Bearer ')) {
        return response()->json(['message' => 'Token obrigatório'], 401);
    }

    $token = substr($header, 7);

    $usuario = Usuario::where('api_token', $token)
        ->where('api_token_expira_em', '>', now())
        ->first();

    if (!$usuario) {
        return response()->json(['message' => 'Token inválido ou expirado'], 401);
    }

    // Token válido, pode continuar
    // Por exemplo, armazenar o usuário na request
    $request->user = $usuario;
}


}