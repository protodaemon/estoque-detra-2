<?php

/*

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;

class CheckNomeServidor
{
    public function handle($request, Closure $next)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        return $next($request);
    }
}
*/

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class CheckNomeServidor
{
    public function handle($request, Closure $next)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verifique se a sessão está presente
        if (!isset($_SESSION['nomeServidor'])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Sincronize a sessão com o Laravel, se necessário
        Session::put('nomeServidor', $_SESSION['nomeServidor']);

        return $next($request);
    }
}

