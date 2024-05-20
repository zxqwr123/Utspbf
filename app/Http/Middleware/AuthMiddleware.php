<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // return $next($request);
        $jwt = $request->bearerToken();

        // cek jika jwt null atau kosong
        if ( is_null($jwt) ) {
            return response("Akses ditolak",401);
        }

        $decode = JWT::decode($jwt, new Key(env("JWT_SECRET_KEY"), 'HS256'));
        if ( $decode->role == 'admin' ) {
            return $next($request);
        }

        return response()->json("Anda tidak memiliki hak akses admin", 200);
    }
}